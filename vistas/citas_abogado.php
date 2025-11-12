<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abogado') {
    header('Location: ../vistas/login.php'); 
    exit;
}

require_once __DIR__ . '/../inc/conexion.php';

// ✅ Recuperamos el ID real del abogado (ahora guardado correctamente)
$idAbogado = isset($_SESSION['Id_abgd']) ? (int)$_SESSION['Id_abgd'] : 0;
if ($idAbogado <= 0) { 
    die("⚠️ Error: el ID del abogado no está definido en la sesión."); 
}

// ✅ Consulta segura
$sql = "SELECT Id_ct, Da_ct, Hra_ct, Nom_cl_ct, App_cl_ct, Apm_cl_ct
        FROM cita
        WHERE abgd_id_ct = ?
        ORDER BY Da_ct, Hra_ct";

$stmt = $conexion->prepare($sql);
if (!$stmt) { 
    die("Error SQL (prepare): " . $conexion->error); 
}

$stmt->bind_param('i', $idAbogado);
$stmt->execute();
$citas = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mis Citas - Abogado</title>
<link rel="stylesheet" href="../css/estilo_panel.css">
<style>
.table-box{background:#fff;padding:20px;border:1px solid #dcd6c8;border-radius:8px;max-width:800px;margin:auto;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th,td{padding:10px;border-bottom:1px solid #e5e0d8;text-align:center;}
</style>
</head>
<body>
<div class="header">
  <span>⚖️ García &amp; Asociados</span>
  <span>Abogado: <?php echo htmlspecialchars($_SESSION['Nom_abgd'].' '.$_SESSION['App_abgd']); ?></span>
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
  <h1 class="title">Mis Citas</h1>
  <?php if (isset($_GET['msg'])): ?>
  <?php
    $mensaje = "";
    $tipo = "info";

    switch ($_GET['msg']) {
      case 'ok':
        $mensaje = "Cita eliminada correctamente.";
        $tipo = "success";
        break;
      case 'notfound':
        $mensaje = "⚠️ No se encontró la cita o no pertenece a este abogado.";
        $tipo = "warning";
        break;
      case 'error_sql':
        $mensaje = "❌ Error interno al eliminar la cita.";
        $tipo = "error";
        break;
      case 'error_id':
        $mensaje = "⚠️ ID de cita no recibido.";
        $tipo = "warning";
        break;
    }
  ?>
  <div class="mensaje <?= $tipo ?>" id="mensaje-notificacion">
    <?= $mensaje ?>
  </div>
<?php endif; ?>

<style>
.mensaje {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 12px 18px;
  border-radius: 8px;
  font-family: 'Montserrat', sans-serif;
  font-weight: 500;
  font-size: 15px;
  color: white;
  animation: fadeIn 0.5s ease, fadeOut 0.5s ease 3s forwards;
  z-index: 9999;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.mensaje.success { background-color: #28a745; }  
.mensaje.warning { background-color: #ffc107; color: #212529; }
.mensaje.error   { background-color: #dc3545; }  
@keyframes fadeIn {
  from { opacity: 0; transform: translate(-50%, -20px); }
  to { opacity: 1; transform: translate(-50%, 0); }
}
@keyframes fadeOut {
  from { opacity: 1; transform: translate(-50%, 0); }
  to { opacity: 0; transform: translate(-50%, -20px); }
}
</style>

<script>
  // Ocultar el mensaje después de 3.5 segundos
  setTimeout(() => {
    const msg = document.getElementById('mensaje-notificacion');
    if (msg) msg.remove();
  }, 3500);
</script>

  <div class="table-box">
  <table>
    <tr>
      <th>Fecha</th>
      <th>Hora</th>
      <th>Cliente</th>
      <th>Acciones</th>
    </tr>

    <?php if ($citas->num_rows === 0): ?>
      <tr><td colspan="4">No hay citas registradas.</td></tr>
    <?php else: ?>
      <?php while($c = $citas->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($c['Da_ct']); ?></td>
          <td><?php echo htmlspecialchars($c['Hra_ct']); ?></td>
          <td><?php echo htmlspecialchars($c['Nom_cl_ct'].' '.$c['App_cl_ct'].' '.$c['Apm_cl_ct']); ?></td>
          <td>
            <form action="../php/eliminar_cita.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta cita?');">
              <input type="hidden" name="id_cita" value="<?php echo $c['Id_ct']; ?>">
              <button type="submit" style="background:#c0392b;color:white;border:none;padding:6px 10px;border-radius:4px;cursor:pointer;">
                🗑 Eliminar
              </button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php endif; ?>
  </table>
</div>

</div>
</body>
</html>
