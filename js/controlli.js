document.addEventListener('DOMContentLoaded', (event) => {
    const radioatt = document.getElementById('radioatt');
    const radiorest = document.getElementById('radiorest');
    const datarest = document.getElementById('datarest');

    function toggleDataRest() {
        if (radiorest.checked) {
            datarest.removeAttribute('disabled');
        } else {
            datarest.setAttribute('disabled', 'disabled');
        }
    }

    // Aggiungi event listener per gli input radio
    radioatt.addEventListener('change', toggleDataRest);
    radiorest.addEventListener('change', toggleDataRest);

    // Esegui la funzione al caricamento della pagina per impostare lo stato iniziale
    toggleDataRest();
});
function convertToUpperCase(input) {
    input.value = input.value.toUpperCase();
}
function testoHiddenDiv(testo){
    document.getElementById('testo').innerHTML=testo;
    document.getElementById('overlay').style.display = 'block';
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.style.display = 'block';
    hiddenDiv.style.opacity = 1; // aggiungi questa riga
    setTimeout(function () {
        window.location.href = 'targa.php';
    }, 5000); // reindirizza dopo 2 secondi
}