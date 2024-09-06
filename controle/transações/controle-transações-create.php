<?php
require_once("modelo/Bancos.php");
require_once("modelo/Transacao.php");
require_once("modelo/Moeda.php");

$textoRecebido = file_get_contents("php://input");
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

$objResposta = new stdClass();
$objTransacao = new Transacao();
$objTransacao->setMoedaOrig($objJson->transacao->moedaOrig);
$objTransacao->setQtdMoedaOrig($objJson->transacao->qtdMoeda);

$objMoeda = new Moeda();
// Define o ID do moeda a ser lido
$objMoeda->setIsoMoeda($objTransacao->getMoedaOrig());

// Obtém o moeda específico do banco de dados com base no ID fornecido
$vetor = $objMoeda ->readByISO();
$moeda = $vetor[0];

$qtdConv = $moeda->taxaConv * $objTransacao->qtdMoeda;

$objTransacao -> setQtdConv($qtdConv);

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
else if (strlen($objTransacao->getIdTransacao()) !== 3) {
    $objResposta->cod = 3;
    $objResposta->status = false;
    $objResposta->msg = "o ISO deve conter 3 caracteres";
}
else{
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
    header("HTTP/1.1 201");
} else {
    header("HTTP/1.1 200");
}

echo json_encode($objResposta);
?>