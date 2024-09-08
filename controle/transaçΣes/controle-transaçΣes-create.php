<?php
require_once("modelo/Bancos.php");
require_once("modelo/Transacao.php");
require_once("modelo/Moeda.php");

$textoRecebido = file_get_contents("php://input");
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

$objResposta = new stdClass();
$objTransacao = new Transacao();
$objTransacao->setMoedaOrig($objJson->transacao->moedaOrig);
$objTransacao->setQtdMoeda($objJson->transacao->qtdMoeda);

$objMoeda = new Moeda();
$objMoeda->setIdMoeda($objTransacao->getMoedaOrig());

// Obtém o moeda específico do banco de dados com base no ID fornecido
$vetor = $objMoeda->readByISO();
if (empty($vetor)) {
    $objResposta->cod = 4;
    $objResposta->status = false;
    $objResposta->msg = "Moeda não encontrada";
    header("Content-Type: application/json");
    header("HTTP/1.1 404 Not Found");
    echo json_encode($objResposta);
    exit();
}
$moeda = $vetor[0];

$qtdConv = $moeda->getTaxaConv() * $objTransacao->getQtdMoeda();
$objTransacao->setQtdConv($qtdConv);

if (empty($objTransacao->getMoedaOrig())) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "moedaOrig não pode ser vazio";
}
else if (empty($objTransacao->getQtdMoeda())) {
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "qtdMoeda não pode ser vazio";
} 
else {
    if ($objTransacao->create() == true) {
        $objResposta->cod = 5;
        $objResposta->status = true;
        $objResposta->msg = "cadastrado com sucesso";
        $objResposta->novaTransacao = $objTransacao;
    } 
    else {
        $objResposta->cod = 6;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar nova Transacao";
    }
}

header("Content-Type: application/json");

if ($objResposta->status == true) {
    header("HTTP/1.1 201 Created");
} else {
    header("HTTP/1.1 400 Bad Request");
}

echo json_encode($objResposta);
