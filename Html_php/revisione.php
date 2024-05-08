<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <link rel="stylesheet" href="../Css/main_page.css">
  <script type="text/javascript" src="../js/rinominaheader.js"></script>
  <script type="text/javascript" src="../js/jquery-2.0.0.js"></script>
  <meta charset="utf-8">
  <title>ProgettoPFG_Motorizzazione</title>
</head>
<body onload="setRevisione()">

  <?php
  include "header.html";
  include "footer.html";
  include "query.php";
  ?>
  <div class="container">
    <div class="ricercasx">
      <div class="nav">
        <?php
        include "nav.html";
        ?>
      </div>
      <div class="filtro">
        <form name="form_ricerca" method="post">
          <input type="number" name="numerorevisione" placeholder="Numero di revisione"><br>
          <input type="text" name="numerotarga"  placeholder="Targa"><br><br>
          Data della revisione:
          <input type="date" name="datarevisione" placeholder="Data della revisione"><br><br>
          Esito:<br>
          <input type="radio" name="esito" value="positivo"> Positivo <br>
          <input type="radio" name="esito" value="negativo"> Negativo <br>
          <input type="radio" name="esito" value="indifferente" checked> Indifferente <br><br>
          <label for="scelta">Ordina per:</label>
          <select id="ordinamento" name="scelta">
            <option value="ordinamentoNullo"selected>Nessun ordinamento</option>
            <option value="ordinaNumeroRev">Numero di revisione</option>
            <option value="ordinaNumeroTarga">Numero di targa</option>
            <option value="ordinaPositivo">Prima i positivi</option>
            <option value="ordinaNegativo">Prima i negativi</option>
          </select>
          <input type="submit" name="bottonericerca" value="Cerca">

        </form>
      </div>
    </div>
    <div class="risultato">
      <?php
      $numRevisione= "";
      $numTarga= "";
      $dataRE = "";
      $posneg="";
      $valoreordinamento="";
      if(count($_POST)>0 ){
        $numRevisione= $_POST["numerorevisione"];
        $numTarga = $_POST["numerotarga"];
        $dataRE = $_POST["datarevisione"];
        $posneg=$_POST["esito"];
        $valoreordinamento=$_POST["scelta"];
      }

      $query = queryRevisione($numRevisione, $numTarga, $dataRE, $posneg, $valoreordinamento);
      echo "<p>Query della Targa: " . $query . "</p>";

      include 'connect.php';
      try {
        $result = $conn->query($query);
      } catch(PDOException$e) {
        echo "<p>DB Error on Query: " . $e->getMessage() . "</p>";
        $error = true;
      }
      	if(!$error) {
      ?>
      <table class="table">
      <tr class="header">
        <th>#</th>
        <th>IdRevisione</th>
        <th>Targa</th>
        <th>Data Emissione</th>
        <th>Esito</th>
        <th>Motivazione</th>
      </tr>
      <?php
      $i=0;
      foreach($result as $riga) {
        $i =$i+1;
        $numRevisione = $riga["numRevisione"];
        $numTarga = $riga["numTarga"];
        $dataRE = $riga["dataRevisione"];
        $esito = $riga["esito"];
        $motivazione = $riga["motivazione"];
        // Determina la classe CSS in base all'esito
        $bg_class = ($esito == 'positivo') ? 'bg-green' : 'bg-red';
      ?>
      <tr class="<?php echo $bg_class; ?>">
        <td><?php echo $i; ?></td>
        <td><?php echo $numRevisione; ?></td>
        <td><?php echo $numTarga; ?></td>
        <td><?php echo $dataRE; ?></td>
        <td><?php echo $esito; ?></td>
        <td><?php echo $motivazione; ?></td>
      </tr>
      <?php
      }
      ?>
    </table>
      <?php
        }
       ?>
  </div>
</div>
</body>
</html>
