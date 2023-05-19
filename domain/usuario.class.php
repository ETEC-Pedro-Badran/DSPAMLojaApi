<?php
require_once "db/conexao.php";

class Usuario {
    public $id;
    public $nome;
    public $email;
    private $senha;
    public $foto;

    public function setSenha($senha) {
        $this->senha = $senha;
    }


    public function incluir(){
        $con = Conexao::getInstance();
        $sql = "insert into usuario (nome, email, senha) \n"
        ." values (:nome, :email, :senha)";
        $st = $con->prepare($sql);
        $st->bindValue(":nome",$this->nome);
        $st->bindValue(":email",$this->email);
        $st->bindValue(":senha",$this->senha);
        try {
            $st->execute();
            return ['ok'=>true];
        } catch(PDOException $e) {
            return ['ok'=>false,'erro'=>$e->getMessage()];
        }
        
    }

    public function valida(){
        $con = Conexao::getInstance();
        $sql = "select id, nome, foto from usuario \n".
        "where email = :email and senha = :senha";
        $st = $con->prepare($sql);
        $st->bindValue(":email",$this->email);
        $st->bindValue(":senha",$this->senha);
        $st->execute();
        $registros = $st->fetchAll();
        if(count($registros)>0) {
            $this->id = $registros[0]["id"];
            $this->nome = $registros[0]["nome"];
            $this->foto = $registros[0]["foto"];
        }
    }




    public function get(){
        $con = Conexao::getInstance();
        $sql = "select id, nome, email, foto from usuario \n".
        "where id = :id";
        $st = $con->prepare($sql);
        $st->bindValue(":id",$this->email);
        $st->execute();
        $registros = $st->fetchAll();
        if(count($registros)>0) {
            $reg = $registros[0];
            $this->id = $reg["id"];
            $this->nome = $reg["nome"];
            $this->email = $reg["email"];
            $this->foto = $reg["foto"];
        }
    }


    public function udpateFoto(){
        $con = Conexao::getInstance();
        $sql = "update usuario set foto = :foto where id = :id";
        $st = $con->prepare($sql);
        $st->bindValue(":foto",$this->foto);
        $st->bindValue(":id",$this->id);
        try {
            $st->execute();
            return ['ok'=>true];
        } catch(PDOException $e) {
            return ['ok'=>false,'erro'=>$e->getMessage()];
        }
        
    }

}