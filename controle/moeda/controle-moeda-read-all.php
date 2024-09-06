<?php
// Inclui as classes Banco e Moeda, que contêm funcionalidades relacionadas ao banco de dados e aos moedas
require_once ("modelo/Bancos.php");
require_once ("modelo/Moeda.php");

// Cria um novo objeto para armazenar a resposta
$objResposta = new stdClass();
// Cria um novo objeto da classe Moeda
$objMoeda = new Moeda();

// Obtém todos os moedas do banco de dados
$vetor = $objMoeda ->readAll();

// Define o código de resposta como 1
$objResposta->cod = 1;
// Define o status da resposta como verdadeiro
$objResposta->status = true;
// Define a mensagem de sucesso
$objResposta->msg = "executado com sucesso";
// Define o vetor de moedas na resposta
$objResposta->moedas = $vetor;

// Define o código de status da resposta como 200 (OK)
header("HTTP/1.1 200");
// Define o tipo de conteúdo da resposta como JSON
header("Content-Type: application/json");
// Converte o objeto resposta em JSON e o imprime na saída
echo json_encode($objResposta);
?>
