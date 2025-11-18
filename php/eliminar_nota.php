<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abogado') {
    exit("Acceso denegado");
}

require_once __DIR__ . '/../inc/conexion.php';

if (!isset($_GET['id']) || !isset($_GET['caso'])) exit("Parámetros inválidos");

$idNota = (int) $_GET['id'];
$idCaso = (int) $_GET['caso'];

$sql = "DELETE FROM notas_caso WHERE Id_nota = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idNota);
$stmt->execute();

/* Actualizar fecha */
$conexion->query("UPDATE casos SET Fecha_act = NOW() WHERE Id_cs = $idCaso");

header("Location: ../vistas/ver_caso.php?id=$idCaso&msg=nota_deleted");
exit();
