<?php
// Inclui as classes Banco e Moeda, que contêm funcionalidades relacionadas ao banco de dados e aos cargos
require_once ("modelo/Bancos.php");
require_once ("modelo/Moeda.php");

// Obtém os dados enviados por meio de uma requisição POST em formato JSON
$textoRecebido = file_get_contents("php://input");
// Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Moeda
$objMoeda = new Moeda();
// Define o ISO da moeda a ser atualizado
$objMoeda->setIsoMoeda($isoMoeda);

$objMoeda->setTaxaConv($objJson->moeda->taxaConv);

// Verifica se o nome da moeda está vazio
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
// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);
?>
