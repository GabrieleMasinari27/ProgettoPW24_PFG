<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <link rel="stylesheet" href="../Css/main_page.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/0b3c862c21.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="../js/rinominaheader.js"></script>
<script type="text/javascript" src="../js/jquery-2.0.0.js"></script>
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
      <div id="nota">
      <p>NOTA: le operazioni di inserimento, modifica e rimozione dei dati sono disponibili soltanto sulla tabella Targa, come da consegna</p>
      </div>
    </div>
    <div class="risultato">
      <div>
        <p id="intro">Benvenuto a PFG Motorizzazione!</p>
        <p>Questa pagina web permette di visualizzare una collezione di dati relativi a targhe, siano esse ancora attive o gia' restituite; <br></p>
        <p>Ogni targa ha un collegamento a un veicolo e a un numero variabile revisioni, che possono essere consultate nelle apposite pagine. <br></p>
        <p>Per iniziare la navigazione selezionare uno dei collegamenti sulla tabella di navigazione (sulla sinistra).</p>
      </div>
      <div class="immagini">
        <img src="./../img/auto1.png" alt="macchinaBiancaNonCaricata"></img>
      </div>
    </div>
</div>
</body>
</html>
