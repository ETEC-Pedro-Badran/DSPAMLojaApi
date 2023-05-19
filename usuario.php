<?php
require_once "domain/usuario.class.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method=="POST") {
    
    $json = json_decode( file_get_contents("php://input"));
    //error_log(print_r($json, true));
    $usuario = new Usuario();
    $usuario->email = $json->email;
    $usuario->nome = $json->nome;
    $usuario->setSenha($json->senha);
    exit( json_encode($usuario->incluir()));
} else if ($method=="GET" && isset($_GET["email"]) && 
    isset($_GET["senha"])) {
    $usuario = new Usuario();
    $usuario->email = $_GET["email"];
    $usuario->setSenha($_GET["senha"]);
    $usuario->valida();
    if ($usuario->id>0)
      exit( json_encode(['ok'=>true,'usuario'=>$usuario]));
    else 
      exit( http_response_code(403));   
      
} else { 
  exit( http_response_code(400));   
}