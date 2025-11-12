<?php
session_start();
require_once __DIR__ . '/../inc/conexion.php';

// ✅ Solo abogados pueden eliminar citas
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abogado') {
    header("Location: ../vistas/login.php");
    exit();
}

if (empty($_POST['id_cita'])) {
    header("Location: ../vistas/citas_abogado.php?msg=error_id");
    exit();
}

$id_cita = (int)$_POST['id_cita'];
$id_abogado = (int)$_SESSION['Id_abgd'];

// 🔍 Intentar eliminar la cita solo si pertenece al abogado
$sql = "DELETE FROM cita WHERE Id_ct = ? AND abgd_id_ct = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    header("Location: ../vistas/citas_abogado.php?msg=error_sql");
    exit();
}

$stmt->bind_param('ii', $id_cita, $id_abogado);
$stmt->execute();

// Verificar si se eliminó algo
if ($stmt->affected_rows > 0) {
    header("Location: ../vistas/citas_abogado.php?msg=ok");
} else {
    header("Location: ../vistas/citas_abogado.php?msg=notfound");
}

$stmt->close();
exit();
?>
