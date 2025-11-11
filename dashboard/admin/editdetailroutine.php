<?php
require '../../include/db_conn.php';
page_protect();

/* ===== Validación y carga segura ===== */
if (!isset($_GET['id']) || $_GET['id'] === '') {
  echo "<script>alert('Falta el ID de la rutina');window.location='editroutine.php';</script>";
  exit;
}
$tid = trim($_GET['id']);

/* Consulta preparada */
$stmt = $con->prepare("SELECT tid, tname, day1, day2, day3, day4, day5, day6 FROM timetable WHERE tid = ? LIMIT 1");
if (!$stmt) {
  echo "<script>alert('Error preparando consulta');window.location='editroutine.php';</script>";
  exit;
}
$stmt->bind_param('s', $tid);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
  echo "<script>alert('Rutina no encontrada');window.location='editroutine.php';</script>";
  exit;
}
$row = $res->fetch_assoc();
$stmt->close();

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Detalle de Rutina</title>

  <!-- CSS base -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">
  <!-- CSS de esta vista -->
  <link rel="stylesheet" href="../../css/edit_routine.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js"></script>

  <style>
    .page-container .sidebar-menu #main-menu li#routinehassubopen > a{
      background:#2b303a;color:#fff;
    }
  </style>
</head>
<body class="page-body page-fade" onload="collapseSidebar()">
<div class="page-container sidebar-collapsed" id="navbarcollapse">

  <div class="sidebar-menu">
    <header class="logo-env">
      <div class="logo">
        <a href="main.php"><img src="../../images/logo2.png" alt="" width="192" height="80"></a>
      </div>
      <div class="sidebar-collapse" onclick="collapseSidebar()">
        <a href="#" class="sidebar-collapse-icon with-animation"><i class="entypo-menu"></i></a>
      </div>
    </header>
    <?php include('nav.php'); ?>
  </div>

  <div class="main-content ff-edit-routine">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenid@ <?php echo h($_SESSION['full_name'] ?? ''); ?></li>
          <li><a class="ff-pill" href="logout.php">Cerrar Sesión <i class="entypo-logout right"></i></a></li>
        </ul>
      </div>
    </div>

    <div class="ff-section">
      <h3 class="ff-page-title">Detalle de Rutina</h3>
      <div class="ff-page-underline"></div>
    </div>

    <div class="ff-card ff-card--narrow">
      <div class="ff-card__header">Editar Rutina</div>
      <div class="ff-card__body">
        <form method="post" action="updateroutine.php" class="ff-form">
          <input type="hidden" name="tid" value="<?php echo h($row['tid']); ?>">

          <div class="ff-row">
            <div class="ff-col ff-col--full">
              <label class="ff-label">Nombre de Rutina</label>
              <!-- OJO: usar el nombre que espera tu updateroutine.php -->
              <input class="ff-input" type="text" name="rname" required value="<?php echo h($row['tname']); ?>">
            </div>
          </div>

          <div class="ff-grid-2">
            <div class="ff-field">
              <label class="ff-label">Día 1</label>
              <textarea class="ff-textarea" name="day1" rows="3"><?php echo h($row['day1']); ?></textarea>
            </div>
            <div class="ff-field">
              <label class="ff-label">Día 2</label>
              <textarea class="ff-textarea" name="day2" rows="3"><?php echo h($row['day2']); ?></textarea>
            </div>
            <div class="ff-field">
              <label class="ff-label">Día 3</label>
              <textarea class="ff-textarea" name="day3" rows="3"><?php echo h($row['day3']); ?></textarea>
            </div>
            <div class="ff-field">
              <label class="ff-label">Día 4</label>
              <textarea class="ff-textarea" name="day4" rows="3"><?php echo h($row['day4']); ?></textarea>
            </div>
            <div class="ff-field">
              <label class="ff-label">Día 5</label>
              <textarea class="ff-textarea" name="day5" rows="3"><?php echo h($row['day5']); ?></textarea>
            </div>
            <div class="ff-field">
              <label class="ff-label">Día 6</label>
              <textarea class="ff-textarea" name="day6" rows="3"><?php echo h($row['day6']); ?></textarea>
            </div>
          </div>

          <div class="ff-actions">
            <button type="submit" class="ff-btn ff-btn--primary">Actualizar</button>
            <a href="editroutine.php" class="ff-btn ff-btn--ghost">Volver</a>
          </div>
        </form>
      </div>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>
</body>
</html>
