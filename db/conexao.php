<?php

class Conexao {

    const usuario = 'root';
    const host = 'localhost';
    const dbname = 'loja';
    const passwd = '';

    static function getInstance(){
        return new PDO('mysql:host='.Conexao::host.';dbname='.Conexao::dbname,
        Conexao::usuario, Conexao::passwd);
    } 

}
/*
create table usuario ( id int primary key AUTO_INCREMENT,
                      nome varchar(100) not null,
                      email varchar(100) not null unique,
                      senha varchar(100) not null, 
                      foto varchar(255));
*/
?>
