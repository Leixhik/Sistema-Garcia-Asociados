<?php
if (!isset($_SESSION)) { session_start(); }

// Si no hay sesión, redirigir
if (!isset($_SESSION['rol'])) {
    header("Location: ../vistas/login.php");
    exit();
}

// Variables seguras
$usuarioNombre = $_SESSION['Nom_abgd'] ?? '';
$usuarioApp    = $_SESSION['App_abgd'] ?? '';
$esAdmin       = $_SESSION['es_admin'] ?? 0;
?>

<div class="header">
  <span>⚖️ García & Asociados</span>

  <span>
      <?php if ($esAdmin == 1): ?>
          Administrador:
      <?php else: ?>
          Abogado:
      <?php endif; ?>

      <?= htmlspecialchars($usuarioNombre . ' ' . $usuarioApp); ?>
  </span>
</div>
