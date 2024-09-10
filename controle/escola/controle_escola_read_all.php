<?php

use Firebase\JWT\MeuTokenJWT;

// Inclui as classes Banco e Escola, que contêm funcionalidades relacionadas ao banco de dados e as Escola
require_once ("modelo/Banco.php");
require_once ("modelo/Escola.php");
require_once("modelo/MeuTokenJWT.php");

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();

$headers = getallheaders();
$authorization = $headers['Authorization'];

$meuToken = new MeuTokenJWT();
if($meuToken -> validarToken($authorization) == true){
    $payloadRecuperado = $meuToken -> getPayload();
// Cria um novo objeto da classe Escola
    $objEscola = new Escola();

    // Obtém todos as Escola do banco de dados
    $vetor = $objEscola ->readAll();

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
