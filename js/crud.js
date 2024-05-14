function Cancella(){
    
    var richiesta=window.confirm("Vuoi davvero cancellare questa riga?");
    if(richiesta){
        alert("operazione eseguita con successo");
        return richiesta;
    }
    alert("operazione annullata con successo.");
}