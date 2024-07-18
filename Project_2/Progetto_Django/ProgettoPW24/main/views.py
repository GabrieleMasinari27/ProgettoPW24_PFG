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
    result = get_targa()
    print(result)
    return render(request, 'targa.html', {'result': result})

def veicolo(request):
    return render(request, 'veicolo.html')

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

def get_targa(numTarga="", dataEM="", radiocheck="", valoreordinamento=""):
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