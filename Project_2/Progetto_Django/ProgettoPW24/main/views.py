from django.shortcuts import render

def index(request):
    return render(request, 'index.html')

def aggiungi(request):
    return render(request, 'aggiungi.html')

def elimina(request):
    return render(request, 'elimina.html')

def modifica(request):
    return render(request, 'modifica.html')

def revisione(request):
    return render(request, 'revisione.html')

def targa(request):
    return render(request, 'targa.html')

def veicolo(request):
    return render(request, 'veicolo.html')
