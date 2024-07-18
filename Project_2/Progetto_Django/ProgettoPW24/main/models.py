from django.db import models

class Revisione(models.Model):
    numRevisione = models.CharField(max_length=100)
    numTarga = models.CharField(max_length=100)
    dataRevisione = models.DateField()
    esito = models.CharField(max_length=100)
    motivazione = models.TextField()

    def __str__(self):
        return f"{self.numRevisione} - {self.numTarga}"
