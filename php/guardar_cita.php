<?php
include "../INC/conexion.php";
session_start();

$Id_cl_ct   = $_POST['Id_cl_ct'];
$Da_ct      = $_POST['Da_ct'];
$Hra_ct     = $_POST['Hra_ct'];
$Ced_abgd_ct = $_POST['Ced_abgd_ct'];
$Nom_abgd_ct = $_POST['Nom_abgd_ct'];
$App_abgd_ct = $_POST['App_abgd_ct'];
$Apm_abgd_ct = $_POST['Apm_abgd_ct'];

// Obtener datos del cliente
$cl = $conexion->query("SELECT Nom_cl, App_cl, Apm_cl FROM cliente WHERE Id_cl=$Id_cl_ct");
$cliente = $cl->fetch_assoc();

$Nom_cl_ct = $cliente['Nom_cl'];
$App_cl_ct = $cliente['App_cl'];
$Apm_cl_ct = $cliente['Apm_cl'];

$sql = "INSERT INTO cita (Hra_ct, Da_ct, Id_cl_ct, Nom_cl_ct, App_cl_ct, Apm_cl_ct, Ced_abgd_ct, Nom_abgd_ct, App_abgd_ct, Apm_abgd_ct)
VALUES ('$Hra_ct', '$Da_ct', $Id_cl_ct, '$Nom_cl_ct', '$App_cl_ct', '$Apm_cl_ct', $Ced_abgd_ct, '$Nom_abgd_ct', '$App_abgd_ct', '$Apm_abgd_ct')";

$conexion->query($sql);

header("Location: ../vistas/cita_cliente.php");
exit();
