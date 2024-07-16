from django import forms

class RicercaRevisioneForm(forms.Form):
    numerorevisione = forms.IntegerField(required=False, min_value=1, max_value=50)
    numerotarga = forms.CharField(max_length=7, required=False)
    datarevisione = forms.DateField(required=False)
    esito = forms.ChoiceField(choices=[
        ('positivo', 'Positivo'),
        ('negativo', 'Negativo'),
        ('indifferente', 'Indifferente')
    ], widget=forms.RadioSelect, initial='indifferente')
    scelta = forms.ChoiceField(choices=[
        ('ordinamentoNullo', 'Nessun ordinamento'),
        ('ordinaNumeroRev', 'Numero di revisione'),
        ('ordinaNumeroTarga', 'Numero di targa'),
        ('ordinaPositivo', 'Prima i positivi'),
        ('ordinaNegativo', 'Prima i negativi')
    ], initial='ordinamentoNullo')
