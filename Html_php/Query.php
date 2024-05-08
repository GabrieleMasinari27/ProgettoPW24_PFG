<?php
	function getTarga($numTarga, $dataEM, $radiocheck): string {
    $qry = "SELECT
                TARGA.numero AS numTarga,
                TARGA.dataEM AS dataEM,
						CASE
								WHEN TARGA.numero IN (SELECT TARGA_RESTITUITA.targa FROM TARGA_RESTITUITA) THEN 'Restituita'
								WHEN TARGA.numero IN (SELECT TARGA_ATTIVA.targa FROM TARGA_ATTIVA) THEN 'Attiva'
						END AS stato

            FROM
                TARGA

            WHERE 1=1 ";

    if ($numTarga != "")
        $qry .= "AND TARGA.numero LIKE '%" . $numTarga . "%' ";

    if ($dataEM != "")
        $qry .= "AND TARGA.dataEM  LIKE '%" . $dataEM . "%' ";

		if($radiocheck=="targherest")
				$qry .= "AND TARGA.numero IN (SELECT TARGA_RESTITUITA.targa FROM TARGA_RESTITUITA)";

		if($radiocheck=="targheatt")
				$qry .= "AND TARGA.numero IN (SELECT TARGA_ATTIVA.targa FROM TARGA_ATTIVA)";


    return $qry;
	}

	#	CASE
	#			WHEN TARGA_ATTIVA.targa IS NOT NULL THEN 'Attiva'
	#			WHEN TARGA_RESTITUITA.targa IS NOT NULL THEN 'Restituita'
	#	END AS stato

	#LEFT JOIN
	#    TARGA_ATTIVA ON TARGA.numero = TARGA_ATTIVA.targa
	#LEFT JOIN
	#    TARGA_RESTITUITA ON TARGA.numero = TARGA_ATTIVA.targa

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
