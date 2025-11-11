<?php
session_start();
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado"){
    header("Location: ../vistas/login.php");
    exit();
}

include "../INC/conexion.php";

// AHORA usamos la cédula directamente
$cedula = $_SESSION['Ced_abgd'];

// Consultar citas del abogado
$sql = "SELECT * FROM cita WHERE Ced_abgd_ct = $cedula ORDER BY Da_ct, Hra_ct";
$citas = $conexion->query($sql);

if(!$citas){
    die("Error al consultar citas: " . $conexion->error);
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Citas - Abogado</title>
<link rel="stylesheet" href="../CSS/estilo_panel.css">
<style>
.table-box{background:white;padding:20px;border:1px solid #dcd6c8;border-radius:8px;max-width:800px;margin:auto;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
table th, table td{padding:10px;border-bottom:1px solid #e5e0d8;text-align:center;}
</style>
</head>

<body>

<div class="header">
    <span>⚖️ García & Asociados</span>
    <span>Abogado: <?php echo $_SESSION['Nom_abgd']." ".$_SESSION['App_abgd']; ?></span>

</div>

<div class="sidebar">
    <a href="panel_abogado.php">Inicio</a>
    <a href="citas_abogado.php">Mis Citas</a>
    <a href="agendar_cita.php">Agendar Cita</a>
    <a href="../PHP/logout.php">Cerrar Sesión</a>
</div>

<div class="content">
    <h1 class="title">Mis Citas</h1>

    <div class="table-box">
        <table>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Cliente</th>
            </tr>

            <?php if($citas->num_rows == 0){ ?>
                <tr><td colspan="3">No hay citas registradas.</td></tr>
            <?php } else { ?>
                <?php while($c = $citas->fetch_assoc()){ ?>
                    <tr>
                        <td><?php echo $c['Da_ct']; ?></td>
                        <td><?php echo $c['Hra_ct']; ?></td>
                        <td><?php echo $c['Nom_cl_ct']." ".$c['App_cl_ct']." ".$c['Apm_cl_ct']; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>
