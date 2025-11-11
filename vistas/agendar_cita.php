<?php
session_start();
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado"){
    header("Location: ../vistas/login.php");
    exit();
}

include "../INC/conexion.php";

// Obtener lista de clientes
$clientes = $conexion->query("SELECT Id_cl, Nom_cl, App_cl, Apm_cl FROM cliente ORDER BY Nom_cl ASC");

// Datos del abogado desde la sesión (si aún no lo guardamos en sesión, lo tomamos desde la BD)
$nombreAbg = $_SESSION['usuario'];
$ab = $conexion->query("SELECT * FROM abogado WHERE Nom_abgd = '$nombreAbg'");
$abogado = $ab->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agendar Cita</title>
<link rel="stylesheet" href="../CSS/estilo_panel.css">
<style>
.form-box{
    background: white;
    padding: 25px;
    max-width: 600px;
    margin: auto;
    border-radius: 8px;
    border: 1px solid #dcd6c8;
}
label{ font-weight:bold; display:block; margin-top:12px;}
input, select{ width:100%; padding:8px; margin-top:5px;}
button{ margin-top:15px; padding:10px; background:#7a5e42; color:white; border:none; border-radius:6px;}
button:hover{ background:#604831; }
</style>
</head>

<body>

<div class="header">
    <span>⚖️ García & Asociados</span>
    <span>Abogado: <?php echo $abogado['Nom_abgd']." ".$abogado['App_abgd']; ?></span>
</div>

<div class="sidebar">
    <a href="panel_abogado.php">Inicio</a>
    <a href="citas_abogado.php">Mis Citas</a>
    <a href="registro_cliente.php">Registrar Cliente</a>
    <a href="registro_abogado.php">Registrar Abogado</a>
    <a href="../PHP/logout.php">Cerrar Sesión</a>
</div>

<div class="content">
    <h1 class="title">Agendar Nueva Cita</h1>

    <form action="../PHP/guardar_cita.php" method="POST" class="form-box">

        <label>Cliente:</label>
        <select name="Id_cl_ct" required>
            <option value="">Seleccione un cliente</option>
            <?php while($cl = $clientes->fetch_assoc()){ ?>
                <option value="<?php echo $cl['Id_cl']; ?>">
                    <?php echo $cl['Nom_cl']." ".$cl['App_cl']." ".$cl['Apm_cl']; ?>
                </option>
            <?php } ?>
        </select>

        <label>Fecha:</label>
        <input type="date" name="Da_ct" required>

        <label>Hora:</label>
<input type="time" name="Hra_ct" required>


        <input type="hidden" name="Ced_abgd_ct" value="<?php echo $abogado['Ced_abgd']; ?>">
        <input type="hidden" name="Nom_abgd_ct" value="<?php echo $abogado['Nom_abgd']; ?>">
        <input type="hidden" name="App_abgd_ct" value="<?php echo $abogado['App_abgd']; ?>">
        <input type="hidden" name="Apm_abgd_ct" value="<?php echo $abogado['Apm_abgd']; ?>">

        <button type="submit">Guardar Cita</button>
    </form>

</div>
</body>
</html>
