<?php
session_start();
include "../inc/conexion.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado" || (int)$_SESSION['es_admin'] !== 1) {
    header("Location: ../vistas/login.php");
    exit();
}

if (!isset($_POST['id_abgd'])) {
    header("Location: ../vistas/abogados_registrados.php?msg=error_id");
    exit();
}

$id = (int)$_POST['id_abgd'];
$id_admin_actual = (int)$_SESSION['Id_abgd'];

// Prevenir que un admin se elimine a sí mismo o a otro admin
$consulta = $conexion->prepare("SELECT es_admin FROM abogado WHERE Id_abgd = ?");
$consulta->bind_param("i", $id);
$consulta->execute();
$res = $consulta->get_result();
$abg = $res->fetch_assoc();
$consulta->close();

if (!$abg) {
    header("Location: ../vistas/abogados_registrados.php?msg=error_id");
    exit();
}

if ((int)$abg['es_admin'] === 1 || $id === $id_admin_actual) {
    header("Location: ../vistas/abogados_registrados.php?msg=no_permiso");
    exit();
}

$sql = "DELETE FROM abogado WHERE Id_abgd = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    header("Location: ../vistas/abogados_registrados.php?msg=error_sql");
    exit();
}
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../vistas/abogados_registrados.php?msg=ok");
} else {
    header("Location: ../vistas/abogados_registrados.php?msg=error_exec");
}
$stmt->close();
exit();
?>
