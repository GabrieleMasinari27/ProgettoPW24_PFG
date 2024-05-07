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

	function queryRevisione($numRevione, $numTarga, $dataRE, $posneg, $valoreordinamento) : string {
    $qry = "SELECT REVISIONE.numero AS numRevisione, REVISIONE.dataRev AS dataRevisione, REVISIONE.targa AS numTarga, REVISIONE.esito AS esito, REVISIONE.motivazione AS motivazione " .
           "FROM REVISIONE " .
           "WHERE 1=1 ";

    if ($numTarga != "")
        $qry .= "AND REVISIONE.targa LIKE '%" . $numTarga . "%' ";

    if ($numRevione != "")
        $qry .= "AND REVISIONE.numero LIKE '%" . $numRevione . "%' ";

    if ($dataRE != "")
        $qry .= "AND REVISIONE.dataRev LIKE '%" . $dataRE . "%' ";

    if ($posneg != "indifferente")
        $qry .= "AND REVISIONE.esito LIKE '%" . $posneg . "%' ";

    switch ($valoreordinamento) {
        case 'ordinamentoNullo':

            break;
        case 'ordinaNumeroRev':

            $qry .= " ORDER BY REVISIONE.numero";
            break;
        case 'ordinaNumeroTarga':

            $qry .= " ORDER BY REVISIONE.targa";
            break;
        case 'ordinaPositivo':

            $qry .= " ORDER BY REVISIONE.esito DESC";
            break;
        case 'ordinaNegativo':

            $qry .= " ORDER BY REVISIONE.esito ASC";
            break;
    }

    return $qry;
	}
?>
