<?php
    // Inclui as classes Banco e Turma, que contêm funcionalidades relacionadas ao banco de dados e aos turmas
    require_once ("modelo/Banco.php");
    require_once ("modelo/Turma.php");

    // Obtém os dados enviados por meio de uma requisição POST em formato JSON
    $textoRecebido = file_get_contents("php://input");
    // Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
    $objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

    // Cria um novo objeto para armazenar a resposta
    $objResposta = new stdClass();
    // Cria um novo objeto da classe turma
    $objTurma = new Turma();

    // Define o nome do turma recebido do JSON no objeto turma
    $objTurma->setserieTurma($objJson->turmas->serieTurma);
    $objTurma->setqtdAlunos($objJson->turmas->qtdAlunos);
    $objTurma->setcurso($objJson->turmas->curso);

    // Verifica se a serieTurma da turma está vazio
    if ($objTurma->getserieTurma() == "") {
        $objResposta->cod = 1;
        $objResposta->status = false;
        $objResposta->msg = "a serie nao pode ser vazia";
    } 
    if ($objTurma->getqtdAlunos() == "") {
        $objResposta->cod = 1;
        $objResposta->status = false;
        $objResposta->msg = "a quantidade de Alunos nao pode ser vazia";
    } 
    if ($objTurma->getcurso() == "") {
        $objResposta->cod = 1;
        $objResposta->status = false;
        $objResposta->msg = "o curso nao pode ser vazio";
    } 

    // Verifica se o nome da turma tem 2 caracteres
    if (strlen($objTurma->getserieTurma()) != 2) {  
        $objResposta->cod = 1;
        $objResposta->status = false;
        $objResposta->msg = "A serieTurma deve conter 2 caracteres";
    } 

    // Verifica se já existe um turma cadastrado com a mesma serieTurma
    else if ($objTurma->isTurma() == true) {
        $objResposta->cod = 3;
        $objResposta->status = false;
        $objResposta->msg = "Ja existe uma turma cadastrada com a serieTurma: " . $objTurma->getserieTurma();
    } 
    // Se todas as condições anteriores forem atendidas, tenta criar uma nova turma
    else {
        // Verifica se a criação da nova turma foi bem-sucedida
        if ($objTurma->create() == true) {
            $objResposta->cod = 4;
            $objResposta->status = true;
            $objResposta->msg = "cadastrada com sucesso";
            $objResposta->novaturma = $objTurma;
        } 
        // Se houver erro na criação da turma, define a mensagem de erro
        else {
            $objResposta->cod = 5;
            $objResposta->status = false;
            $objResposta->msg = "Erro ao cadastrar nova turma";
        }
    }

    // Define o tipo de conteúdo da resposta como JSON
    header("Content-Type: application/json");

    // Define o código de status da resposta com base no status da operação
    if ($objResposta->status == true) {
        header("HTTP/1.1 201");
    } else {
        header("HTTP/1.1 200");
    }

    // Converte o objeto resposta em JSON e o imprime na saída
    echo json_encode($objResposta);

?>
