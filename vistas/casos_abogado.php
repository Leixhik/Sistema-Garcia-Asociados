<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abogado') {
    header('Location: ../vistas/login.php');
    exit();
}

require_once __DIR__ . '/../inc/conexion.php';

$idAbogado = isset($_SESSION['Id_abgd']) ? (int)$_SESSION['Id_abgd'] : 0;
$esAdmin   = isset($_SESSION['es_admin']) ? (int)$_SESSION['es_admin'] : 0;

if ($idAbogado <= 0) {
    die("⚠️ Error: el ID del abogado no está definido en la sesión.");
}

/* ==============================
   1) CONSULTA DE CASOS
   - Admin: ve TODOS los casos
   - Abogado normal: sólo los suyos (Ced_abgd_ct = Id_abgd)
   ============================== */

if ($esAdmin === 1) {
    // Admin ve todos
    $sql = "SELECT Id_cs, No_cs, Desc_cs, Tipo_cs, Estado_cs,
                   Fecha_ini, Fecha_act,
                   Nom_cl_ct, App_cl_ct, Apm_cl_ct,
                   Nom_abgd_ct, App_abgd_ct, Apm_abgd_ct
            FROM casos
            ORDER BY Fecha_ini DESC";
    $casos = $conexion->query($sql);
    if (!$casos) {
        die("Error SQL (admin casos): " . $conexion->error);
    }
} else {
    // Abogado normal ve sólo los suyos
    $sql = "SELECT Id_cs, No_cs, Desc_cs, Tipo_cs, Estado_cs,
                   Fecha_ini, Fecha_act,
                   Nom_cl_ct, App_cl_ct, Apm_cl_ct,
                   Nom_abgd_ct, App_abgd_ct, Apm_abgd_ct
            FROM casos
            WHERE Ced_abgd_ct = ?
            ORDER BY Fecha_ini DESC";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        die("Error SQL (prepare casos): " . $conexion->error);
    }
    $stmt->bind_param("i", $idAbogado);
    $stmt->execute();
    $casos = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Casos - García & Asociados</title>
<link rel="stylesheet" href="../css/estilo_panel.css">
<style>
*{
  box-sizing:border-box;
  font-family:'Montserrat',sans-serif;
}

/* ======== LAYOUT ======== */
.content {
    margin-left: 240px;   /* espacio perfecto para sidebar */
    margin-top: 90px;     /* espacio para el header */
    padding: 25px;
}

/* ======== TABLA ======== */
.table-box{
  background:#fff;
  padding:20px;
  border:1px solid #dcd6c8;
  border-radius:8px;
  max-width:1100px;
  margin:auto;
  overflow-x:auto;
}

table{
  width:100%;
  border-collapse:collapse;
  min-width:950px;
}

th, td {
  padding:12px;
  border-bottom:1px solid #e5e0d8;
  text-align:center;
  font-size:14px;
}

th{
  background:#f5f1eb;
  font-weight:bold;
}

/* ======== BOTONES ACCIONES ======== */
td .acciones{
    display:flex;
    justify-content:center;
    gap:10px;
}

.btn-editar,
.btn-detalle{
    display:inline-block;
    padding:7px 12px;
    border-radius:5px;
    color:white;
    font-size:13px;
    text-decoration:none;
    white-space:nowrap;
}

/* Colores */
.btn-editar{ background:#004aad; }
.btn-editar:hover{ background:#00337a; }

.btn-detalle{ background:#6c757d; }
.btn-detalle:hover{ background:#565e64; }

/* ======== BADGES ======== */
.badge-estado{
  padding:4px 8px;
  border-radius:12px;
  font-size:12px;
  font-weight:bold;
}

.badge-abierto{ background:#d4edda; color:#155724; }
.badge-cerrado{ background:#f8d7da; color:#721c24; }
.badge-proceso{ background:#fff3cd; color:#856404; }

/* ======== RESPONSIVE ======== */
@media(max-width:900px){
  .content{ margin-left:0; margin-top:140px; }
  table{ min-width:700px; }
}

@media(max-width:600px){
  .content{ margin-top:160px; }
  td .acciones{ flex-direction:column; }
}
</style>

</head>
<body>

<?php include "../inc/header.php"; ?>
<?php include "../inc/sidebar.php"; ?>

<div class="content">
  <h1 class="title" style="margin-bottom:20px;">
  Casos <?php echo $esAdmin ? "(todos)" : "asignados a mí"; ?>
</h1>


  <?php if (isset($_GET['msg'])): ?>
    <?php
      $mensaje = "";
      $tipo = "info";
      switch ($_GET['msg']) {
        case 'cs_ok':
          $mensaje = "✅ Caso registrado correctamente.";
          $tipo = "success"; break;
        case 'cs_err':
          $mensaje = "❌ Error al registrar el caso.";
          $tipo = "error"; break;
        case 'cs_edit_ok':
          $mensaje = "✅ Caso actualizado correctamente.";
          $tipo = "success"; break;
        case 'cs_edit_err':
          $mensaje = "❌ Error al actualizar el caso.";
          $tipo = "error"; break;
      }
    ?>
    <?php if ($mensaje !== ""): ?>
      <div class="mensaje <?= $tipo ?>" id="mensaje-notificacion">
        <?= $mensaje ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <style>
  .mensaje{
    position:fixed;top:20px;left:50%;
    transform:translateX(-50%);
    padding:12px 18px;border-radius:8px;
    font-family:'Montserrat',sans-serif;
    font-weight:500;font-size:15px;color:#fff;
    animation:fadeIn .5s ease,fadeOut .5s ease 3s forwards;
    z-index:9999;box-shadow:0 2px 8px rgba(0,0,0,.15);
  }
  .mensaje.success{background:#28a745;}
  .mensaje.error{background:#dc3545;}
  .mensaje.info{background:#17a2b8;}
  @keyframes fadeIn{
    from{opacity:0;transform:translate(-50%,-20px);}
    to{opacity:1;transform:translate(-50%,0);}
  }
  @keyframes fadeOut{
    from{opacity:1;transform:translate(-50%,0);}
    to{opacity:0;transform:translate(-50%,-20px);}
  }
  </style>
  <script>
    setTimeout(() => {
      const msg = document.getElementById('mensaje-notificacion');
      if (msg) msg.remove();
    }, 3500);
  </script>
  

  <!-- 🔹 Botón para ir a registrar un nuevo caso -->
  <div style="margin-top: 10px; margin-bottom:15px;">
    <a href="registrar_caso.php" 
       style="background:#004aad;color:white;padding:8px 14px;border-radius:5px;
              text-decoration:none;font-size:14px;">
      ➕ Registrar nuevo caso
    </a>
  </div>

  <div class="table-box">
    <table>
      <tr>
        <th>No. Caso</th>
        <th>Descripción</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Cliente</th>
        <th>Fecha inicio</th>
        <th>Última actualización</th>
        <th>Acciones</th>
      </tr>

      <?php if ($casos->num_rows === 0): ?>
        <tr><td colspan="8">No hay casos registrados.</td></tr>
      <?php else: ?>
        <?php while($cs = $casos->fetch_assoc()): ?>
          <?php
            $estado = $cs['Estado_cs'] ?? 'Abierto';
            $badgeClass = 'badge-abierto';
            if (strcasecmp($estado,'Cerrado') === 0) $badgeClass = 'badge-cerrado';
            elseif (strcasecmp($estado,'En proceso') === 0 || strcasecmp($estado,'Proceso') === 0) $badgeClass = 'badge-proceso';
          ?>
          <tr>
            <td><?= htmlspecialchars($cs['No_cs']); ?></td>
            <td><?= htmlspecialchars($cs['Desc_cs']); ?></td>
            <td><?= htmlspecialchars($cs['Tipo_cs'] ?? ''); ?></td>
            <td><span class="badge-estado <?= $badgeClass; ?>"><?= htmlspecialchars($estado); ?></span></td>
            <td><?= htmlspecialchars($cs['Nom_cl_ct']." ".$cs['App_cl_ct']." ".$cs['Apm_cl_ct']); ?></td>
            <td><?= htmlspecialchars($cs['Fecha_ini']); ?></td>
            <td><?= htmlspecialchars($cs['Fecha_act'] ?? '-'); ?></td>
            <td>
              <div class="acciones">
          
                <a href="editar_caso.php?id=<?= $cs['Id_cs']; ?>" class="btn-editar">Editar</a>
                <a href="ver_caso.php?id=<?= $cs['Id_cs']; ?>" class="btn-detalle">Detalle</a>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php endif; ?>
    </table>
  </div>
</div>

</body>
</html>
