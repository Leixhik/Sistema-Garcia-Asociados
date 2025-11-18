<?php
session_start();
require_once __DIR__ . '/../inc/conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abogado') {
    header("Location: ../vistas/login.php");
    exit();
}

if (!isset($_POST['id_caso']) || !isset($_POST['nota'])) {
    header("Location: ../vistas/casos.php?msg=cs_err");
    exit();
}

$idCaso = (int)$_POST['id_caso'];
$idAbogado = (int)$_SESSION['Id_abgd'];
$nota = trim($_POST['nota']);

if ($idCaso <= 0 || empty($nota)) {
    header("Location: ../vistas/casos.php?msg=cs_err");
    exit();
}

$sql = "INSERT INTO notas_caso (Id_cs, Id_abgd, nota) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error SQL: " . $conexion->error);
}

$stmt->bind_param("iis", $idCaso, $idAbogado, $nota);

if ($stmt->execute()) {
    header("Location: ../vistas/ver_caso.php?id=$idCaso&msg=nota_ok");
} else {
    header("Location: ../vistas/ver_caso.php?id=$idCaso&msg=nota_err");
}

$stmt->close();
exit();
?>
