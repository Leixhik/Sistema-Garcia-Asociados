<?php
session_start();
include "../inc/conexion.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado" || (int)$_SESSION['es_admin'] !== 1) {
    header("Location: ../vistas/login.php");
    exit();
}

if (empty($_POST['id_abogado']) || empty($_POST['id_especialidad'])) {
    header("Location: ../vistas/detalleae.php?msg=faltan");
    exit();
}

$id_abg = (int)$_POST['id_abogado'];
$id_esp = (int)$_POST['id_especialidad'];

$sql = "INSERT INTO detalleae (Id_abgd_da, Id_esp_da) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_abg, $id_esp);

if ($stmt->execute()) {
    header("Location: ../vistas/detalleae.php?msg=ok");
} else {
    header("Location: ../vistas/detalleae.php?msg=error");
}
$stmt->close();
exit();
?>
