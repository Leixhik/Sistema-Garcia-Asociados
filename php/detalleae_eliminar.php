<?php
session_start();
include "../inc/conexion.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado" || (int)$_SESSION['es_admin'] !== 1) {
    header("Location: ../vistas/login.php");
    exit();
}

if (!isset($_POST['id_da'])) {
    header("Location: ../vistas/detalleae.php?msg=error_id");
    exit();
}

$id = (int)$_POST['id_da'];
$sql = "DELETE FROM detalleae WHERE Id_da = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../vistas/detalleae.php?msg=ok");
} else {
    header("Location: ../vistas/detalleae.php?msg=error_exec");
}
$stmt->close();
exit();
?>
