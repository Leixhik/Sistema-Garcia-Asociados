<?php
session_start();
include "../inc/conexion.php";

if(!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado"){
    header("Location: ../vistas/login.php");
    exit();
}

// Capturar datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$app = trim($_POST['ap_pat'] ?? '');
$apm = trim($_POST['ap_mat'] ?? '');
$rfc = trim($_POST['rfc'] ?? '');
$cp = trim($_POST['cp'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$pass = trim($_POST['password'] ?? '');
$confirmar = trim($_POST['confirmar'] ?? '');

// Validar campos vacíos
if ($nombre==='' || $app==='' || $correo==='' || $pass==='' || $confirmar==='') {
    header("Location: ../vistas/registro_cliente.php?msg=cli_faltan");
    exit();
}

// Verificar que las contraseñas coincidan
if ($pass !== $confirmar) {
    header("Location: ../vistas/registro_cliente.php?msg=cli_pass");
    exit();
}

// Insertar cliente (con seguridad)
$sql = "INSERT INTO cliente (Nom_cl, App_cl, Apm_cl, cp_cl, Rfc_cl, tel_cl, Cor_cl, Dir_cl, Con_cli)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if(!$stmt){
    header("Location: ../vistas/registro_cliente.php?msg=cli_sql_prep");
    exit();
}

$stmt->bind_param("sssssssss", $nombre, $app, $apm, $cp, $rfc, $telefono, $correo, $direccion, $pass);

if($stmt->execute()){
    header("Location: ../vistas/registro_cliente.php?msg=cli_ok");
} else {
    header("Location: ../vistas/registro_cliente.php?msg=cli_err");
}
$stmt->close();
exit();
?>
