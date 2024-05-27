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
<body onload="setTargaModifica()">
  <?php
  include "header.html";
  include "footer.html";
  include "query.php";
  include 'connect.php';
  include 'mysqli_connect.php';
  $radio = isset($_POST["radiotarga"]) ? $_POST["radiotarga"] : '';
  $disabled = ($radio === 'targherest') ? ' ' : 'disabled';
  $numTarga = $_GET['numTarga'];
  $OLDdataEM= $_GET['dataEM'];
  $OLDstato= $_GET['stato'];
  // Fetch record from database
  if($OLDstato=="Restituita"){
    $OLDtelaio = $_GET['telaioRes'];
    //$OLDdataRes =$conn->query("SELECT dataRes FROM TARGA_RESTITUITA WHERE targa = '$numTarga'");
    $OLDdataRes = mysqli_query($conn_sqli, "SELECT dataRes FROM TARGA_RESTITUITA WHERE targa = '$numTarga'");
    $row = mysqli_fetch_assoc($OLDdataRes);
  }
  else{
    $OLDtelaio = $_GET['telaioAtt'];
  }  



  ?>
  <div class="container">
    <div class="ricercasx">
      <div class="nav">
       <nav>
  <ul>
<li><a class="active" href="index.php"><i class="fa fa-home"></i></a></li>

</ul>
</nav>
      </div>
      <div class="filtro">

      </div>
    </div>
    <div class="risultato">
     <form  action="modifica.php"name="form_ricerca" method="post">

           Targa selezionata: <br>
          <input type="search" name="NumTarga" value="<?= $numTarga ?>" placeholder=" Targa"placeholder=" Targa" pattern="[A-Za-z0-9]+" title="Inserisci solo lettere e numeri" maxlength="7"minlength="7" oninput="convertToUpperCase(this)" readonly><i class="fa fa-automobile"></i><br><br>

          Modifica data di emissione:<br>
          <input type="date"value="<?= $OLDdataEM ?>" name="dataEM"required><br><br>

          Modifica il tipo di targa:<br>
          <input type="radio" name="radiotarga" value="targheatt"id="radioatt" required <?php echo ($OLDstato === 'Attiva') ? 'checked' : ''; ?>>Targa attiva<br>
          <input type="radio" name="radiotarga" value="targherest"id="radiorest" required <?php echo ($OLDstato === 'Restituita') ? 'checked' : ''; ?>>Targhe restituita <br><br>

          Modifica il numero di telaio del veicolo a cui associare la targa(il numero del telaio deve essere già presente nella tabella Veicolo):<br>
          <input type="number" name="telaio" value="<?= $OLDtelaio ?>"placeholder="Telaio veicolo associato"min="100000"max="1000000" required><br><br>

          Aggiungi/Modifica l'eventuale data di restituzione:<br>
          <input type="date" name="datares"value="<?= $row['dataRes']?>" id="datarest"<?php echo $disabled; ?>><br><br>

          <button class="btn"><i class="fa fa-pencil"></i> Modifica</button>
    
        </form>
        <?php
// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $numTarga = $_POST['NumTarga'];
  $dataEM = $_POST['dataEM'];
  $statoTarga=$_POST['radiotarga'];
  $telaio=$_POST['telaio'];
  $dataRES=$_POST['datares'];

  if (verificaVeicolo($telaio, $conn)) { //verifichiamo che il veicolo esista
    //se la targa è attiva, verifico che non sia già presente
    if ($statoTarga == 'targheatt' && $OLDstato=="Restituita" && verificaTargaAttiva($telaio, $conn)) {
      echo("<script> alert('Esiste già una targa attiva per questo veicolo.') </script>");
      }
    else if($statoTarga == 'targherest' && $datarest < $dataEM){ 
      //se è restituita, verifico che la data di restituzione non preceda quella di emissione
      echo("<script> alert('La data di restituzione non può essere più vecchia della data di inserimento.') </script>");
    }
    else{ //se tutto va bene posso lanciare la query
      $query = modifica($numTarga,$dataEM,$OLDstato,$statoTarga,$telaio,$dataRES);
      try {
        $result = $conn->query($query);
        //echo("<script> alert('Inserimento eseguito con successo.') </script>");
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
?>
    </div>
</div>
</body>
</html>
