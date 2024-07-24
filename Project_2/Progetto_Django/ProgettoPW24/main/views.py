
from .models import Revisione
from .models import Targa 
from .forms import RicercaRevisioneForm
from django.http import HttpResponse
from django.shortcuts import render, redirect
import sqlite3

def index(request):
    return render(request, 'index.html')
def revisione(request):
    if request.method == "POST":
        num_revisione = request.POST.get("numerorevisione", "")
        num_targa = request.POST.get("numerotarga", "")
        data_re = request.POST.get("datarevisione", "")
        posneg = request.POST.get("esito", "")
        valore_ordinamento = request.POST.get("scelta", "")
    else:
        num_revisione = ""
        num_targa = ""
        data_re = ""
        posneg = ""
        valore_ordinamento = ""
    result = get_data_revisione(num_revisione,num_targa,data_re,posneg,valore_ordinamento)
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

def get_data_revisione(num_revisione="", num_targa="", data_re="", posneg="indifferente", valore_ordinamento=""):
    conn = sqlite3.connect('db.sqlite3')
    cursor = conn.cursor()
    qry = """
        SELECT
            REVISIONE.numero AS numRevisione,
            REVISIONE.dataRev AS dataRevisione,
            REVISIONE.targa AS numTarga,
            REVISIONE.esito AS esito,
            REVISIONE.motivazione AS motivazione
        FROM REVISIONE
        WHERE 1=1
    """

    if num_targa:
        qry += " AND REVISIONE.targa LIKE '%{}%'".format(num_targa)

    if num_revisione:
        qry += " AND REVISIONE.numero LIKE '%{}%'".format(num_revisione)

    if data_re:
        qry += " AND REVISIONE.dataRev LIKE '%{}%'".format(data_re)

    if posneg != "indifferente":
        qry += " AND REVISIONE.esito LIKE '%{}%'".format(posneg)

    if valore_ordinamento == "ordinamentoNullo":
        pass
    elif valore_ordinamento == "ordinaNumeroRev":
        qry += " ORDER BY REVISIONE.numero"
    elif valore_ordinamento == "ordinaNumeroTarga":
        qry += " ORDER BY REVISIONE.targa"
    elif valore_ordinamento == "ordinaPositivo":
        qry += " ORDER BY REVISIONE.esito DESC"
    elif valore_ordinamento == "ordinaNegativo":
        qry += " ORDER BY REVISIONE.esito ASC"

    cursor.execute(qry)

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
    try:
        conn = sqlite3.connect('db.sqlite3')
        cursor = conn.cursor()
        cursor.execute("SELECT COUNT(*) FROM VEICOLO WHERE telaio = ?", (telaio,))
        result = cursor.fetchone()[0]
    except sqlite3.Error as e:
        print(f"Errore del database: {e}")
        result = 0
    finally:
        conn.close()
    return result > 0

def verificaTargaAttiva(telaio):
    try:
        conn = sqlite3.connect('db.sqlite3')
        cursor = conn.cursor()
        cursor.execute("SELECT COUNT(*) FROM TARGA_ATTIVA WHERE veicolo = ?", (telaio,))
        result = cursor.fetchone()[0]
    except sqlite3.Error as e:
        print(f"Errore del database: {e}")
        result = 0
    finally:
        conn.close()
    return result > 0

def modifica(request):
    if request.method == "POST":
        numTarga = request.POST.get("NumTarga", "")
        dataEM = request.POST.get("dataEM", "")
        statoTarga = request.POST.get("radiotarga", "")
        telaio = request.POST.get("telaio", "")
        dataRES = request.POST.get("datares", "")
        OLDstato = request.POST.get("OLDstato", "")

        if verificaVeicolo(telaio): # se il veicolo scelto esiste
            if statoTarga == 'targheatt' and OLDstato == "Restituita" and verificaTargaAttiva(telaio): 
                error_message = "Mi dispiace, esiste già una targa attiva per questo veicolo. Sarai reindirizzato alla pagina delle targhe"
                return render(request, 'modifica.html', {'error': error_message})
            elif statoTarga == 'targherest' and dataRES < dataEM:
                error_message = "Mi dispiace, la data di restituzione non può essere più vecchia della data di inserimento. Sarai reindirizzato alla pagina delle targhe"
                return render(request, 'modifica.html', {'error': error_message})
            else:
                conn = None
                try:
                    conn = sqlite3.connect('db.sqlite3')
                    cursor = conn.cursor()
                    
                    if statoTarga == "targheatt" and OLDstato == "Attiva": 
                        query = """
                        UPDATE TARGA_ATTIVA
                        SET veicolo = ?
                        WHERE targa = ?
                        """
                        cursor.execute(query, (telaio, numTarga))
                    
                    elif statoTarga == "targheatt" and OLDstato == "Restituita":
                        cursor.execute("DELETE FROM TARGA_RESTITUITA WHERE targa = ?", (numTarga,))
                        cursor.execute("INSERT INTO TARGA_ATTIVA (targa, veicolo) VALUES (?, ?)", (numTarga, telaio))
                    
                    elif statoTarga == "targherest" and OLDstato == "Restituita":
                        query = """
                        UPDATE TARGA_RESTITUITA
                        SET veicolo = ?, dataRes = ?
                        WHERE targa = ?
                        """
                        cursor.execute(query, (telaio, dataRES, numTarga))
                    
                    elif statoTarga == "targherest" and OLDstato == "Attiva":
                        cursor.execute("DELETE FROM TARGA_ATTIVA WHERE targa = ?", (numTarga,))
                        cursor.execute("INSERT INTO TARGA_RESTITUITA (targa, veicolo, dataRes) VALUES (?, ?, ?)", (numTarga, telaio, dataRES))
                    
                    query = """
                    UPDATE TARGA
                    SET dataEM = ?
                    WHERE numero = ?
                    """
                    cursor.execute(query, (dataEM, numTarga))
                    
                    conn.commit()
                    success_message = "Modifica effettuata correttamente"
                    return render(request, 'modifica.html', {'success': success_message})
                
                except sqlite3.Error as e:
                    if conn:
                        conn.rollback()
                    print(f"Errore del database: {e}")
                    error_message = "Errore nella modifica della targa. Per favore riprova."
                    return render(request, 'modifica.html', {'error': error_message})
                
                finally:
                    if conn:
                        conn.close()
        else:
            error_message = "Siamo spiacenti il telaio da lei inserito per la targa non è valido.Sarai reindirizzato alla pagina di targa"
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
    conn = sqlite3.connect('db.sqlite3')
    cursor = conn.cursor()

    if request.method == 'POST':
        num_targa = request.POST.get("NumTarga")
        
        try:
            # Esegui la query di cancellazione
            cursor.execute("DELETE FROM TARGA WHERE numero = ?", (num_targa,))
            conn.commit()
            testo="Targa eliminata con successo"
            return gestisci_errore(request, testo)
        except sqlite3.Error as e:
            conn.rollback()
            testo="Errore del database:"
            return gestisci_errore(request, testo)
        finally:
            conn.close()
    return redirect('targa')


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
      
        
        conn = sqlite3.connect('db.sqlite3')

        if verificaVeicolo(telaio):
            if statoTarga == 'targheatt' and verificaTargaAttiva(telaio):
                testo = "Mi dispiace, esiste già una targa attiva per questo veicolo.Sarai reindirizzato alla pagina delle targhe"
              
                return gestisci_errore(request, testo)
            elif statoTarga == 'targherest' and dataRES < dataEM:
                testo = "Mi dispiace, la data di restituzione non può essere più vecchia della data di inserimento.Sarai reindirizzato alla pagina delle targhe"
                
                return gestisci_errore(request, testo)
            else:
               
                return gestisci_errore(request, inserimento(numTarga, dataEM, statoTarga, telaio, dataRES))
        else:
            testo = "Siamo spiacenti il telaio da lei inserito per la targa non è valido.Sarà reindirizzato alla pagina di targa"
            return gestisci_errore(request, testo)
    else:
        return render(request, 'aggiungi.html')