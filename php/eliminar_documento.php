<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abogado') {
    exit("Acceso no autorizado");
}

require_once __DIR__ . '/../inc/conexion.php';

if (!isset($_GET['id']) || !isset($_GET['caso'])) {
    exit("Parámetros inválidos");
}

$idDoc  = (int) $_GET['id'];
$idCaso = (int) $_GET['caso'];

/* Obtener documento */
$sql = "SELECT ruta_archivo FROM documentos_caso WHERE Id_doc = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idDoc);
$stmt->execute();
$doc = $stmt->get_result()->fetch_assoc();

if (!$doc) {
    header("Location: ../vistas/ver_caso.php?id=$idCaso&msg=doc_notfound");
    exit();
}

$ruta = $doc['ruta_archivo'];
$ruta = str_replace("../", "", $ruta); 
$path = __DIR__ . "/../" . $ruta;

/* Eliminar archivo */
if (file_exists($path)) {
    unlink($path);
}

/* Borrar BD */
$conexion->query("DELETE FROM documentos_caso WHERE Id_doc = $idDoc");

/* Actualizar fecha */
$conexion->query("UPDATE casos SET Fecha_act = NOW() WHERE Id_cs = $idCaso");

header("Location: ../vistas/ver_caso.php?id=$idCaso&msg=doc_deleted");
exit();
