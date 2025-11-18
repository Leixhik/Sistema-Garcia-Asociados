<?php
session_start();
require_once __DIR__ . '/../inc/conexion.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abogado') {
    header('Location: ../vistas/login.php');
    exit();
}

$idAbogadoSesion = isset($_SESSION['Id_abgd']) ? (int)$_SESSION['Id_abgd'] : 0;
$esAdmin         = isset($_SESSION['es_admin']) ? (int)$_SESSION['es_admin'] : 0;

if ($idAbogadoSesion <= 0) {
    header('Location: ../vistas/login.php');
    exit();
}

/* ======= Validar datos del formulario ======= */
if (
    empty($_POST['id_cliente']) ||
    empty($_POST['id_abogado']) ||
    empty($_POST['no_caso'])   ||
    empty($_POST['tipo'])      ||
    empty($_POST['desc'])
) {
    header('Location: ../vistas/casos_abogado.php?msg=cs_ok');

    exit();
}

$idCliente  = (int)$_POST['id_cliente'];
$idAbogado  = (int)$_POST['id_abogado'];
$no_caso    = trim($_POST['no_caso']);
$tipo       = trim($_POST['tipo']);
$estado     = isset($_POST['estado']) ? trim($_POST['estado']) : 'Abierto';
$desc       = trim($_POST['desc']);
$detalle    = isset($_POST['detalle']) ? trim($_POST['detalle']) : null;

/* 
   Si NO es admin, por seguridad, el id_abogado SIEMPRE debe ser el suyo.
*/
if ($esAdmin !== 1 && $idAbogado !== $idAbogadoSesion) {
    $idAbogado = $idAbogadoSesion;
}

/* ======= Traer datos del cliente ======= */
$sqlCli = "SELECT Nom_cl, App_cl, Apm_cl FROM cliente WHERE Id_cl = ? LIMIT 1";
$stmtCli = $conexion->prepare($sqlCli);
if (!$stmtCli) {
    header('Location: ../vistas/casos.php?msg=cs_err');
    exit();
}
$stmtCli->bind_param("i", $idCliente);
$stmtCli->execute();
$resCli = $stmtCli->get_result();
$cli = $resCli->fetch_assoc();
$stmtCli->close();

if (!$cli) {
    header('Location: ../vistas/casos.php?msg=cs_err');
    exit();
}

/* ======= Traer datos del abogado ======= */
$sqlAbg = "SELECT Nom_abgd, App_abgd, Apm_abgd FROM abogado WHERE Id_abgd = ? LIMIT 1";
$stmtAbg = $conexion->prepare($sqlAbg);
if (!$stmtAbg) {
    header('Location: ../vistas/casos.php?msg=cs_err');
    exit();
}
$stmtAbg->bind_param("i", $idAbogado);
$stmtAbg->execute();
$resAbg = $stmtAbg->get_result();
$abg = $resAbg->fetch_assoc();
$stmtAbg->close();

if (!$abg) {
    header('Location: ../vistas/casos.php?msg=cs_err');
    exit();
}

/* ======= Insertar en tabla casos ======= */
/*
   Campos de la tabla casos:

   Id_cs (AI)
   No_cs
   Desc_cs
   Tipo_cs
   Estado_cs
   Fecha_ini   (tiene default curdate())
   Fecha_act   (NULL)
   Detalle_cs
   Id_cl_ct
   Nom_cl_ct
   App_cl_ct
   Apm_cl_ct
   Ced_abgd_ct
   Nom_abgd_ct
   App_abgd_ct
   Apm_abgd_ct
*/

$sqlIns = "INSERT INTO casos (
    No_cs, Desc_cs, Tipo_cs, Estado_cs,
    Detalle_cs,
    Id_cl_ct, Nom_cl_ct, App_cl_ct, Apm_cl_ct,
    Ced_abgd_ct, Nom_abgd_ct, App_abgd_ct, Apm_abgd_ct
) VALUES (?,?,?,?,?,
          ?,?,?,?,
          ?,?,?,?)";

$stmtIns = $conexion->prepare($sqlIns);
if (!$stmtIns) {
    header('Location: ../vistas/casos.php?msg=cs_err');
    exit();
}

$stmtIns->bind_param(
    "sssssssisssss",
    $no_caso,
    $desc,
    $tipo,
    $estado,
    $detalle,
    $idCliente,
    $cli['Nom_cl'],
    $cli['App_cl'],
    $cli['Apm_cl'],
    $idAbogado,
    $abg['Nom_abgd'],
    $abg['App_abgd'],
    $abg['Apm_abgd']
);

if ($stmtIns->execute()) {
    header('Location: ../vistas/casos_abogado.php?msg=cs_ok');
} else {
    header('Location: ../vistas/casos_abogado.php?msg=cs_err');
}
$stmtIns->close();
exit();
