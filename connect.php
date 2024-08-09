<?php
//mysqli_set_charset($conexao, "utf8");
ini_set('error_reporting', E_ALL); // mesmo resultado de: error_reporting(E_ALL);
ini_set('display_errors', 1);

    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $dbname = "u953711579_database";    
    //Criar a conexao
    $conn = mysqli_connect($servidor, $usuario, $senha, $dbname);
    
    if(!$conn){
        die("Falha na conexao: " . mysqli_connect_error());
    }else{
        echo "Conexao realizada com sucesso";
    }      
?>
