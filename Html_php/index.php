<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <link rel="stylesheet" href="../Css/main_page.css">
  <meta charset="utf-8">
  <title>ProgettoPFG_Motorizzazione</title>
</head>
<body>
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
          <input type="text" name="numerotarga"  placeholder=" Targa">
          Data di emissione:
          <input type="date" name="dataemtarga"><br>
          <input type="radio" name="radiofiltrotarga" value="targheatt">Targhe attive<br>
          <input type="radio" name="radiofiltrotarga" value="targherest">Targhe restituite <br>
          <input type="radio" name="radiofiltrotarga" value="targhetutte" checked>Tutte le targhe <br>
          <label for="scelta">Ordina per:</label>
          <select id="ordinamento" name="scelta">
            <option value="ordinamentoNullo"selected>Nessun ordinamento</option>
            <option value="ordinaDataEm">Data di Emissione</option>
            <option value="ordinaNumeroTarga">Numero di targa</option>
          <input type="submit" name="bottonericerca" value="Cerca">

        </form>
      </div>
    </div>
    <div class="risultato">

      <?php
      $numTarga= "";
      $dataEM = "";
      $radiocheck="";
      $valoreordinamento="";
      $count_revisioni=0;
      if(count($_POST)>0 ){
        $numTarga = $_POST["numerotarga"];
        $dataEM = $_POST["dataemtarga"];
        $radiocheck=$_POST["radiofiltrotarga"];
        $valoreordinamento=$_POST["scelta"];
      }
      $query = getTarga($numTarga, $dataEM,$radiocheck,$valoreordinamento);
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
<!-- IMPLEMENTARE IL NUMERI DI REVISONI EFFETTUATE PER OGNI TARGA
 e il telaio del veicolo-->
      <table class="table">
        <tr class="header">
          <th># </th>
          <th>Targa</th>
          <th>Data Emissione</th>
          <th>Stato della targa</th>
          <th># Revisioni Effettuate</th>
          <th>Telaio Veicolo</th>
        </tr>

        <?php
        $i=0;
        foreach($result as $riga) {
          $i=$i+1;
          $numTarga = $riga["numTarga"];
          $dataEM = $riga["dataEM"];
          $stato = $riga["stato"]; //presente in query.php
          $count_revisioni=$riga["count_revisioni"];
          $telaio_res_veicolo=$riga["telaio_res_associato"];
          $telaio_att_veicolo=$riga["telaio_att_associato"];
          ?>

          <tr>
            <td > <?php echo $i; ?> </td>
            <td > <?php echo $numTarga; ?> </td>
            <td > <?php echo $dataEM; ?> </td>
            <td > <?php echo $stato; ?> </td>
            <td > <?php echo $count_revisioni; ?> </td>
            <?php
              if($stato=="Attiva"){
              ?>
              <td > <?php echo $telaio_att_veicolo; ?> </td>

            <?php
              }
              else{
              ?>
              <td > <?php echo $telaio_res_veicolo; ?> </td>
              <?php
              }
              ?>
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
