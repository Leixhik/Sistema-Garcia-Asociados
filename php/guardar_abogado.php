<?php
session_start();
include "../inc/conexion.php";

// Solo el admin puede ejecutar esto
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado" || (int)$_SESSION['es_admin'] !== 1) {
    header("Location: ../vistas/login.php");
    exit();
}

$nombre     = trim($_POST['nombre'] ?? '');
$app        = trim($_POST['ap_pat'] ?? '');
$apm        = trim($_POST['ap_mat'] ?? '');
$dir        = trim($_POST['dir'] ?? '');
$cel        = trim($_POST['cel'] ?? '');
$tel        = trim($_POST['tel'] ?? '');
$correo     = trim($_POST['correo'] ?? '');
$pass       = trim($_POST['password'] ?? '');
$confirmar  = trim($_POST['confirmar'] ?? '');

// ✅ Validar datos vacíos
if ($nombre === '' || $app === '' || $correo === '' || $pass === '' || $confirmar === '') {
    header("Location: ../vistas/registro_abogado.php?msg=abg_faltan");
    exit();
}

// ✅ Verificar contraseñas
if ($pass !== $confirmar) {
    header("Location: ../vistas/registro_abogado.php?msg=abg_pass");
    exit();
}

// ✅ Insertar nuevo abogado
$sql = "INSERT INTO abogado (Nom_abgd, App_abgd, Apm_abgd, Dir_abgd, Cel_abgd, Tel_abgd, Cor_abgd, Con_abgd)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    header("Location: ../vistas/registro_abogado.php?msg=abg_sql_prep");
    exit();
}

$stmt->bind_param("ssssssss", $nombre, $app, $apm, $dir, $cel, $tel, $correo, $pass);

if ($stmt->execute()) {
    header("Location: ../vistas/registro_abogado.php?msg=abg_ok");
} else {
    header("Location: ../vistas/registro_abogado.php?msg=abg_err");
}
$stmt->close();
exit();
?>
