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
  include "connect.php";
   $radio = isset($_POST["radiotarga"]) ? $_POST["radiotarga"] : '';
    $disabled = ($radio === 'targherest') ? ' ' : 'disabled';
  
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
     <form name="form_ricerca" method="post">

           Targa selezionata: <br>
          <input type="search" name="NumTarga"  placeholder=" Targa"placeholder=" Targa" pattern="[A-Za-z0-9]+" title="Inserisci solo lettere e numeri" maxlength="7"minlength="7" oninput="convertToUpperCase(this)" required><i class="fa fa-automobile"></i><br><br>

          Modifica data di emissione:<br>
          <input type="date" name="dataEM"required><br><br>

          Modifica il tipo di targa:<br>
          <input type="radio" name="radiotarga" value="targheatt"id="radioatt" required>Targa attiva<br>
          <input type="radio" name="radiotarga" value="targherest"id="radiorest" required  >Targhe restituita <br><br>

          Modifica il numero di telaio del veicolo a cui associare la targa(il numero del telaio deve essere già presente nella tabella Veicolo):<br>
          <input type="number" name="telaio" placeholder="Telaio veicolo associato"min="100000"max="1000000" required><br><br>

          Aggiungi/Modifica l'eventuale data di restituzione:<br>
          <input type="date" name="datares" id="datarest"<?php echo $disabled; ?>><br><br>

          <button class="btn"><i class="fa fa-pencil"></i> Modifica</button>

        </form>
    </div>
</div>
</body>
</html>
