from django.shortcuts import render
from .models import Revisione
from .models import Targa 
from .forms import RicercaRevisioneForm
from django.http import HttpResponse
import sqlite3

def index(request):
    return render(request, 'index.html')

def elimina(request):
    return render(request, 'elimina.html')

def revisione(request):
    result = get_data_revisione()
    return render(request, 'revisione.html', {'result': result})

def targa(request):
    if request.method == "POST":
        numTarga = request.POST.get("numerotarga", "")
        dataEM = request.POST.get("dataemtarga", "")
        radiocheck = request.POST.get("radiofiltrotarga", "")
        valoreordinamento = request.POST.get("scelta", "")
        errore = request.POST.get("error", "")
    else:
        numTarga = ""
        dataEM = ""
        radiocheck = ""
        valoreordinamento = ""
        errore = ""

    result = get_targa(numTarga, dataEM, radiocheck, valoreordinamento)
    return render(request, 'targa.html', {'result': result, 'error': errore})

def veicolo(request):
    if request.method == "POST":
        numTelaio = request.POST.get("numTelaio", "")
        marca = request.POST.get("marca", "")
        modello = request.POST.get("modello", "")
        dataPro = request.POST.get("dataPro", "")
        valoreordinamento = request.POST.get("valoreordinamento", "")
    else:
        numTelaio = ""
        marca = ""
        modello = ""
        dataPro = ""
        valoreordinamento = ""
    result = query_veicolo(numTelaio, marca, modello, dataPro, valoreordinamento)
    return render(request, 'veicolo.html', {'result': result})

def get_data_revisione():
    conn = sqlite3.connect('db.sqlite3')
    cursor = conn.cursor()
    
    query = "SELECT * FROM REVISIONE"
    cursor.execute(query)
    
    rows = cursor.fetchall()
    conn.close()
    
    return rows

def get_data_targa():
    conn = sqlite3.connect('db.sqlite3')
    cursor = conn.cursor()
    
    query = "SELECT * FROM TARGA"
    cursor.execute(query)
    
    rows = cursor.fetchall()
    conn.close()
    
    return rows

def get_targa(numTarga, dataEM, radiocheck, valoreordinamento):
    conn = sqlite3.connect('db.sqlite3')
    cursor = conn.cursor()
    
    query = """
    SELECT
    TARGA.numero AS numTarga,
    TARGA.dataEM AS dataEM,
    (SELECT COUNT(*) FROM REVISIONE WHERE REVISIONE.targa = TARGA.numero) AS count_revisioni,
    TARGA_RESTITUITA.veicolo AS telaio_res_associato,
    TARGA_ATTIVA.veicolo AS telaio_att_associato,
    CASE
        WHEN TARGA.numero IN (SELECT TARGA_RESTITUITA.targa FROM TARGA_RESTITUITA) THEN 'Restituita'
        WHEN TARGA.numero IN (SELECT TARGA_ATTIVA.targa FROM TARGA_ATTIVA) THEN 'Attiva'
    END AS stato,
    COALESCE(TARGA_RESTITUITA.dataRes, 'N/A') AS dataRes
    FROM TARGA
    LEFT JOIN TARGA_ATTIVA ON TARGA.numero = TARGA_ATTIVA.targa
    LEFT JOIN TARGA_RESTITUITA ON TARGA.numero = TARGA_RESTITUITA.targa
    WHERE 1=1
    """
    
    # Aggiungo condizioni alla query
    if numTarga:
        query += " AND TARGA.numero LIKE ?"
    if dataEM:
        query += " AND TARGA.dataEM LIKE ?"
    if radiocheck == "targherest":
        query += " AND TARGA.numero IN (SELECT TARGA_RESTITUITA.targa FROM TARGA_RESTITUITA)"
    if radiocheck == "targheatt":
        query += " AND TARGA.numero IN (SELECT TARGA_ATTIVA.targa FROM TARGA_ATTIVA)"
    
    query += " GROUP BY TARGA.numero, TARGA.dataEM"
    
    # Aggiungo ordinamento alla query
    if valoreordinamento == "ordinaDataEm":
        query += " ORDER BY TARGA.dataEM"
    elif valoreordinamento == "ordinaNumeroTarga":
        query += " ORDER BY TARGA.numero"
    
    # Preparo i parametri per la query
    params = []
    if numTarga:
        params.append(f"%{numTarga}%")
    if dataEM:
        params.append(f"%{dataEM}%")
    
    # Eseguo la query
    cursor.execute(query, params)
    rows = cursor.fetchall()
    conn.close()
    
    return rows

def query_veicolo(numTelaio, marca, modello, dataPro, valoreordinamento):
    conn = sqlite3.connect('db.sqlite3')
    cursor = conn.cursor()
    
    qry = """
        SELECT
            VEICOLO.telaio AS telaio,
            VEICOLO.marca AS marca,
            VEICOLO.modello AS modello,
            VEICOLO.dataProd AS data,
            (SELECT COUNT(*) FROM TARGA_RESTITUITA WHERE TARGA_RESTITUITA.veicolo = VEICOLO.telaio) as num_restituite,
            (SELECT targa FROM TARGA_ATTIVA WHERE TARGA_ATTIVA.veicolo = VEICOLO.telaio) as targa_attiva
        FROM
            VEICOLO
        WHERE 1=1
    """
    
    if numTelaio:
        qry += f" AND VEICOLO.telaio LIKE '%{numTelaio}%'"
    
    if marca:
        qry += f" AND VEICOLO.marca LIKE '%{marca}%'"
    
    if modello:
        qry += f" AND VEICOLO.modello LIKE '%{modello}%'"
    
    if dataPro:
        qry += f" AND VEICOLO.dataProd LIKE '%{dataPro}%'"
    
    # Gestione dell'ordinamento
    if valoreordinamento == 'ordinaNumeroTel':
        qry += " ORDER BY VEICOLO.telaio"
    elif valoreordinamento == 'ordinaMarca':
        qry += " ORDER BY VEICOLO.marca"
    elif valoreordinamento == 'ordinaModello':
        qry += " ORDER BY VEICOLO.modello"
    elif valoreordinamento == 'ordinaData':
        qry += " ORDER BY VEICOLO.dataProd"
    
    cursor.execute(qry)
    rows = cursor.fetchall()
    
    conn.close()
    
    return rows

def verificaVeicolo(telaio):
    conn = sqlite3.connect('db.sqlite3')
    cursor = conn.cursor()
    cursor.execute("SELECT COUNT(*) FROM Veicolo WHERE telaio = ?", (telaio,))
    result = cursor.fetchone()[0]
    conn.close()
    return result > 0

def verificaTargaAttiva(telaio):
    # Connetto al database
    conn = sqlite3.connect('db.sqlite3')
    cursor = conn.cursor()
    
    # Eseguo la query per contare le targhe attive per il veicolo specificato
    cursor.execute("""
        SELECT COUNT(*) 
        FROM Targa_Attiva 
        WHERE veicolo = ?
    """, (telaio,))
    
    result = cursor.fetchone()[0]
    
    # Chiudo la connessione al database
    conn.close()
    
    # Ritorno True se esiste almeno una targa attiva, altrimenti False
    return result > 0
    
def modifica(request):
    if request.method == "POST":
        numTarga = request.POST.get("NumTarga", "")
        dataEM = request.POST.get("dataEM", "")
        statoTarga = request.POST.get("radiotarga", "")
        telaio = request.POST.get("telaio", "")
        dataRES = request.POST.get("datares", "")
        OLDstato = request.POST.get("OLDstato", "")

        if verificaVeicolo(telaio): #se il veicolo scelto esiste
            if statoTarga == 'targheatt' and OLDstato == "Restituita" and verificaTargaAttiva(telaio): 
                #se provo a mettere una targa attiva dove ce ne è già uma mi da errore
                error_message = "Mi dispiace, esiste già una targa attiva per questo veicolo<br>Sarai reindirizzato alla pagina delle targhe"
                return render(request, 'modifica.html', {'error': error_message})
            elif statoTarga == 'targherest' and dataRES < dataEM:
                #se provo a mettere una targa restituita con restituzione inferiore a emissione
                error_message = "Mi dispiace, la data di restituzione non può essere più vecchia della data di inserimento<br>Sarai reindirizzato alla pagina delle targhe"
                return render(request, 'modifica.html', {'error': error_message})
            else:
                if statoTarga== "targheatt" and OLDstato == "Attiva": 
                    #modifico una targa attiva, tenendola attiva
                    query = """
                    UPDATE TARGA_ATTIVA
                    SET veicolo = ?
                    WHERE targa = ?
                    ;
                    """
                    case = 1

                if statoTarga=="targheatt" and OLDstato=="Restituita":
                    #modifico una targa restituita, rendendola attiva
                    query = """
                    DELETE FROM TARGA_RESTITUITA WHERE targa = ? ;
                    INSERT INTO TARGA_ATTIVA (targa,veicolo) VALUES(?,?);
                    """
                    case = 2
                
                if statoTarga=="targherest" and OLDstato=="Restituita":
                    #modifico una targa restituita, tenendola restituita
                    query = """
                    UPDATE TARGA_RESTITUITA
                    SET veicolo = ?, dataRes = ?
                    WHERE targa = ?
                    ;
                    """
                    case = 3
                
                if statoTarga=="targherest" and OLDstato=="Attiva":
                    #modifico una targa attiva, rendendola restituita
                    query = """
                    DELETE FROM TARGA_ATTIVA WHERE targa = ? ;
                    INSERT INTO TARGA_RESTITUITA (targa,veicolo,dataRes) VALUES(?,?,?);
                    """
                    case = 4
                

                query += """
                    UPDATE Targa
                    SET dataEM= ?
                    WHERE numero = ?
                """
                conn = sqlite3.connect('db.sqlite3')
                cursor = conn.cursor()
                #ogni caso di modifica richiede variabili differenti in ordine diverso
                if case==1:
                    cursor.execute(query, (telaio, numTarga, dataEM, numTarga))
                elif case==2:
                    cursor.execute(query, (numTarga, numTarga, telaio, dataEM, numTarga))
                elif case==3:
                    cursor.execute(query, (telaio, dataRES, numTarga, dataEM, numTarga))
                elif case==4:
                    cursor.execute(query, (numTarga, numTarga, telaio, dataRES, dataEM, numTarga))
                
                conn.commit()
                conn.close()
                success_message = "La modifica è stata effettuata correttamente"
                return render(request, 'modifica.html', {'success': success_message})
        else:
            error_message = "Siamo spiacenti il telaio da lei inserito per la targa non è valido<br>Sarà reindirizzato alla pagina di targa"
            return render(request, 'modifica.html', {'error': error_message})
    else:
        numTarga = request.GET.get('numTarga', '')
        OLDdataEM = request.GET.get('dataEM', '')
        OLDstato = request.GET.get('stato', '')
        OLDtelaio = request.GET.get('telaioRes', '') if OLDstato == "Restituita" else request.GET.get('telaioAtt', '')
        OLDdataRes = request.GET.get('dataRes', '') if OLDstato == "Restituita" else ''
        context = {
            'numTarga': numTarga,
            'OLDdataEM': OLDdataEM,
            'OLDstato': OLDstato,
            'OLDtelaio': OLDtelaio,
            'OLDdataRes': OLDdataRes
        }
        return render(request, 'modifica.html', context)

def gestisci_errore(request, errore):
    request.POST = request.POST.copy()  # Copia i dati POST per mantenerli
    request.POST['error'] = errore
    return targa(request)  # Chiama la funzione `targa` per gestire la visualizzazione dell'errore

def elimina(request):
    if request.method == 'POST':
        num_targa = request.POST.get("NumTarga")
        try:
            targa = Targa.objects.get(num_targa=num_targa)
            targa.delete()
            return render(request, 'targa.html', {'success': 'La targa è stata eliminata correttamente'})
        except Targa.DoesNotExist:
            return render(request, 'targa.html', {'error': 'Targa non trovata'})
        except Exception as e:
            return render(request, 'targa.html', {'error': f'Errore: {e}'})

    return render(request, 'targa.html')


def inserimento(num_targa: str, data_em: str, radio: str, telaio: str, data_rest: str) -> None:
    # Connessione al database
    conn = sqlite3.connect('db.sqlite3')
    cursor = conn.cursor()

    try:
        if radio == 'targheatt':
            # Inserimento della targa e del veicolo associato
            cursor.execute("INSERT INTO TARGA (numero, dataEM) VALUES (?, ?)", (num_targa, data_em))
            cursor.execute("INSERT INTO TARGA_ATTIVA (targa, veicolo) VALUES (?, ?)", (num_targa, telaio))
        
        elif radio == 'targherest':
            # Inserimento della targa e del veicolo restituito
            cursor.execute("INSERT INTO TARGA (numero, dataEM) VALUES (?, ?)", (num_targa, data_em))
            cursor.execute("INSERT INTO TARGA_RESTITUITA (targa, veicolo, dataRes) VALUES (?, ?, ?)", (num_targa, telaio, data_rest))
        
        # Commit delle modifiche
        conn.commit()
        return "Inserimento effettuato correttamente"

    except sqlite3.Error as e:
        # Gestione degli errori
        print(f"Errore durante l'inserimento: {e}")
        conn.rollback()
        return "Inserimento non effettuato correttamente"
    
    finally:
        # Chiusura della connessione
        conn.close()


def aggiungi(request):
    if request.method == "POST":
        numTarga = request.POST.get("NumTarga", "")
        dataEM = request.POST.get("dataEM", "")
        statoTarga = request.POST.get("radiotarga", "")
        telaio = request.POST.get("telaio", "")
        dataRES = request.POST.get("datares", "")
        print("entrato")
        print(f"Stato targa: {statoTarga}, Telaio: {telaio}")  # Stampa di debug
        conn = sqlite3.connect('db.sqlite3')

        if verificaVeicolo(telaio):
            if statoTarga == 'targheatt' and verificaTargaAttiva(telaio):
                testo = "Mi dispiace, esiste già una targa attiva per questo veicolo<br>Sarai reindirizzato alla pagina delle targhe"
                print("targa attiva")
                return gestisci_errore(request, testo)
            elif statoTarga == 'targherest' and dataRES < dataEM:
                testo = "Mi dispiace, la data di restituzione non può essere più vecchia della data di inserimento<br>Sarai reindirizzato alla pagina delle targhe"
                print("targa restituita")
                return gestisci_errore(request, testo)
            else:
                print("innerelse: ", numTarga, dataEM, statoTarga, telaio, dataRES)
                return gestisci_errore(request, inserimento(numTarga, dataEM, statoTarga, telaio, dataRES))
        else:
            print("outerelse")
            testo = "Siamo spiacenti il telaio da lei inserito per la targa non è valido<br>Sarà reindirizzato alla pagina di targa"
            return render(request, 'aggiungi.html', {'error': testo})
    else:
        return render(request, 'aggiungi.html')