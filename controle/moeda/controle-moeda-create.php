<?php
require_once("modelo/Bancos.php");
require_once("modelo/Moeda.php");

$textoRecebido = file_get_contents("php://input");
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

$objResposta = new stdClass();
$objMoeda = new Moeda();
$objMoeda -> setIsoMoeda($objJson->moeda->isoMoeda);
$objMoeda -> setTaxaConv($objJson->moeda->taxaConv);
$objMoeda -> setNome($objJson->moeda->nome);




if (empty($objMoeda->getTaxaConv())) {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "taxaConv não pode ser vazio";
}
else if (empty($objMoeda->getNome())) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "nome não pode ser vazio";
} 
else if (strlen($objMoeda->getIsoMoeda()) !== 3) {
    $objResposta->cod = 3;
    $objResposta->status = false;
    $objResposta->msg = "o ISO deve conter 3 caracteres";
}
else if ($objMoeda->isISO()) {
    $objResposta->cod = 4;
    $objResposta->status = false;
    $objResposta->msg = "Já existe uma moeda cadastrada com o código ISO: " . $objMoeda->getIsoMoeda();
}else{
    if ($objMoeda->create() == true) {
        $objResposta->cod = 5;
        $objResposta->status = true;
        $objResposta->msg = "cadastrado com sucesso";
        $objResposta->novaMoeda = $objMoeda;
    } 
    else {
        $objResposta->cod = 6;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar nova Moeda";
    }
}

header("Content-Type: application/json");

if ($objResposta->status == true) {
    header("HTTP/1.1 201");
} else {
    header("HTTP/1.1 200");
}

echo json_encode($objResposta);
?>