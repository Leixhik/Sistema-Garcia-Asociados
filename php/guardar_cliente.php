<?php
include "../INC/conexion.php";

$nombre = $_POST['nombre'];
$ap_pat = $_POST['ap_pat'];
$ap_mat = $_POST['ap_mat'];
$correo = $_POST['correo'];
$password = $_POST['password'];

$sql = "INSERT INTO cliente (Nom_cl, App_cl, Apm_cl, cp_cl, rf_cl, tel_cl, Cor_cl, Dir_cli, Con_cli)
VALUES ('$nombre','$ap_pat','$ap_mat','00000','N/D','0000000000','$correo','Sin registro','$password')";

if($conexion->query($sql)){
    header("Location: ../VISTAS/registro_cliente.php?ok=1");
} else {
    echo "❌ Error: " . $conexion->error;
}
