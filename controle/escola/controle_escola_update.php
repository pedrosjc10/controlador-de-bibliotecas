<?php

require_once ("modelo/Banco.php");
require_once ("modelo/Escola.php");

$textoRecebido = file_get_contents("php://input");
$objJson = json_decode($textoRecebido) or die('{"msg":"formato incorreto"}');

$objResposta = new stdClass();
$objEscola = new Escola();

$objEscola->setidEscola($idEscola);
$objEscola->setemailEscola($objJson->Escola->email);
$objEscola->setnomeEscola($objJson->Escola->nomeEscola);
$objEscola->setsenhaEscola($objJson->Escola->senhaEscola);




if ($objEscola->getnomeEscola() == "") {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "a nome da escola nao pode ser vazia";
} 
elseif ($objEscola->getemailEscola() == "") {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "o email da escola nao pode ser vazio";
}
elseif ($objEscola->getsenhaEscola() == "") {
    $objResposta->cod = 1;
    $objResposta->status = false;
    $objResposta->msg = "a senha da escola nao pode ser vazia";
}

else if ($objEscola->isEscola() == true) {
    $objResposta->cod = 3;
    $objResposta->status = false;
    $objResposta->msg = "Ja existe uma escola cadastrada com o nome: " . $objEscola->getnomeEscola();
} 
else {
    if ($objEscola->update() == true) {
        $objResposta->cod = 4;
        $objResposta->status = true;
        $objResposta->msg = "Atualizada com sucesso";
        $objResposta->escolaAtualizada = $objEscola;
    } 
    else {
        $objResposta->cod = 5;
        $objResposta->status = false;
        $objResposta->msg = "Erro ao cadastrar a nova Escola";
    }
}
header("HTTP/1.1 200");
header("Content-Type: application/json");
echo json_encode($objResposta);
?>
