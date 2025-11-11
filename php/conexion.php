<?php

function conexion(){
    try{
        $pdo = new PDO("mysql:host=localhost;dbname=rifcu","root","");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }catch(PDOException $error){
        die("Error en la conexión: ".$error->getMessage());
    }
}
