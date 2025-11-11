<?php
session_start();
include "../INC/conexion.php";

$correo = trim($_POST['correo']);
$password = trim($_POST['password']);

if($correo == "" || $password == ""){
    header("Location: ../VISTAS/login.php?error=1");
    exit();
}

// Verificar si es abogado
$consulta_abogado = $conexion->query("SELECT * FROM abogado WHERE Cor_abgd='$correo' AND Con_abgd='$password'");
if($consulta_abogado->num_rows > 0){
    $abogado = $consulta_abogado->fetch_assoc();
    $_SESSION['usuario'] = $abogado['Nom_abgd'];
    $_SESSION['rol'] = "abogado";
    header("Location: ../index.php?vista=home");
    exit();
}

// Verificar si es cliente
$consulta_cliente = $conexion->query("SELECT * FROM cliente WHERE Cor_cl='$correo' AND Con_cli='$password'");
if($consulta_cliente->num_rows > 0){
    $cliente = $consulta_cliente->fetch_assoc();
    $_SESSION['usuario'] = $cliente['Nom_cl'];
    $_SESSION['rol'] = "cliente";
    header("Location: ../index.php?vista=home_cliente");
    exit();
}

header("Location: ../VISTAS/login.php?error=1");
exit();
