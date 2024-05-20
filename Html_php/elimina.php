<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numTarga = $_POST['targa'];

    if (!empty($numTarga)) {
        try {
            $stmt = $conn->prepare("DELETE FROM TARGA WHERE numero = :numTarga");
            $stmt->bindParam(':numTarga', $numTarga);
            $stmt->execute();
            echo "success";
        } catch (PDOException $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    } else {
        http_response_code(400);
        echo "Invalid request.";
    }
}
?>