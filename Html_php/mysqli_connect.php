<?php

$db ="progettopw";
$db_host = "localhost";
$db_user = "root";
$db_password = "";

$conn_sqli = new mysqli($db_host, $db_user, $db_password, $db);
if ($conn_sqli->connect_error) {
    die("Si è verificato il seguente problema tecnico: " . $conn_sqli->connect_error);
} 

?>