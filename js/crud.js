function Mostra(numTarga){
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.classList.toggle('show'); 
    document.getElementById("numeroTarga").innerText = numTarga;
    document.getElementById("bottoneElimina").onclick = function() {
        Elimina(numTarga);
    };
}

function Annulla(){
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.classList.toggle('show');
    
}
function Elimina(numTarga){
    $.ajax({
        url: 'elimina.php',
        type: 'POST',
        data: { targa: numTarga },
        success: function(response) {
            alert('Targa eliminata con successo!');
            location.reload();
        },
        error: function(xhr, status, error) {
            alert('Errore durante l\'eliminazione della targa.');
        }
    });
}
