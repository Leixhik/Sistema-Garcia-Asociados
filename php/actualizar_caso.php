<?php
session_start();
require_once __DIR__ . '/../inc/conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abogado') {
    header("Location: ../vistas/login.php");
    exit();
}

$idAbogado = $_SESSION['Id_abgd'];
$esAdmin   = $_SESSION['es_admin'];

if (
    empty($_POST['id_caso']) || 
    empty($_POST['no_caso']) ||
    empty($_POST['tipo']) ||
    empty($_POST['desc'])
) {
    header("Location: ../vistas/casos_abogado.php?msg=cs_edit_err");
    exit();
}

$id_caso = (int)$_POST['id_caso'];
$no_caso = trim($_POST['no_caso']);
$tipo    = trim($_POST['tipo']);
$estado  = trim($_POST['estado']);
$desc    = trim($_POST['desc']);
$detalle = trim($_POST['detalle'] ?? "");

/* ============================
   Validar propiedad del caso
   ============================ */
if ($esAdmin == 1) {
    $sql = "SELECT Id_cs FROM casos WHERE Id_cs = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_caso);
} else {
    $sql = "SELECT Id_cs FROM casos WHERE Id_cs = ? AND Ced_abgd_ct = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id_caso, $idAbogado);
}

$stmt->execute();
$valid = $stmt->get_result()->num_rows;
$stmt->close();

if (!$valid) {
    header("Location: ../vistas/casos_abogado.php?msg=cs_edit_err");
    exit();
}

/* ============================
   Actualizar caso
   ============================ */

$sql = "UPDATE casos SET
        No_cs = ?, 
        Desc_cs = ?, 
        Tipo_cs = ?, 
        Estado_cs = ?, 
        Detalle_cs = ?,
        Fecha_act = NOW()
        WHERE Id_cs = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssi",
    $no_caso,
    $desc,
    $tipo,
    $estado,
    $detalle,
    $id_caso
);

if ($stmt->execute()) {
    header("Location: ../vistas/casos_abogado.php?msg=cs_edit_ok");
} else {
    header("Location: ../vistas/casos_abogado.php?msg=cs_edit_err");
}
exit();
