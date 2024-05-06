<?php
	function getTarga($numTarga,$dataEM,$radiocheck) : string {
		$qry = "SELECT 	TARGA.numero AS numTarga, TARGA.dataEM AS dataEM " .
							"FROM TARGA " .
							"WHERE 1=1 ";
		if ($numTarga != "")
			$qry = $qry . "AND TARGA.numero LIKE '%" . $numTarga . "%' ";

		if ($dataEM != "")
			$qry = $qry . "AND TARGA.dataEM  LIKE '%" . $datEM . "%' ";
		return $qry;
	}
?>
