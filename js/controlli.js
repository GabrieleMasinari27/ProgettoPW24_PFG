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