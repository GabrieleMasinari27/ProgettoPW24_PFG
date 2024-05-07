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
          <input type="text" name="numerotarga"  placeholder=" Targa">
          <input type="date" name="dataemtarga"><br>
          <input type="radio" name="radiofiltrotarga" value="targheatt">Targhe attive<br>
          <input type="radio" name="radiofiltrotarga" value="targherest">Targhe restituite <br>
          <input type="radio" name="radiofiltrotarga" value="targhetutte" checked> Tutte le targhe <br>
          <input type="submit" name="bottonericerca" value="Cerca">

        </form>
      </div>
    </div>
    <div class="risultato">
      <?php
      $numTarga= "";
      $datEM = "";
      $radiocheck="";
      if(count($_POST)>0 ){
        $numTarga = $_POST["numerotarga"];
        $dataEM = $_POST["dataemtarga"];
        $radiocheck=$_POST["radiofiltrotarga"];
      }
      if(count($_GET)>0 ){
        $numTarga = $_POST["numerotarga"];
        $dataEM = $_POST["dataemtarga"];
        $radiocheck=$_POST["radiofiltrotarga"];
      }
      $query = getTarga($numTarga, $dataEM,$radiocheck);
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
          <th># </th>
          <th>Targa</th>
          <th>Data Emissione</th>
          <th>Stato della targa</th>
        </tr>
        <?php
        $i=0;
        foreach($result as $riga) {
          $i=$i+1;
          $numTarga = $riga["numTarga"];
          $datEM = $riga["dataEM"];
          $stato = $riga["stato"];
          ?>
          <tr>
            <td > <?php echo $i; ?> </td>
            <td > <?php echo $numTarga; ?> </td>
            <td > <?php echo $datEM; ?> </td>
            <td > <?php echo $stato; ?> </td>
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
