<?php
	function getTarga($numTarga,$dataEM,$radiocheck) : string {
		$qry = "SELECT 	TARGA.numero AS numTarga, TARGA.dataEM AS dataEM " .
							"FROM TARGA " .
							"WHERE 1=1 ";
		if ($numTarga != "")
			$qry = $qry . "AND numTarga LIKE '%" . $numTarga . "%' ";

		if ($dataEM != "")
			$qry = $qry . "AND dataEM  LIKE '%" . $datEM . "%' ";
		return $qry;
	}
?>
