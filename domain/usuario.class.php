<?php
require_once "db/conexao.php";

class Usuario {
    public $id;
    public $nome;
    public $email;
    public $senha;
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




    public function get($id){
        $con = Conexao::getInstance();
        $sql = "select id, nome, email, foto from usuario \n".
        "where id = :id";
        $st = $con->prepare($sql);
        $st->bindValue(":id",$id);
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


    public function update(){
        $con = Conexao::getInstance();
        $senha = "";
        if ($this->senha!=null && strlen($this->senha)>0) {
            $senha = ", senha = :senha ";
        }
        $sql = "update usuario set nome = :nome, email = :email, foto = :foto $senha where id = :id";
        $st = $con->prepare($sql);
        $st->bindValue(":nome",$this->nome);        
        $st->bindValue(":email",$this->email);                
        $st->bindValue(":foto",$this->foto);
        $st->bindValue(":id",$this->id);
        if ($this->senha!=null && strlen($this->senha)>0) {
        }        $st->bindValue(":senha",$this->senha);
        try {
            $st->execute();
            return ['ok'=>true];
        } catch(PDOException $e) {
            return ['ok'=>false,'erro'=>$e->getMessage()];
        }
        
    }

}