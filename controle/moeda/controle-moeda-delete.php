<?php
// Inclui a classe Moeda.php, que provavelmente contém funcionalidades relacionadas aos moedas
require_once ("modelo/Moeda.php");
// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Moeda
$objMoeda = new Moeda();
// Define o ID do moeda a ser excluído
$objMoeda->setIdMoeda($idMoeda);
// Verifica se a exclusão do moeda foi bem-sucedida
if($objMoeda->delete()==true){
    // Define o código de status da resposta como 204 (No Content)
    header("HTTP/1.1 204");
}else{
    // Define o código de status da resposta como 200 (OK)
    header("HTTP/1.1 200");
    // Define o tipo de conteúdo da resposta como JSON
    header("Content-Type: application/json");
    // Define novamente o código de status da resposta como 200 (OK)
    header("HTTP/1.1 200");
    // Define o tipo de conteúdo da resposta como JSON novamente
    header("Content-Type: application/json");
    // Define o status da resposta como falso
    $objResposta->status = false;
    // Define o código de resposta como 1
    $objResposta->cod = 1;
    $objResposta->msg = "Erro ao excluir moeda";
    echo json_encode($objResposta);
}
?>
