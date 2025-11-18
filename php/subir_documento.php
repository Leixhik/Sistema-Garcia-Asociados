<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abogado') {
    header("Location: ../vistas/login.php");
    exit();
}

require_once __DIR__ . '/../inc/conexion.php';

if (!isset($_POST['id_caso'])) {
    header("Location: ../vistas/casos_abogado.php");
    exit();
}

$idCaso = (int)$_POST['id_caso'];

$dir = __DIR__ . "/../archivos_casos/";
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== 0) {
    header("Location: ../vistas/ver_caso.php?id=$idCaso&msg=doc_err");
    exit();
}

$nombreOriginal = basename($_FILES['archivo']['name']);
$ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

$permitidos = ["pdf", "jpg", "jpeg", "png"];

if (!in_array($ext, $permitidos)) {
    header("Location: ../vistas/ver_caso.php?id=$idCaso&msg=tipo_err");
    exit();
}

/* Generar nombre único */
$nombreFinal = "doc_" . uniqid() . "." . $ext;
$rutaDestino = $dir . $nombreFinal;

/* Subir archivo */
if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino)) {
    header("Location: ../vistas/ver_caso.php?id=$idCaso&msg=subida_err");
    exit();
}

/* Registrar en BD */
$sql = "INSERT INTO documentos_caso (Id_cs, nombre_archivo, ruta_archivo, tipo, fecha_subida)
        VALUES (?, ?, ?, ?, NOW())";

$stmt = $conexion->prepare($sql);
$tipoArchivo = $ext;

$rutaRelativa = "../archivos_casos/" . $nombreFinal;  // para usar en <a href>

$stmt->bind_param("isss", $idCaso, $nombreOriginal, $rutaRelativa, $tipoArchivo);
$stmt->execute();
$stmt->close();

/* Actualizar fecha */
$conexion->query("UPDATE casos SET Fecha_act = NOW() WHERE Id_cs = $idCaso");

header("Location: ../vistas/ver_caso.php?id=$idCaso&msg=doc_ok");
exit();
