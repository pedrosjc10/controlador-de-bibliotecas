<?php
    // Inclui as classes Banco e Professor, que contêm funcionalidades relacionadas ao banco de dados e aos professores
    require_once ("modelo/Banco.php");
    require_once ("modelo/Professor.php");

    // Obtém os dados enviados por meio de uma requisição POST em formato JSON
    $textoRecebido = file_get_contents("php://input");
    // Decodifica os dados JSON recebidos em um objeto PHP ou interrompe o script se o formato estiver incorreto
    $objJson = json_decode($textoRecebido);

    // Cria um novo objeto para armazenar a resposta 
    $objResposta = new stdClass();
    
    // Verifica se o JSON foi decodificado corretamente e se a propriedade 'professores' está presente
    if ($objJson === null || !isset($objJson->professores)) {
        $objResposta->cod = 1;
        $objResposta->status = false;
        $objResposta->msg = "formato incorreto ou dados incompletos";
        echo json_encode($objResposta);
        exit;
    }

    // Cria um novo objeto da classe professor
    $objProfessor = new Professor();

    // Define os dados do professor recebidos do JSON no objeto professor
    if (isset($objJson->professores->nomeProfessor)) {
        $objProfessor->setnomeProfessor($objJson->professores->nomeProfessor);
    }

    if (isset($objJson->professores->telProfessor)) {
        $objProfessor->settelProfessor($objJson->professores->telProfessor);
    }

    if (isset($objJson->professores->cpfProfessor)) {
        $objProfessor->setcpfProfessor($objJson->professores->cpfProfessor);
    }

    if (isset($objJson->professores->especialProfessor)) {
        $objProfessor->setespecialProfessor($objJson->professores->especialProfessor);
    }
    

    // Verifica se o nomeProfessor do professor está vazio
    if ($objProfessor->getnomeProfessor() == "") {
        $objResposta->cod = 1;
        $objResposta->status = false;
        $objResposta->msg = "o nome do professor nao pode ser vazio";
    } 

    // Verifica se já existe um professor cadastrado com o mesmo nomeProfessor
    else if ($objProfessor->isProfessor() == true) {
        $objResposta->cod = 3;
        $objResposta->status = false;
        $objResposta->msg = "Ja existe um professor cadastrado com o nome: " . $objProfessor->getnomeProfessor();
    } 
    // Se todas as condições anteriores forem atendidas, tenta criar um novo professor
    else {
        // Verifica se a criação do novo professor foi bem-sucedida
        if ($objProfessor->create() == true) {
            $objResposta->cod = 4;
            $objResposta->status = true;
            $objResposta->msg = "cadastrado com sucesso";
            $objResposta->novaProfessor = $objProfessor;
        } 
        // Se houver erro na criação do professor, define a mensagem de erro
        else {
            $objResposta->cod = 5;
            $objResposta->status = false;
            $objResposta->msg = "Erro ao cadastrar novo professor";
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