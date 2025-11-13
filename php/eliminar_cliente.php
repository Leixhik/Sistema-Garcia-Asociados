<?php
session_start();
include "../inc/conexion.php";

if(!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado" || (int)$_SESSION['es_admin'] !== 1){
    header("Location: ../vistas/login.php");
    exit();
}

if (!isset($_POST['id_cl'])) {
    header("Location: ../vistas/clientes_registrados.php?msg=error_id");
    exit();
}

$id = (int)$_POST['id_cl'];

$sql = "DELETE FROM cliente WHERE Id_cl = ?";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    header("Location: ../vistas/clientes_registrados.php?msg=error_sql");
    exit();
}
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../vistas/clientes_registrados.php?msg=ok");
} else {
    header("Location: ../vistas/clientes_registrados.php?msg=error_exec");
}
$stmt->close();
exit();
?>
