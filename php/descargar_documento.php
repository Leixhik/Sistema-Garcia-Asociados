<?php
session_start();
if (!isset($_SESSION['rol'])) {
    exit("Acceso denegado");
}

require_once __DIR__ . '/../inc/conexion.php';

if (!isset($_GET['id'])) exit("ID inválido");

$idDoc = (int) $_GET['id'];

$sql = "SELECT nombre_archivo, ruta_archivo FROM documentos_caso WHERE Id_doc = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idDoc);
$stmt->execute();
$doc = $stmt->get_result()->fetch_assoc();

if (!$doc) exit("Documento no encontrado");

$ruta = $doc['ruta_archivo'];
$ruta = str_replace("../", "", $ruta);

$path = __DIR__ . "/../" . $ruta;

/* Forzar descarga */
header("Content-Disposition: attachment; filename=\"" . $doc['nombre_archivo'] . "\"");
header("Content-Type: application/octet-stream");
readfile($path);
exit();
