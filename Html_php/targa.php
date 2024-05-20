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
<body onload="setTarga()">
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
            <legend>Filtro Ricerca per: </legend>
          <input type="search" name="numerotarga"  placeholder=" Targa"><i class="fa fa-automobile"></i><br><br>
          Data di emissione:<br>
          <input type="date" name="dataemtarga"><br><br>
          <input type="radio" name="radiofiltrotarga" value="targheatt">Targhe attive<br>
          <input type="radio" name="radiofiltrotarga" value="targherest">Targhe restituite <br>
          <input type="radio" name="radiofiltrotarga" value="targhetutte" checked>Tutte le targhe <br><br>
          <label for="scelta">Ordina per:</label>
          <select id="ordinamento" name="scelta">
            <option value="ordinamentoNullo"selected>Nessun ordinamento</option>
            <option value="ordinaDataEm">Data di Emissione</option>
            <option value="ordinaNumeroTarga">Numero di targa</option>
          </select>
           <br><br>
          <input type="submit" name="bottonericerca" value="Cerca">&nbsp&nbsp<i class="fa fa-search"></i>
          </fieldset>
        </form>
      </div>
    </div>
    <div class="risultato">
      <i id="icona_aggiungi"class="fa fa-plus-square-o"></i>
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
          
          <th>Targa</th>
          <th>Data Emissione</th>
          <th>Stato della targa</th>
          <th># Revisioni Effettuate</th>
          <th>Telaio Veicolo</th>
          <th>Modifica</th>
          <th>Elimina</th>
        </tr>

        <?php
        
        foreach($result as $riga) {
        
          $numTarga = $riga["numTarga"];
          $dataEM = $riga["dataEM"];
          $stato = $riga["stato"]; //presente in query.php
          $count_revisioni=$riga["count_revisioni"];
          $telaio_res_veicolo=$riga["telaio_res_associato"];
          $telaio_att_veicolo=$riga["telaio_att_associato"];
          ?>

          <tr>
            
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
            <td id="icona_modifica"> <a onclick=""><i class="fa fa-pencil"></i></a> </td>
            <td id="icona_elimina">  <a onclick="Mostra()"href="#"><i class="fa fa-trash"></i></a> </td>
          </tr>
    <div id="hiddenDiv">
        <p>Sei sicuro di voler eliminare questa riga?</p>
        <p><?php  $numTarga; ?></p>
        <button onclick="Annulla()" id="bottoneAnnulla">Annulla</button>
        <button onclick="Elimina(<?php $numTarga ?>)" id="bottoneElimina">Elimina</button>
    </div>
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