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
} else if ($method=="POST" && isset($_POST["foto"])) {
    $usuario = new Usuario();
    $usuario->get($_POST["usuario_id"]);
    if ($usuario->id>0) {
      $pasta = "imagens";
      $gername = dechex(time()); 
      $filename = "$pasta/$gername.jpeg";
      $arquivo = fopen($filename,'w+');
      $foto = $_POST["foto"];
      fwrite($arquivo, $foto);
      fclose($arquivo);
      if ($arquivo == false) throw new Exception("Erro salvando foto");
      if ($usuario->foto){ 
        unlink("$pasta/$usuario->foto");
      }
      $usuario->foto = "$gername.jpeg";
      $usuario->updateFoto();
   } else {
      exit( http_response_code(500)); // internal error
    }
} else { 
  exit( http_response_code(400));   
}