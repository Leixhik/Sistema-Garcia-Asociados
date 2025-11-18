<?php
session_start();
if(!isset($_SESSION['rol']) || $_SESSION['rol'] != "abogado"){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel del Abogado</title>
<link rel="stylesheet" href="../CSS/estilo_panel.css">
</head>

<body>

<?php include "../inc/header.php"; ?>
<?php include "../inc/sidebar.php"; ?>


<div class="content">
    <h1 class="title">Panel Principal</h1>
    <p>Seleccione una opción del menú para continuar.</p>
    

</div>

</body>
</html>
