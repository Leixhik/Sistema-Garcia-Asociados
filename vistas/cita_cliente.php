<?php
session_start();

// Verificar que sea cliente
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != "cliente"){
    header("Location: ../VISTAS/login.php");
    exit();
}

include "../INC/conexion.php";

// Verificar que el ID del cliente esté en sesión
if (!isset($_SESSION['id_cl'])) {
    header("Location: ../VISTAS/login.php");
    exit();
}

$idCliente = $_SESSION['id_cl'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Citas</title>
<link rel="stylesheet" href="../CSS/estilo_panel.css">

<style>
.table-box{
    background: white;
    padding: 20px;
    border: 1px solid #dcd6c8;
    border-radius: 8px;
    max-width: 720px;
    margin: auto;
}
table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
table th, table td{
    padding: 10px;
    border-bottom: 1px solid #e5e0d8;
    text-align: center;
}
</style>

</head>

<body>

<div class="header">
    <span>⚖️ García & Asociados</span>
    <span>Cliente: <?php echo $_SESSION['Nom_cl']." ".$_SESSION['App_cl']; ?></span>
</div>

<div class="header-slogan">Compromiso, ética y ley.</div>

<div class="sidebar">
    <a href="panel_cliente.php">Inicio</a>
    <a href="cita_cliente.php">Mis Citas</a>
    <a href="../PHP/logout.php">Cerrar Sesión</a>
</div>

<div class="content">
    <h1 class="title">Mis Citas</h1>

    <div class="table-box">

        <?php
        $sql_citas = "SELECT Hra_ct, Da_ct, Nom_abgd_ct, App_abgd_ct, Apm_abgd_ct 
                      FROM cita 
                      WHERE Id_cl_ct = $idCliente";

        $resultado_citas = $conexion->query($sql_citas);

        if(!$resultado_citas){
            die("Error consultando citas: " . $conexion->error);
        }
        ?>

        <table>
            <tr>
                <th>Día</th>
                <th>Hora</th>
                <th>Abogado</th>
            </tr>

            <?php if($resultado_citas->num_rows == 0){ ?>
                <tr>
                    <td colspan="3">No tienes citas registradas aún.</td>
                </tr>
            <?php } else { ?>
                <?php while($cita = $resultado_citas->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $cita['Da_ct']; ?></td>
                    <td><?php echo $cita['Hra_ct']; ?></td>
                    <td><?php echo $cita['Nom_abgd_ct']." ".$cita['App_abgd_ct']." ".$cita['Apm_abgd_ct']; ?></td>
                </tr>
                <?php } ?>
            <?php } ?>

        </table>

    </div>
</div>

</body>
</html>
