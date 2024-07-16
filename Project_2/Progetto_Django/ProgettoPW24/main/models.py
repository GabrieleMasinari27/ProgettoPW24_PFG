from django.db import models

class Revisione(models.Model):
    numRevisione = models.IntegerField()
    numTarga = models.CharField(max_length=7)
    dataRevisione = models.DateField()
    esito = models.CharField(max_length=20)
    motivazione = models.TextField()

    def __str__(self):
        return f"{self.numRevisione} - {self.numTarga}"