function testoHiddenDivElimina(testo) {
    document.getElementById('hiddenDiv').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';

    document.getElementById('testo2').innerHTML = testo;
    document.getElementById('overlay2').style.display = 'block';
    var hiddenDiv = document.getElementById('hiddenDiv2');
    hiddenDiv.style.display = 'block';
    hiddenDiv.style.opacity = 1;
    setTimeout(function () {
        window.location.href = '../../templates/targa';
    }, 3000); // reindirizza dopo 3 secondi
}

function Mostra(numTarga) {
    document.getElementById("overlay").style.display = "block";
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.classList.toggle('show');
    document.getElementById("numeroTarga").innerText = numTarga;
    document.getElementById("bottoneElimina").onclick = function () {
        Elimina(numTarga);
    };
}

function Annulla() {
    document.getElementById("overlay").style.display = "none";
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.classList.toggle('show');
}

function Elimina(numTarga) {
    $.ajax({
        url: '{% url "elimina_targa" %}',
        type: 'POST',
        data: {
            targa: numTarga,
            csrfmiddlewaretoken: '{{ csrf_token }}'
        },
        success: function (response) {
            if (response.success) {
                testoHiddenDivElimina('Targa eliminata con successo');
            } else {
                testoHiddenDivElimina('Errore durante l\'eliminazione della targa: ' + response.message);
            }
        },
        error: function (xhr, status, error) {
            testoHiddenDivElimina('Errore durante l\'eliminazione della targa');
        }
    });
}