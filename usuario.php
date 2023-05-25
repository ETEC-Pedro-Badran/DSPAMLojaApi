<?php
require_once "domain/usuario.class.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method=="GET" && isset($_GET["email"]) && 
    isset($_GET["senha"])) {
    $usuario = new Usuario();
    $usuario->email = $_GET["email"];
    $usuario->setSenha($_GET["senha"]);
    $usuario->valida();
    if ($usuario->id>0)
      exit( json_encode(['ok'=>true,'usuario'=>$usuario]));
    else 
      exit( http_response_code(403));   
} else if ($method=="POST" && isset($_POST['id'])) {
    $usuario = new Usuario();
    $usuario->get($_POST["id"]);
    if ($usuario->id>0) {
      $usuario->nome = $_POST["nome"];
      $usuario->email = $_POST["email"];
      if ($_POST["senha"]!="") {
        $usuario->senha = $_POST["senha"];
      }
      
      if (isset($_POST["upload"])) {
        $pasta = "imagens";
        $gername = dechex(time()); 
        $filename = "$pasta/$gername.jpeg";
        $arquivo = fopen($filename,'w+');
        $foto = $_POST["upload"];
        fwrite($arquivo, $foto);
        fclose($arquivo);
        if ($arquivo == false) throw new Exception("Erro salvando foto");
        if ($usuario->foto){ 
          unlink("$pasta/$usuario->foto");
        }
        $usuario->foto = "$gername.jpeg";
        $usuario->update();
      }
      
      
   } else {
      exit( http_response_code(500)); // internal error
    }

    http_response_code(500);
    
} else if ($method=="POST") {
  $json = json_decode( file_get_contents("php://input"));
  //error_log(print_r($json, true));
  $usuario = new Usuario();
  $usuario->email = $json->email;
  $usuario->nome = $json->nome;
  $usuario->setSenha($json->senha);
  exit( json_encode($usuario->incluir()));
} else { 
  exit( http_response_code(400));   
}