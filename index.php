<?php
session_start();

if(!isset($_SESSION['rol'])){
    header("Location: VISTAS/login.php");
    exit();
}

if($_SESSION['rol'] == "abogado"){
    header("Location: VISTAS/panel_abogado.php");
    exit();
}

if($_SESSION['rol'] == "cliente"){
    header("Location: VISTAS/panel_cliente.php");
    exit();
}
