<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <link rel="stylesheet" href="../Css/main_page.css">
  <meta charset="utf-8">
  <title>ProgettoPFG_Motorizzazione</title>
</head>
<body onload="setRevisione()">

  <?php
  include "header.html";
  include "footer.html";
  include "Query.php";
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
          data della revisione:
          <input type="date" name="datarevisione" placeholder="Data della revisione"><br><br>
          esito:<br>
          <input type="radio" name="esito" value="positivo"> Positivo <br>
          <input type="radio" name="esito" value="negativo"> Negativo <br>
          <input type="radio" name="esito" value="indifferente" checked> indifferente <br><br>
          <label for="scelta">Ordina per:</label>
          <select id="ordinamento" name="scelta">
            <option value="ordinamentoNullo">Nessun ordinamento</option>
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
      $numRevione= "";
      $numTarga= "";
      $datRE = "";
      $posneg="";
      $valoreordinamento="";
      if(count($_POST)>0 ){
        $numRevione= $_POST["numerorevisione"];
        $numTarga = $_POST["numerotarga"];
        $dataRE = $_POST["datarevisione"];
        $posneg=$_POST["esito"];
        $valoreordinamento=$_POST["scelta"];
      }
      if(count($_GET)>0 ){
        $numRevione= $_POST["numerorevisione"];
        $numTarga = $_POST["numerotarga"];
        $dataRE = $_POST["datarevisione"];
        $posneg=$_POST["esito"];
        $valoreordinamento=$_POST["scelta"];
      }
      $query = queryRevisione($numRevione, $numTarga, $dataRE, $posneg, $valoreordinamento);
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
        <th>#Revisione</th>
        <th>Targa</th>
        <th>Data Emissione</th>
        <th>Esito</th>
        <th>Motivazione</th>
      </tr>
      <?php
      foreach($result as $riga) {
        $numRevione = $riga["numRevisione"];
        $numTarga = $riga["numTarga"];
        $datRE = $riga["dataRevisione"];
        $esito = $riga["esito"];
        $motivazione = $riga["motivazione"];
        // Determina la classe CSS in base all'esito
        $bg_class = ($esito == 'positivo') ? 'bg-green' : 'bg-red';
      ?>
      <tr class="<?php echo $bg_class; ?>">
        <td><?php echo $numRevione; ?></td>
        <td><?php echo $numTarga; ?></td>
        <td><?php echo $datRE; ?></td>
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
