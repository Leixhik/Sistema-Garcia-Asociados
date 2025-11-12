<?php
session_start();

// Verificar que haya sesión y sea abogado
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado"){
    header("Location: login.php");
    exit();
}

// Solo los administradores pueden acceder
if (!isset($_SESSION['es_admin']) || (int)$_SESSION['es_admin'] !== 1) {
    echo "<script>alert('⚠️ No tienes permiso para registrar abogados.'); window.location='panel_abogado.php';</script>";
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Abogado</title>
<link rel="stylesheet" href="../CSS/estilo_panel.css">
<style>
.form-box{
    background: white;
    padding: 30px;
    max-width: 550px;
    margin: 40px auto;
    border-radius: 8px;
    border: 1px solid #dcd6c8;
    font-family: 'Montserrat', sans-serif;
}
.form-box h2{
    font-family: 'Lora', serif;
    font-size: 26px;
    color: #333;
    text-align: center;
    margin-bottom: 25px;
}
.form-box input{
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    margin-bottom: 18px;
    border: 1px solid #b9b3a8;
    border-radius: 4px;
}
button{
    background: #C6A667;
    color: white;
    border: none;
    padding: 12px 20px;
    width: 100%;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}
button:hover{
    background: #b18f4f;
}
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
    <a href="registro_cliente.php">Registrar Cliente</a>

    <?php if (isset($_SESSION['es_admin']) && (int)$_SESSION['es_admin'] === 1): ?>
        <a href="registro_abogado.php">Registrar Abogado</a>
    <?php endif; ?>

    <a href="../PHP/logout.php">Cerrar Sesión</a>
</div>


<div class="content">
    <div class="form-box">
        <h2>Registrar Nuevo Abogado</h2>

        <form action="../php/guardar_abogado.php" method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="ap_pat" placeholder="Apellido Paterno" required>
            <input type="text" name="ap_mat" placeholder="Apellido Materno" required>
            <input type="text" name="dir" placeholder="Dirección" required>
            <input type="text" name="cel" placeholder="Celular" required>
            <input type="text" name="tel" placeholder="Teléfono fijo" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
            <button type="submit">Guardar Abogado</button>
        </form>
    </div>
</div>

</body>
</html>
