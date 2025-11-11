<?php
require '../../include/db_conn.php';
page_protect();

/*
  Asumimos que la sesión trae estos campos:
    $_SESSION['user_data']  -> username (actual del admin)
    $_SESSION['full_name']  -> nombre completo (actual del admin)
*/
$current_username = $_SESSION['user_data'] ?? '';
$current_fullname = $_SESSION['full_name'] ?? '';

/* ------- Guardar cambios ------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  $new_username = trim($_POST['login_id'] ?? '');
  $new_fullname = trim($_POST['full_name'] ?? '');

  if ($new_username === '' || $new_fullname === '') {
    echo "<script>alert('Completa todos los campos');</script>";
  } else {
    // Consulta preparada: actualiza sobre el username actual de sesión
    $stmt = $con->prepare("UPDATE admin SET username = ?, Full_name = ? WHERE username = ? LIMIT 1");
    if (!$stmt) {
      echo "<script>alert('Error preparando consulta: ".addslashes($con->error)."');</script>";
    } else {
      $stmt->bind_param('sss', $new_username, $new_fullname, $current_username);
      if ($stmt->execute() && $stmt->affected_rows >= 0) {
        // Forzamos re-login para refrescar sesión con los valores nuevos
        echo "<script>alert('Perfil actualizado. Inicia sesión nuevamente.');window.location='logout.php';</script>";
        exit;
      } else {
        echo "<script>alert('No se pudo actualizar el perfil.');</script>";
      }
      $stmt->close();
    }
  }
}

/* Helpers de salida segura */
function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Admin</title>

  <!-- Base -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">

  <!-- Estilos de esta página -->
  <link rel="stylesheet" href="../../css/admin_profile.css">

  <script src="../../js/Script.js"></script>

  <style>
    .page-container .sidebar-menu #main-menu li#adminprofile > a{
      background-color:#2b303a;color:#fff;
    }
  </style>
</head>

<body class="page-body page-fade" onload="collapseSidebar()">
<div class="page-container sidebar-collapsed" id="navbarcollapse">

  <div class="sidebar-menu">
    <header class="logo-env">
      <div class="logo">
        <a href="main.php">
          <img src="../../images/logo2.png" alt="" width="192" height="80" />
        </a>
      </div>
      <div class="sidebar-collapse" onclick="collapseSidebar()">
        <a href="#" class="sidebar-collapse-icon with-animation"><i class="entypo-menu"></i></a>
      </div>
    </header>
    <?php include('nav.php'); ?>
  </div>

  <div class="main-content ff-adminprofile">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenido <?php echo h($current_fullname); ?></li>
          <li><a href="logout.php" class="pill-orange">Cerrar Sesión <i class="entypo-logout right"></i></a></li>
        </ul>
      </div>
    </div>

    <h3 class="ff-title">Editar Perfil de Usuario</h3>
    <div class="ff-underline"></div>
    <p class="ff-hint">Deberás iniciar sesión nuevamente después de actualizar tu perfil.</p>

    <div class="ff-card">
      <div class="ff-card__head">Cambiar Perfil</div>
      <form method="post" class="ff-form" action="">
        <table class="ff-form-table" width="100%">
          <tr>
            <td>Usuario (ID)</td>
            <td>
              <input class="ff-input" type="text" name="login_id" value="<?php echo h($current_username); ?>" required>
            </td>
          </tr>
          <tr>
            <td>Nombre Completo</td>
            <td>
              <input class="ff-input" type="text" name="full_name" value="<?php echo h($current_fullname); ?>" maxlength="50" required>
            </td>
          </tr>
          <tr>
            <td>Contraseña</td>
            <td>
              <span class="ff-pass-mask">*********</span>
              <a href="change_pwd.php" class="ff-btn ff-btn--warning">Cambiar Contraseña</a>
            </td>
          </tr>
          <tr>
            <td></td>
            <td class="ff-actions">
              <button type="submit" name="submit" class="ff-btn ff-btn--primary">Enviar</button>
              <button type="reset" class="ff-btn ff-btn--muted">Borrar</button>
            </td>
          </tr>
        </table>
      </form>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>
</body>
</html>
