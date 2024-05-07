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

	function queryVeicolo($numTelaio, $marca, $modello, $dataPro, $valoreordinamento) : string {
    $qry = "SELECT VEICOLO.telaio AS telaio, VEICOLO.marca AS marca, VEICOLO.modello AS modello, VEICOLO.dataProd AS data " .
           "FROM VEICOLO " .
           "WHERE 1=1 ";

    if ($numTelaio != "")
        $qry .= "AND VEICOLO.telaio LIKE '%" . $numTelaio . "%' ";

    if ($marca != "")
        $qry .= "AND VEICOLO.marca LIKE '%" . $marca . "%' ";

    if ($modello != "")
        $qry .= "AND VEICOLO.modello LIKE '%" . $modello . "%' ";

    if ($dataPro != "")
        $qry .= "AND VEICOLO.dataProd LIKE '%" . $dataPro . "%' ";

    switch ($valoreordinamento) {
        case 'ordinamentoNullo':

            break;
        case 'ordinaNumeroTel':

            $qry .= " ORDER BY VEICOLO.telaio";
            break;
        case 'ordinaMarca':

            $qry .= " ORDER BY VEICOLO.marca";
            break;
        case 'ordinaModello':

            $qry .= " ORDER BY VEICOLO.modello";
            break;
        case 'ordinaData':

            $qry .= " ORDER BY VEICOLO.dataProd";
            break;
    }

    return $qry;
	}
?>
