
function Mostra(numTarga) {
    document.getElementById("overlay").style.display = "block";
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.classList.toggle('show');
    hiddenDiv.style.display="block";
    document.getElementById("numeroTarga").innerText = numTarga;
    document.getElementById("targaDaCancellare").value = numTarga;
   
}

function Annulla() {
    document.getElementById("overlay").style.display = "none";
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.classList.toggle('show');
}