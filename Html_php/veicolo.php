<!DOCTYPE html>
<html lang="it" dir="ltr">
<head>
  <link rel="stylesheet" href="../Css/main_page.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/0b3c862c21.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="../js/rinominaheader.js"></script>
  <script type="text/javascript" src="../js/jquery-2.0.0.js"></script>
  <meta charset="utf-8">
  <title>ProgettoPFG_Motorizzazione</title>
</head>
<body onload="setVeicolo()">
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
          <fieldset>
          <legend>Filtro Ricerca per:</legend>
          <input type="number" name="telaio" placeholder="Telaio"min="100000"max="1000000" ><br>
          <input type="text" name="marca"  placeholder="Marca"  pattern="[A-Za-z0-9-]+" title="Inserisci solo lettere e numeri"maxlength="15"><br>
          <input type="text" name="modello"  placeholder="Modello" pattern="[A-Za-z0-9]+" title="Inserisci solo lettere e numeri" maxlength="15"><br><br>
          Data della produzione:
          <input type="date" name="dataproduzione" placeholder="Data della produzione"><br><br>
          <label for="scelta">Ordina per:</label>
          <select id="ordinamento" name="scelta">
            <option value="ordinamentoNullo">Nessun ordinamento</option>
            <option value="ordinaNumeroTel">Numero telaio</option>
            <option value="ordinaMarca">Alfabetico per marca</option>
            <option value="ordinaModello">Alfabetico perModello</option>
            <option value="ordinaData">Data Produzione</option>
          </select>
           <br>
          <input type="submit" name="bottonericerca" value="Cerca">&nbsp&nbsp<i class="fa fa-search"></i><br>
          <input type="reset" name="bottonericerca" value="Resetta">
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
      $query = queryVeicolo($numTelaio, $marca, $modello, $dataPro, $valoreordinamento);
      
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
        <!-- IMPLEMENTARE IL NUMERO DI TARGHE restituite
        E AGGIUNGERE ID DI TARGA ATTIVA-->
      <tr class="header">
        
        <th>#Telaio</th>
        <th>Marca</th>
        <th>Modello</th>
        <th>Data di produzione</th>
        <th>#Targhe Restituite</th>
        <th>IdTargaAttiva</th>
      </tr>
      <?php
      
      foreach($result as $riga) {
        
        $numTelaio = $riga["telaio"];
        $marca = $riga["marca"];
        $modello = $riga["modello"];
        $dataPro = $riga["data"];
        $num_res= $riga["num_restituite"];
        $targa_att=$riga["targa_attiva"];
      ?>
      <tr>
        <td><?php echo $numTelaio; ?></td>
        <td><?php echo $marca; ?></td>
        <td><?php echo $modello; ?></td>
        <td><?php echo $dataPro; ?></td>
        <td><?php echo $num_res; ?></td>
        <td><?php echo $targa_att; ?></td>
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
