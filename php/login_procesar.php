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
$_SESSION['rol'] = "abogado";
$_SESSION['Ced_abgd'] = $data['Ced_abgd'];
$_SESSION['Nom_abgd'] = $data['Nom_abgd'];
$_SESSION['App_abgd'] = $data['App_abgd'];
$_SESSION['Apm_abgd'] = $data['Apm_abgd'];

    header("Location: ../vistas/panel_abogado.php");
    exit();
}

// Verificar si es cliente
$sql_cliente = "SELECT * FROM cliente WHERE Cor_cl = '$correo' AND Con_cli = '$password'";
$result_cliente = $conexion->query($sql_cliente);

if($result_cliente->num_rows > 0){
    $data = $result_cliente->fetch_assoc();

    $_SESSION['rol'] = "cliente";

    $_SESSION['id_cl']  = $data['Id_cl'];
    $_SESSION['Nom_cl'] = $data['Nom_cl'];
    $_SESSION['App_cl'] = $data['App_cl'];
    $_SESSION['Apm_cl'] = $data['Apm_cl'];

    header("Location: ../vistas/panel_cliente.php");
    exit();
}



// Si no coincide
header("Location: ../VISTAS/login.php?error=1");
exit();
