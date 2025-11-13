<?php
session_start();
include "../inc/conexion.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "abogado") {
    header("Location: ../vistas/login.php");
    exit();
}

if (empty($_POST['cliente']) || empty($_POST['fecha']) || empty($_POST['hora'])) {
    header("Location: ../vistas/citas_abogado.php?msg=cita_faltan");
    exit();
}

$id_cliente = (int)$_POST['cliente'];
$fecha = $_POST['fecha'];
$hora  = $_POST['hora'];

$nom_abg = $_SESSION['Nom_abgd'] ?? '';
$app_abg = $_SESSION['App_abgd'] ?? '';
$apm_abg = $_SESSION['Apm_abgd'] ?? '';
$id_abg  = $_SESSION['Id_abgd'] ?? 0;

if ($id_abg <= 0) {
    header("Location: ../vistas/login.php");
    exit();
}

// ✅ 1) Obtener datos del cliente
$sql_cliente = "SELECT Nom_cl, App_cl, Apm_cl FROM cliente WHERE Id_cl = ? LIMIT 1";
$stmt_cli = $conexion->prepare($sql_cliente);
if (!$stmt_cli) {
    header("Location: ../vistas/citas_abogado.php?msg=cita_sql_prep_cli");
    exit();
}
$stmt_cli->bind_param("i", $id_cliente);
$stmt_cli->execute();
$res = $stmt_cli->get_result();
$cliente = $res->fetch_assoc();
$stmt_cli->close();

if (!$cliente) {
    header("Location: ../vistas/citas_abogado.php?msg=cita_no_cli");
    exit();
}

// ✅ 2) Insertar la cita
$sql_insert = "INSERT INTO cita (
    Hra_ct, Da_ct, Id_cl_ct, Nom_cl_ct, App_cl_ct, Apm_cl_ct,
    abgd_id_ct, Nom_abgd_ct, App_abgd_ct, Apm_abgd_ct
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt_ins = $conexion->prepare($sql_insert);
if (!$stmt_ins) {
    header("Location: ../vistas/citas_abogado.php?msg=cita_sql_prep_ins");
    exit();
}

$stmt_ins->bind_param(
    "ssisssisss",
    $hora,
    $fecha,
    $id_cliente,
    $cliente['Nom_cl'],
    $cliente['App_cl'],
    $cliente['Apm_cl'],
    $id_abg,
    $nom_abg,
    $app_abg,
    $apm_abg
);

if ($stmt_ins->execute()) {
    header("Location: ../vistas/citas_abogado.php?msg=cita_ok");
} else {
    header("Location: ../vistas/citas_abogado.php?msg=cita_err");
}
$stmt_ins->close();
exit();
?>
