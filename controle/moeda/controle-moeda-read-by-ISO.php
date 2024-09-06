<?php

require_once ("modelo/Bancos.php");
require_once ("modelo/Moeda.php");


$objResposta = new stdClass();


$objMoeda = new Moeda();
$objMoeda->setIsoMoeda($isoMoeda);


$vetor = $objMoeda ->readByISO();

objResposta->cod = 1;

$objResposta->status = true;
posta->msg = "executado com sucesso";

$objResposta->moedas = $vetor;


header("HTTP/1.1 200");

header("Content-Type: application/json");

echo json_encode($objResposta);

?>
