<?php
	$servername= "localhost";
	$username = "root";
	$dbname= "progettopw";
	$password = null;
	$error = false;

	try {
		$conn = new PDO("mysql:host=".$servername.";dbname=".$dbname,
											$username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE,
												PDO::ERRMODE_EXCEPTION);
		//$result = $conn->query("SELECT * FROM Artist");
	} catch(PDOException$e) {
		echo "DB Error: " . $e->getMessage();
		$error = true;
	}
?>
