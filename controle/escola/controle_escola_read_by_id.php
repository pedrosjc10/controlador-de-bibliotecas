<?php

use Firebase\JWT\MeuTokenJWT;

require_once ("modelo/MeuTokenJWT.php");
require_once ("modelo/Banco.php");
require_once ("modelo/Escola.php");



// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();

$headers = getallheaders();
$authorization = $headers['Authorization'];

$meuToken = new MeuTokenJWT();
if($meuToken -> validarToken($authorization) == true){
    $payloadRecuperado = $meuToken -> getPayload();

// Cria um novo objeto da classe Escola
    $objEscola = new Escola();
    // Define o ID da Escola a ser lido
    $objEscola->setidEscola($idEscola);

    // Obtém a Escola específica do banco de dados com base no ID fornecido
    $vetor = $objEscola ->readByID();

    // Define o código de resposta como 1
    $objResposta->cod = 1;
    // Define o status da resposta como verdadeiro
    $objResposta->status = true;
    // Define a mensagem de sucesso
    $objResposta->msg = "executado com sucesso";
    // Define o vetor de Escola na resposta
    $objResposta->Escola = $vetor;

    $objResposta->token = $meuToken->gerarToken($payloadRecuperado);

    // Define o código de status da resposta como 200 (OK)
    header("HTTP/1.1 200");
    // Define o tipo de conteúdo da resposta como JSON
    header("Content-Type: application/json");
    // Converte o objeto resposta em JSON e o imprime na saída
    echo json_encode($objResposta);
}else{
    $objResposta->cod = 2;
    $objResposta->status = false;
    $objResposta->msg = "Token inválido.";
    header("HTTP/1.1 401");
}
?>
