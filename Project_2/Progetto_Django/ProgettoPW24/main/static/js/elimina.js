function testoHiddenDivElimina(testo) {
    document.getElementById('hiddenDiv').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';

    document.getElementById('testo2').innerHTML = testo;
    document.getElementById('overlay2').style.display = 'block';
    var hiddenDiv = document.getElementById('hiddenDiv2');
    hiddenDiv.style.display = 'block';
    hiddenDiv.style.opacity = 1;
    setTimeout(function () {
        
    }, 3000); // reindirizza dopo 3 secondi
}

function Mostra(numTarga) {
    document.getElementById("overlay").style.display = "block";
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.classList.toggle('show');
    document.getElementById("numeroTarga").innerText = numTarga;
    document.getElementById("targaDaCancellare").value = numTarga;
    document.getElementById("bottoneElimina").onclick = function () {
       // Elimina(numTarga);
    
    };
}

function Annulla() {
    document.getElementById("overlay").style.display = "none";
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.classList.toggle('show');
}

function elimina() {
    const form = document.getElementById('elimina-form');
    const targaDaCancellare = document.getElementById('targaDaCancellare');

    // Chiama la funzione testoHiddenDivElimina(testo)
    testoHiddenDivElimina(`Targa eliminata con successo: ${targaDaCancellare.value}`);

    // Invia il form
    form.submit();
}
