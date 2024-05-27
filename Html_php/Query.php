<?php
function getTarga($numTarga, $dataEM, $radiocheck,$valoreordinamento): string {
	$qry = "SELECT
	TARGA.numero AS numTarga,
	TARGA.dataEM AS dataEM,
	(SELECT COUNT(*) FROM REVISIONE WHERE REVISIONE.targa = TARGA.numero) AS count_revisioni,
	TARGA_RESTITUITA.veicolo AS telaio_res_associato,
	TARGA_ATTIVA.veicolo AS telaio_att_associato,

	CASE
	WHEN TARGA.numero IN (SELECT TARGA_RESTITUITA.targa FROM TARGA_RESTITUITA) THEN 'Restituita'
	WHEN TARGA.numero IN (SELECT TARGA_ATTIVA.targa FROM TARGA_ATTIVA) THEN 'Attiva'
	END AS stato

	FROM
	TARGA
	LEFT JOIN
	TARGA_ATTIVA ON TARGA.numero = TARGA_ATTIVA.targa
	LEFT JOIN
	TARGA_RESTITUITA ON TARGA.numero = TARGA_RESTITUITA.targa


	WHERE  1=1 ";

	if ($numTarga != "")
	$qry .= "AND TARGA.numero LIKE '%" . $numTarga . "%' ";

	if ($dataEM != "")
	$qry .= "AND TARGA.dataEM  LIKE '%" . $dataEM . "%' ";

	if($radiocheck=="targherest")
	$qry .= "AND TARGA.numero IN (SELECT TARGA_RESTITUITA.targa FROM TARGA_RESTITUITA)";

	if($radiocheck=="targheatt")
	$qry .= "AND TARGA.numero IN (SELECT TARGA_ATTIVA.targa FROM TARGA_ATTIVA)";

	$qry .="GROUP BY TARGA.numero, TARGA.dataEM";

	switch ($valoreordinamento) {
		case 'ordinamentoNullo':

		break;
		case 'ordinaDataEm':

		$qry .= " ORDER BY TARGA.dataEM";
		break;
		case 'ordinaNumeroTarga':

		$qry .= " ORDER BY TARGA.numero";
		break;

	}



	return $qry;
}

function verificaVeicolo($telaio, $conn): bool {
    $checkQuery = "SELECT COUNT(*) AS count FROM VEICOLO WHERE telaio = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$telaio]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $row['count'];
    return $count > 0;
}

function Inserimento($numTarga, $dataEM, $radio, $telaio, $datarest): string {
    $qry = "";
    if ($radio == 'targheatt') {
        $qry .= "INSERT INTO TARGA (numero, dataEM) VALUES ('$numTarga', '$dataEM'); 
		INSERT INTO TARGA_ATTIVA (targa, veicolo) VALUES ('$numTarga', '$telaio');";
    } else if ($radio == 'targherest') {
        $qry .= "INSERT INTO TARGA (numero, dataEM) VALUES ('$numTarga', '$dataEM');
		INSERT INTO TARGA_RESTITUITA (targa, veicolo, dataRes) VALUES ('$numTarga', '$telaio', '$datarest');";
    }

    return $qry;
}

function modifica($numTarga,$dataEM,$OLDstato,$statoTarga,$telaio,$dataRES): string{
	$qry ="";
	if($OLDstato=="Attiva" && $statoTarga=="targherest"){ //se rendiamo una targa attiva una targa restituita
		$qry.="DELETE FROM TARGA_ATTIVA WHERE targa = '$numTarga';";
		$qry .="INSERT INTO TARGA_RESTITUITA (targa,veicolo, dataRes) VALUES ('$numTarga','$telaio','$dataRES');";	
	}
	if($OLDstato=="Restituita" && $statoTarga=="targheatt"){ //se rendiamo una targa restituita una targa attiva
		$qry.="DELETE FROM TARGA_RESTITUITA WHERE targa = '$numTarga';";
		$qry .="INSERT INTO TARGA_ATTIVA (targa,veicolo) VALUES ('$numTarga','$telaio');";
	}
	if($OLDstato=="Restituita" && $statoTarga=="targherest"){ //se modifichiamo una targa restituita
		$qry .="UPDATE TABLE TARGA_RESTITUITA SET veicolo='$telaio', dataRes='$dataRES' WHERE targa='$numTarga';";
	}
	if($OLDstato=="Attiva" && $statoTarga=="targheatt"){ //se modifichiamo una targa attiva
		$qry .="UPDATE TABLE TARGA_ATTIVA SET veicolo='$telaio' WHERE targa='$numTarga';";
	}
	$qry .="UPDATE TABLE TARGA SET dataEM='$dataEM' WHERE numero='$numTarga'";
	return $qry;
}

function verificaTargaAttiva($telaio, $conn): bool {
    $checkQuery = "SELECT COUNT(*) AS count FROM TARGA_ATTIVA WHERE veicolo = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$telaio]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $row['count'];
    return $count > 0;
}


function queryRevisione($numRevione, $numTarga, $dataRE, $posneg, $valoreordinamento) : string {
	$qry = "SELECT
	REVISIONE.numero AS numRevisione,
	REVISIONE.dataRev AS dataRevisione,
	REVISIONE.targa AS numTarga,
	REVISIONE.esito AS esito,
	REVISIONE.motivazione AS motivazione
	FROM REVISIONE
	WHERE 1=1 ";

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
	$qry = "SELECT
	VEICOLO.telaio AS telaio,
	VEICOLO.marca AS marca,
	VEICOLO.modello AS modello,
	VEICOLO.dataProd AS data,
	(SELECT COUNT(*) FROM TARGA_RESTITUITA WHERE TARGA_RESTITUITA.veicolo=VEICOLO.telaio) as num_restituite,
	(SELECT targa FROM TARGA_ATTIVA WHERE TARGA_ATTIVA.veicolo=VEICOLO.telaio) as targa_attiva
	FROM VEICOLO
	WHERE 1=1 ";

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
