<?php

use Firebase\JWT\TokenJWT;
require_once ("modelo/Escola.php");
require_once("modelo/TokenJWT.php");

$textoRecebido = file_get_contents("php://input");
$objJson = json_decode($textoRecebido) or die('{"msg" : "formato incorreto"}');

$objResposta = new stdClass();
$objEscola = new Escola();

$objEscola->setemailEscola($objJson->Escola->emailEscola ?? '');
$objEscola->setsenhaEscola($objJson->Escola->senhaEscola ?? '');

if ($objEscola->getemailEscola() == "") {
    $objResposta->cod = 3;
    $objResposta->status = false;
    $objResposta->msg = "o email não pode ser vazio.";
} else if (strlen($objEscola->getemailEscola()) < 10) {
    $objResposta->cod = 4;
    $objResposta->status = false;
    $objResposta->msg = "o email deve ter mais do que 10 caracteres.";
}elseif($objEscola->getsenhaEscola() == ""){
    $objResposta->cod = 5;
    $objResposta->status = false;
    $objResposta->msg = "a senha não pode ser vazia.";
}else if (strlen($objEscola->getsenhaEscola()) < 3) {
    $objResposta->cod = 6;
    $objResposta->status = false;
    $objResposta->msg = "a senha deve ter mais do que 3 caracteres.";
}else {
    if ($objEscola->login() == true) {
        $tokenJWT = new TokenJWT();

        $objClaimsToken = new stdClass();
        $objClaimsToken->idEscola = $objEscola->getidEscola();
        $objClaimsToken->nameEscola = $objEscola->getnomeEscola();
        $objClaimsToken->email = $objEscola->getemailEscola();
        $objClaimsToken->senhaEscola = $objEscola->getsenhaEscola();
        $objClaimsToken->idprofessor = $objEscola->getIdprofessor();
        $objClaimsToken->prof = $objEscola->getNomeProfessor();

        $novoToken = $tokenJWT->gerarToken($objClaimsToken);

        $objResposta->cod = 1;
        $objResposta->status = true;
        $objResposta->msg = "login efetuado com sucesso.";
        $objResposta->login = $objEscola;
        $objResposta->token = $novoToken;
    } else {
        $objResposta->cod = 2;
        $objResposta->status = false;
        $objResposta->msg = "login invalido.";
    }
}

header("Content-Type: application/json");
if($objResposta->status == true){
    header("HTTP/1.1 200");
}else{
    header("HTTP/1.1 401");
}

echo json_encode($objResposta);
