<?php
session_start();
include "../INC/conexion.php"; // Este archivo lo crearemos en el siguiente paso

$correo = $_POST['correo'];
$password = $_POST['password'];

// Verificar si es abogado
$sql_abogado = "SELECT * FROM abogado WHERE Cor_abgd = '$correo' AND Con_abgd = '$password'";
$result_abogado = $conexion->query($sql_abogado);

if($result_abogado->num_rows > 0){
    $data = $result_abogado->fetch_assoc();
    $_SESSION['usuario'] = $data['Nom_abgd'];
    $_SESSION['rol'] = "abogado";
    header("Location: ../VISTAS/panel_abogado.php");
    exit();
}

// Verificar si es cliente
$sql_cliente = "SELECT * FROM cliente WHERE Cor_cl = '$correo' AND Con_cli = '$password'";
$result_cliente = $conexion->query($sql_cliente);

if($result_cliente->num_rows > 0){
    $data = $result_cliente->fetch_assoc();
    $_SESSION['usuario'] = $data['Nom_cl'];
    $_SESSION['rol'] = "cliente";
    header("Location: ../VISTAS/panel_cliente.php");
    exit();
}

// Si no coincide
header("Location: ../VISTAS/login.php?error=1");
exit();
