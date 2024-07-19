from django.shortcuts import render
from .models import Revisione
from .forms import RicercaRevisioneForm
import sqlite3

def index(request):
    return render(request, 'index.html')

def aggiungi(request):
    return render(request, 'aggiungi.html')

def elimina(request):
    return render(request, 'elimina.html')

def modifica(request):
    return render(request, 'modifica.html')

def revisione(request):
    result = get_data_revisione()
    return render(request, 'revisione.html', {'result': result})

def targa(request):
    if request.method == "POST":
        numTarga = request.POST.get("numerotarga", "")
        dataEM = request.POST.get("dataemtarga", "")
        radiocheck = request.POST.get("radiofiltrotarga", "")
        valoreordinamento = request.POST.get("scelta", "")
    else:
        numTarga = ""
        dataEM = ""
        radiocheck = ""
        valoreordinamento = ""

    result = get_targa(numTarga, dataEM, radiocheck, valoreordinamento)
    return render(request, 'targa.html', {'result': result})

def veicolo(request):
    result = query_veicolo("","","","","")
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