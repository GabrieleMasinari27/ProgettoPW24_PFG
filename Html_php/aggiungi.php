<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <link rel="stylesheet" href="../Css/main_page.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta charset="utf-8">
  <title>ProgettoPFG_Motorizzazione</title>
  <script src="https://kit.fontawesome.com/0b3c862c21.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="../js/rinominaheader.js"></script>
  <script type="text/javascript" src="../js/crud.js"></script>
  <script type="text/javascript" src="../js/jquery-2.0.0.js"></script>
</head>
<body onload="setTargaAggiungi()">
  <?php
  include "header.html";
  include "footer.html";
  include "query.php";
  include "connect.php";
    $NumTarga = "";
    $dataEM = "";
    if (count($_POST) > 0) {
        $NumTarga = $_POST["NumTarga"];
    	$dataEM = $_POST["dataEM"];
    	$radio = $_POST["radiotarga"];
      //$radio="<script> var radio=document.getElementsByName('radiotarga')</script>";
    	$telaio = $_POST["telaio"];
    	$datarest = $_POST["datares"];
        if (empty($radio)) {
          echo("<script> const input = document.getElementById('datarest');
                         input.disabled = true; </script>");
    		
		}
        else{
          if (verificaVeicolo($telaio, $conn)) {
              $query = Inserimento($NumTarga, $dataEM,$radio,$telaio,$datarest,$conn);
              try {
                  $result = $conn->query($query);
              } catch (PDOException $e) {
                  echo "<h3>DB Error on Query: " . $e->getMessage() . "</h3>";
                  $error = true;
              }
              if (!$error) {
                  echo ("<script>alert('Inserimento andato a buon fine')</script>");
                  header('Location: ' . "targa.php");
              } else {
                  echo ("<script>alert('L'inserimento non è andato a buon fine')</script>");
              }
          }
          else{
          		echo ("<h3>Il numero del telaio non è presente nel database!<h3>");
          }
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

</ul>
</nav>
      </div>
      <div class="filtro">

      </div>
    </div>
    <div class="risultato">
     <form name="form_ricerca" method="post">

          Aggiungi una nuova Targa: <br>
          <input type="search" name="NumTarga"  placeholder=" Targa" required><i class="fa fa-automobile"></i><br><br>

          Aggiungi data di emissione:<br>
          <input type="date" name="dataEM"required><br><br>

          Seleziona il tipo di targa:<br>
          <input type="radio" name="radiotarga" value="targheatt"id="radioatt" required>Targa attiva<br>
          <input type="radio" name="radiotarga" value="targherest"id="radiorest" required>Targhe restituita <br><br>

          Seleziona il numero di telaio del veicolo a cui associare la targa:<br>
          <input type="search" name="telaio" placeholder="Telaio veicolo associato" required><br><br>

          Aggiungi l'eventuale data di restituzione:<br>
          <input type="date" name="datares" id="datarest"><br><br>

          <button class="btn"><i class="fa fa-plus-square-o"></i> Aggiungi</button>

        </form>

    </div>
</div>
</body>
</html>
