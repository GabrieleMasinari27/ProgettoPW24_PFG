<?php
// Connessione al database
include 'connect.php';
  $data = $_POST['data'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   try {
    // Preparare la query di eliminazione
    $stmt = $conn->prepare("DELETE FROM TARGA WHERE numero = :id");
    $stmt->bindParam(':id', $data);

    if ($stmt->execute()) {
        echo 'Eliminazione completata con successo';
    } else {
        echo 'Eliminazione non riuscita ';
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);

} 
} 

?>