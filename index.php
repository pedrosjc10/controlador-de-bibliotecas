<?php
require_once("modelo/Router.php");

$roteador = new Router();

$roteador -> get("/usuarios", function(){
    require_once ("controle/usuarios/controle-usuarios-read-all.php");
});

$roteador -> get("/usuarios/(\d+)", function($idUsuario){
    require_once("controle/usuarios/controle-usuarios-read-by-ISO.php");
    
});

$roteador -> put("/usuarios/(\d+)", function($idUsuario){
    require_once("controle/usuarios/controle-usuarios-update.php");
});

$roteador -> delete("/usuarios/(\d+)", function($idUsuario){
    require_once("controle/usuarios/controle-usuarios-delete.php");
});
$roteador -> post("/usuarios", function(){
    require_once("controle/usuarios/controle-usuarios-create.php");
}); 

//Moeda

$roteador -> get("/moeda", function(){
    require_once ("controle/moeda/controle-moeda-read-all.php");
});

$roteador -> get("/moeda/(\d+)", function($isoMoeda){
    require_once("controle/moeda/controle-moeda-read-by-ISO.php");
    
});

$roteador -> put("/moeda/(\d+)", function($isoMoeda){
    require_once("controle/moeda/controle-moeda-update.php");
});

$roteador -> delete("/moeda/(\d+)", function($isoMoeda){
    require_once("controle/moeda/controle-moeda-delete.php");
});
$roteador -> post("/moeda", function(){
    require_once("controle/moeda/controle-moeda-create.php");
});

//Transações

$roteador -> get("/transações", function(){
    require_once ("controle/transações/controle-transações-read-all.php");
});

$roteador -> get("/transações/(\d+)", function($idTransação){
    require_once("controle/transações/controle-transações-read-by-ISO.php");
    
});

$roteador -> put("/transações/(\d+)", function($idTransação){
    require_once("controle/transações/controle-transações-update.php");
});

$roteador -> delete("/transações/(\d+)", function($idTransação){
    require_once("controle/transações/controle-transações-delete.php");
});
$roteador -> post("/transações", function(){
    require_once("controle/transações/controle-transações-create.php");
});

$roteador->run();
?>
pedro gay