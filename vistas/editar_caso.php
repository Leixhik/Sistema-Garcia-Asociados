<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'abogado') {
    header('Location: ../vistas/login.php');
    exit();
}

require_once __DIR__ . '/../inc/conexion.php';

$idAbogado = $_SESSION['Id_abgd'] ?? 0;
$esAdmin   = $_SESSION['es_admin'] ?? 0;

if (!isset($_GET['id'])) {
    header("Location: casos.php?msg=cs_err");
    exit();
}

$idCaso = (int)$_GET['id'];

/* ============================
   1. Validar que el caso exista
   ============================ */

if ($esAdmin == 1) {
    $sql = "SELECT * FROM casos WHERE Id_cs = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idCaso);
} else {
    // Abogado solo puede ver sus casos
    $sql = "SELECT * FROM casos WHERE Id_cs = ? AND Ced_abgd_ct = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $idCaso, $idAbogado);
}

$stmt->execute();
$caso = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$caso) {
    header("Location: casos.php?msg=cs_err");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Caso</title>
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
.form-group{ margin-bottom:12px; }
label{ font-weight:600; margin-bottom:4px; display:block; }
input,select,textarea{
  width:100%; padding:8px; border-radius:5px; border:1px solid #ccc;
}
textarea{ min-height:90px; resize:vertical; }
.btn-save{ background:#004aad;color:#fff;padding:10px 18px;border:none;border-radius:6px;cursor:pointer; }
.btn-save:hover{ background:#00337a; }
</style>
</head>
<body>

<?php include "../inc/header.php"; ?>
<?php include "../inc/sidebar.php"; ?>

<div class="content">
  <h1 class="title">Editar Caso</h1>

  <div class="form-box">
    <form action="../php/actualizar_caso.php" method="POST">

      <input type="hidden" name="id_caso" value="<?= $caso['Id_cs']; ?>">

      <div class="form-group">
        <label>Número de caso</label>
        <input type="text" name="no_caso" value="<?= htmlspecialchars($caso['No_cs']); ?>" required>
      </div>

      <div class="form-group">
        <label>Tipo de caso</label>
        <input type="text" name="tipo" value="<?= htmlspecialchars($caso['Tipo_cs']); ?>" required>
      </div>

      <div class="form-group">
        <label>Estado</label>
        <select name="estado">
          <option value="Abierto" <?= $caso['Estado_cs']=="Abierto"?"selected":""; ?>>Abierto</option>
          <option value="En proceso" <?= $caso['Estado_cs']=="En proceso"?"selected":""; ?>>En proceso</option>
          <option value="Cerrado" <?= $caso['Estado_cs']=="Cerrado"?"selected":""; ?>>Cerrado</option>
        </select>
      </div>

      <div class="form-group">
        <label>Descripción</label>
        <textarea name="desc" required><?= htmlspecialchars($caso['Desc_cs']); ?></textarea>
      </div>

      <div class="form-group">
        <label>Detalle interno</label>
        <textarea name="detalle"><?= htmlspecialchars($caso['Detalle_cs']); ?></textarea>
      </div>

      <button class="btn-save" type="submit"> Guardar cambios </button>

    </form>
  </div>

</div>
</body>
</html>
