function Mostra(){
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.classList.toggle('show'); 
}

function Annulla(){
    var hiddenDiv = document.getElementById('hiddenDiv');
    hiddenDiv.classList.toggle('show');
    
}
function Elimina(numTarga){
    alert(numTarga);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'elimina.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('result').textContent = xhr.responseText;
            setTimeout(function () {
                var hiddenDiv = document.getElementById('hiddenDiv');
                hiddenDiv.classList.add('hide');
                setTimeout(function () {
                    hiddenDiv.classList.remove('show', 'hide');
                }, 500);
            }, 2000);
        }
    };
    xhr.send('data=' +encodeURIComponent(numTarga));// invia i dati necessari al file PHP
}
document.getElementById('bottoneElimina').addEventListener('click', function () {
   
});