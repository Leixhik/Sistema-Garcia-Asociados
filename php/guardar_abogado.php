<?php
session_start();
include "../inc/conexion.php";

// Solo el admin puede ejecutar esto
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado" || $_SESSION['es_admin'] != 1){
    header("Location: ../vistas/login.php");
    exit();
}

$nombre = $_POST['nombre'];
$app = $_POST['ap_pat'];
$apm = $_POST['ap_mat'];
$dir = $_POST['dir'];
$cel = $_POST['cel'];
$tel = $_POST['tel'];
$correo = $_POST['correo'];
$pass = $_POST['password'];
$confirmar = $_POST['confirmar'];

// Verificar contraseñas
if($pass !== $confirmar){
    echo "<script>alert('⚠️ Las contraseñas no coinciden'); window.location='../vistas/registro_abogado.php';</script>";
    exit();
}

// Insertar el nuevo abogado
$sql = "INSERT INTO abogado (Nom_abgd, App_abgd, Apm_abgd, Dir_abgd, Cel_abgd, Tel_abgd, Cor_abgd, Con_abgd)
        VALUES ('$nombre', '$app', '$apm', '$dir', '$cel', '$tel', '$correo', '$pass')";

if($conexion->query($sql)){
    echo "<script>alert('✅ Abogado registrado correctamente'); window.location='../vistas/panel_abogado.php';</script>";
} else {
    echo "<script>alert('❌ Error al guardar el abogado: ".$conexion->error."'); window.location='../vistas/registro_abogado.php';</script>";
}
?>
