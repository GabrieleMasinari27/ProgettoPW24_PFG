from django.shortcuts import render
from .models import Revisione
from .forms import RicercaRevisioneForm

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

def ricerca_revisioni(request):
    form = RicercaRevisioneForm(request.POST or None)
    query_results = None

    if request.method == 'POST' and form.is_valid():
        numRevisione = form.cleaned_data['numerorevisione']
        numTarga = form.cleaned_data['numerotarga']
        dataRevisione = form.cleaned_data['datarevisione']
        esito = form.cleaned_data['esito']
        scelta = form.cleaned_data['scelta']

        query = Revisione.objects.all()

        if numRevisione:
            query = query.filter(numRevisione=numRevisione)
        if numTarga:
            query = query.filter(numTarga__icontains=numTarga)
        if dataRevisione:
            query = query.filter(dataRevisione=dataRevisione)
        if esito != 'indifferente':
            query = query.filter(esito=esito)

        if scelta == 'ordinaNumeroRev':
            query = query.order_by('numRevisione')
        elif scelta == 'ordinaNumeroTarga':
            query = query.order_by('numTarga')
        elif scelta == 'ordinaPositivo':
            query = query.order_by('esito')
        elif scelta == 'ordinaNegativo':
            query = query.order_by('-esito')

        query_results = query

    return render(request, 'ricerca_revisioni.html', {'form': form, 'query_results': query_results})