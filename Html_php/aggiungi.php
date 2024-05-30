<!DOCTYPE html>
<html lang="it" dir="ltr">
<head>
  <link rel="stylesheet" href="../Css/main_page.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta charset="utf-8">
  <title>ProgettoPFG_Motorizzazione</title>
  <script src="https://kit.fontawesome.com/0b3c862c21.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="../js/rinominaheader.js"></script>
  <script type="text/javascript" src="../js/crud.js"></script>
  <script type="text/javascript" src="../js/jquery-2.0.0.js"></script>
   <script type="text/javascript" src="../js/controlli.js"></script>
  
</head>
<body onload="setTargaAggiungi()">
  <?php
  include "header.html";
  include "footer.html";
  include "query.php";
  include "connect.php";
    $NumTarga = "";
    $dataEM = "";
    $radio = isset($_POST["radiotarga"]) ? $_POST["radiotarga"] : '';
    $disabled = ($radio === 'targherest') ? ' ' : 'disabled';
  
    if (count($_POST) > 0) {
      $NumTarga =$_POST["NumTarga"];
    	$dataEM = $_POST["dataEM"];
    	$radio = $_POST["radiotarga"];
      $telaio = $_POST["telaio"];
    	$datarest = $_POST["datares"];
     
      if (verificaVeicolo($telaio, $conn)) {
        //se la targa è attiva, verifico che non sia già presente
        if ($radio == 'targheatt' && verificaTargaAttiva($telaio, $conn)) {
          echo("<script> alert('Esiste già una targa attiva per questo veicolo.') </script>");
          }
        else if($radio == 'targherest' && $datarest < $dataEM){ 
          //se è restituita, verifico che la data di restituzione non preceda quella di emissione
          echo("<script> alert('La data di restituzione non può essere più vecchia della data di inserimento.') </script>");
        }
        else{ //se tutto va bene posso lanciare la query
          $query = Inserimento($NumTarga, $dataEM,$radio,$telaio,$datarest);
          $error=false; //istanziamo error per poi poter stampare il messaggio di corretto inserimento o meno
          try {
            $result = $conn->query($query);
            echo("<script> alert('Inserimento eseguito con successo.') </script>");
          } catch (PDOException $e) { //se qualcosa va comunque storto, lo comunichiamo
                echo "<h3>DB Error on Query: " . $e->getMessage() . "</h3>";
                echo ("<script>alert('Inserimento non eseguito.')</script>");
          }
      
        }
        header('Location: ' . "targa.php");
        }
        else{
          		echo ("<h3>Il numero del telaio non è presente nel database!</h3>");
        }
    }
    
        $query = "SELECT DISTINCT numero FROM TARGA";
    try {
        $result = $conn->query($query);
    } catch (PDOException $e) {
        echo "<h3 class='msg'>DB Error on Query: " . $e->getMessage() . "</h3>";
        $error = true;
    }
    ?>

  <div class="container">
    <div class="ricercasx">
      <div class="nav">
       <nav>
  <ul>
<li><a class="active" href="index.php"><i class="fa fa-home"></i></a></li>
<li><a href="targa.php">Targa <i class="fa fa-drivers-license-o"></i></a></li>
</ul>
</nav>
      </div>
      <div class="filtro">

      </div>
    </div>
    <div class="risultato">
     <form name="form_ricerca" method="post">

          Aggiungi una nuova Targa: <br>
          <input type="search" name="NumTarga"  placeholder=" Targa"placeholder=" Targa" pattern="[A-Za-z0-9]+" title="Inserisci solo lettere e numeri" maxlength="7"minlength="7" oninput="convertToUpperCase(this)" required><i class="fa fa-automobile"></i><br><br>

          Aggiungi data di emissione:<br>
          <input type="date" name="dataEM"required><br><br>

          Seleziona il tipo di targa:<br>
          <input type="radio" name="radiotarga" value="targheatt"id="radioatt" required>Targa attiva<br>
          <input type="radio" name="radiotarga" value="targherest"id="radiorest" required  >Targhe restituita <br><br>

          Seleziona il numero di telaio del veicolo a cui associare la targa(il numero del telaio deve essere già presente nella tabella Veicolo):<br>
          <input type="number" name="telaio" placeholder="Telaio veicolo associato"min="100000"max="1000000" required><br><br>

          Aggiungi l'eventuale data di restituzione:<br>
          <input type="date" name="datares" id="datarest"<?php echo $disabled; ?>><br><br>

          <button class="btn"><i class="fa fa-plus-square-o"></i> Aggiungi</button>

        </form>
    </div>
</div>
</body>
</html>
