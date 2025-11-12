<?php
session_start();
include "../inc/conexion.php";

if(!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado"){
    header("Location: ../vistas/login.php");
    exit();
}

// Capturar datos del formulario
$nombre = $_POST['nombre'];
$app = $_POST['ap_pat'];
$apm = $_POST['ap_mat'];
$rfc = $_POST['rfc'];
$cp = $_POST['cp'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$pass = $_POST['password'];
$confirmar = $_POST['confirmar'];

// Verificar que las contraseñas coincidan
if ($pass !== $confirmar) {
    echo "<script>alert('⚠️ Las contraseñas no coinciden'); window.location='../vistas/registro_cliente.php';</script>";
    exit();
}

// Insertar en la base de datos
$sql = "INSERT INTO cliente (Nom_cl, App_cl, Apm_cl, cp_cl, Rfc_cl, tel_cl, Cor_cl, Dir_cl, Con_cli)
        VALUES ('$nombre', '$app', '$apm', '$cp', '$rfc', '$telefono', '$correo', '$direccion', '$pass')";

if($conexion->query($sql)){
    echo "<script>alert('✅ Cliente registrado correctamente'); window.location='../vistas/panel_abogado.php';</script>";
} else {
    echo "<script>alert('❌ Error al guardar el cliente: ".$conexion->error."'); window.location='../vistas/registro_cliente.php';</script>";
}
?>
