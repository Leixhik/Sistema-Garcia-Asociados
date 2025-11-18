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

// 🔹 Traer lista de clientes para el select
$sqlCli = "SELECT Id_cl, Nom_cl, App_cl, Apm_cl FROM cliente ORDER BY Nom_cl ASC";
$clientes = $conexion->query($sqlCli);
if (!$clientes) {
    die("Error SQL (clientes): " . $conexion->error);
}

// 🔹 Si es admin, también puede elegir a qué abogado asignar el caso
$abogados = null;
if ($esAdmin === 1) {
    $sqlAbg = "SELECT Id_abgd, Nom_abgd, App_abgd, Apm_abgd FROM abogado ORDER BY Nom_abgd ASC";
    $abogados = $conexion->query($sqlAbg);
    if (!$abogados) {
        die("Error SQL (abogados): " . $conexion->error);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Caso - García & Asociados</title>
<link rel="stylesheet" href="../css/estilo_panel.css">
<style>
.form-box{
  background:#fff;
  padding:20px;
  border:1px solid #dcd6c8;
  border-radius:8px;
  max-width:700px;
  margin:auto;
}
.form-box h2{
  font-family:'Lora',serif;
  font-size:24px;
  margin-bottom:15px;
}
.form-group{
  margin-bottom:12px;
}
.form-group label{
  display:block;
  font-weight:600;
  margin-bottom:4px;
}
.form-group input,
.form-group select,
.form-group textarea{
  width:100%;
  padding:8px;
  border-radius:5px;
  border:1px solid #ccc;
  font-size:14px;
}
.form-group textarea{
  min-height:80px;
  resize:vertical;
}
.btn-guardar{
  background:#004aad;
  color:#fff;
  padding:9px 16px;
  border:none;
  border-radius:5px;
  font-size:14px;
  cursor:pointer;
}
.btn-guardar:hover{
  background:#00337a;
}
</style>
</head>
<body>

<?php include "../inc/header.php"; ?>
<?php include "../inc/sidebar.php"; ?>

<div class="content">
  <h1 class="title">Registrar nuevo caso</h1>

  <div class="form-box">
    <h2>Datos del caso</h2>

    <form action="../php/guardar_caso.php" method="POST">
      <!-- Cliente -->
      <div class="form-group">
        <label for="id_cliente">Cliente</label>
        <select name="id_cliente" id="id_cliente" required>
          <option value="">-- Selecciona un cliente --</option>
          <?php while($cl = $clientes->fetch_assoc()): ?>
            <option value="<?= $cl['Id_cl']; ?>">
              <?= htmlspecialchars($cl['Nom_cl']." ".$cl['App_cl']." ".$cl['Apm_cl']); ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Abogado asignado -->
      <?php if ($esAdmin === 1): ?>
        <div class="form-group">
          <label for="id_abogado">Abogado asignado</label>
          <select name="id_abogado" id="id_abogado" required>
            <option value="">-- Selecciona un abogado --</option>
            <?php while($abg = $abogados->fetch_assoc()): ?>
              <option value="<?= $abg['Id_abgd']; ?>">
                <?= htmlspecialchars($abg['Nom_abgd']." ".$abg['App_abgd']." ".$abg['Apm_abgd']); ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
      <?php else: ?>
        <!-- Si NO es admin, se asigna a sí mismo -->
        <input type="hidden" name="id_abogado" value="<?= $idAbogado; ?>">
        <div class="form-group">
          <label>Abogado asignado</label>
          <input type="text" value="<?= htmlspecialchars($_SESSION['Nom_abgd']." ".$_SESSION['App_abgd']." ".$_SESSION['Apm_abgd']); ?>" disabled>
        </div>
      <?php endif; ?>

      <!-- No. de caso -->
      <div class="form-group">
        <label for="no_caso">Número o nombre del caso</label>
        <input type="text" name="no_caso" id="no_caso" 
               placeholder="Ej. CS-001 / Juicio de pensión alimenticia" required>
      </div>

      <!-- Tipo de caso -->
      <div class="form-group">
        <label for="tipo">Tipo de caso</label>
        <input type="text" name="tipo" id="tipo" 
               placeholder="Ej. Civil, Mercantil, Familiar..." required>
      </div>

      <!-- Estado (opcional, default Abierto) -->
      <div class="form-group">
        <label for="estado">Estado del caso</label>
        <select name="estado" id="estado">
          <option value="Abierto" selected>Abierto</option>
          <option value="En proceso">En proceso</option>
          <option value="Cerrado">Cerrado</option>
        </select>
      </div>

      <!-- Descripción breve -->
      <div class="form-group">
        <label for="desc">Descripción breve</label>
        <textarea name="desc" id="desc" 
                  placeholder="Descripción corta del caso" required></textarea>
      </div>

      <!-- Detalle -->
      <div class="form-group">
        <label for="detalle">Detalle / notas internas</label>
        <textarea name="detalle" id="detalle" 
                  placeholder="Notas más amplias del caso (solo para el abogado)"></textarea>
      </div>

      <button type="submit" class="btn-guardar">Guardar caso</button>
    </form>
  </div>
</div>

</body>
</html>
