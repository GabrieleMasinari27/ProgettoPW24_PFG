<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <link rel="stylesheet" href="../Css/main_page.css">
  <meta charset="utf-8">
  <title>ProgettoPFG_Motorizzazione</title>
</head>
<body>

  <!--
  <footer>footer</footer>-->
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
          <input type="number" name="telaio" placeholder="Telaio"><br>
          <input type="text" name="marca"  placeholder="Marca"><br>
          <input type="text" name="modello"  placeholder="Modello"><br><br>
          data della produzione:
          <input type="date" name="dataproduzione" placeholder="Data della produzione"><br><br>
          <label for="scelta">Ordina per:</label>
          <select id="ordinamento" name="scelta">
            <option value="ordinamentoNullo">Nessun ordinamento</option>
            <option value="ordinaNumeroTel">Numero telaio</option>
            <option value="ordinaMarca">Alfabetico per marca</option>
            <option value="ordinaModello">Alfabetico perModello</option>
            <option value="ordinaData">Data Produzione</option>
          </select>
          <input type="submit" name="bottonericerca" value="Cerca">

        </form>
      </div>
    </div>
    <div class="risultato">
      <?php
      $numTelaio= "";
      $marca= "";
      $modello = "";
      $dataPro="";
      $valoreordinamento="";
      if(count($_POST)>0 ){
        $numTelaio= $_POST["telaio"];
        $marca = $_POST["marca"];
        $modello = $_POST["modello"];
        $dataPro=$_POST["dataproduzione"];
        $valoreordinamento=$_POST["scelta"];
      }
      if(count($_GET)>0 ){
        $numTelaio= $_POST["telaio"];
        $marca = $_POST["marca"];
        $modello = $_POST["modello"];
        $dataPro=$_POST["dataproduzione"];
        $valoreordinamento=$_POST["scelta"];
      }
      $query = queryVeicolo($numTelaio, $marca, $modello, $dataPro, $valoreordinamento);
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
        <th>#Telaio</th>
        <th>Marca</th>
        <th>Modello</th>
        <th>Data di produzione</th>
      </tr>
      <?php
      foreach($result as $riga) {
        $numTelaio = $riga["telaio"];
        $marca = $riga["marca"];
        $modello = $riga["modello"];
        $dataPro = $riga["data"];
      ?>
      <tr>
        <td><?php echo $numTelaio; ?></td>
        <td><?php echo $marca; ?></td>
        <td><?php echo $modello; ?></td>
        <td><?php echo $dataPro; ?></td>
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
