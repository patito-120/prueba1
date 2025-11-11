<?php
require '../../include/db_conn.php';
page_protect();

/* 1) Obtener y validar el id del plan (string/alfa-num) */
if (!isset($_GET['id']) || $_GET['id'] === '') {
  echo "<script>alert('Falta el ID del plan');window.location='view_plan.php';</script>";
  exit;
}
$pid = trim($_GET['id']);

/* 2) Cargar datos del plan con consulta preparada */
$stmt = $con->prepare("SELECT pid, planName, description, validity, amount FROM plan WHERE pid = ? LIMIT 1");
if (!$stmt) {
  echo "<script>alert('Error preparando consulta: ".addslashes($con->error)."');window.location='view_plan.php';</script>";
  exit;
}
$stmt->bind_param('s', $pid);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows !== 1) {
  echo "<script>alert('Plan no encontrado');window.location='view_plan.php';</script>";
  exit;
}
$row = $res->fetch_assoc();
$stmt->close();

/* Helper de salida segura */
function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Editar Plan</title>

  <!-- CSS base -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">

  <!-- CSS específico y aislado de ESTA página -->
  <link rel="stylesheet" href="../../css/plan_edit.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js"></script>
</head>

<body class="page-body page-fade" onload="collapseSidebar()">
<div class="page-container sidebar-collapsed" id="navbarcollapse">

  <div class="sidebar-menu">
    <header class="logo-env">
      <div class="logo">
        <a href="main.php">
          <img src="../../images/logo2.png" alt="FITFUSION" width="192" height="80" />
        </a>
      </div>
      <div class="sidebar-collapse" onclick="collapseSidebar()">
        <a href="#" class="sidebar-collapse-icon with-animation">
          <i class="entypo-menu"></i>
        </a>
      </div>
    </header>
    <?php include('nav.php'); ?>
  </div>

  <!-- SCOPE -->
  <div class="main-content ff-planedit">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenid@ <?php echo h($_SESSION['full_name'] ?? ''); ?></li>
          <li>
            <a class="ff-pill" href="logout.php">
              Cerrar Sesión <i class="entypo-logout right"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Título -->
    <div class="ff-section">
      <h3 class="ff-page-title">Editar Plan</h3>
      <div class="ff-page-underline"></div>
    </div>

    <!-- Tarjeta -->
    <div class="ff-card ff-card--narrow">
      <div class="ff-card__header">Actualizar Plan</div>
      <div class="ff-card__body">
        <!-- Mantiene el flujo a updateplan.php -->
        <form id="form1" name="form1" method="post" action="updateplan.php" class="ff-form">
          <div class="ff-row">
            <div class="ff-col">
              <label class="ff-label">ID PLAN</label>
              <input class="ff-input" type="text" name="planid" id="planID" readonly
                     value="<?php echo h($row['pid']); ?>">
            </div>
            <div class="ff-col">
              <label class="ff-label">Nombre de Plan</label>
              <input class="ff-input" name="planname" id="planName" type="text"
                     value="<?php echo h($row['planName']); ?>" required>
            </div>
          </div>

          <div class="ff-row">
            <div class="ff-col ff-col--full">
              <label class="ff-label">Descripción</label>
              <input class="ff-input" type="text" name="desc" id="planDesc"
                     value="<?php echo h($row['description']); ?>">
            </div>
          </div>

          <div class="ff-row">
            <div class="ff-col">
              <label class="ff-label">Validez (meses)</label>
              <input class="ff-input" type="number" name="planval" id="planVal" min="1"
                     value="<?php echo h($row['validity']); ?>" required>
            </div>
            <div class="ff-col">
              <label class="ff-label">Monto</label>
              <input class="ff-input" type="number" name="amount" id="planAmnt" min="0" step="1"
                     value="<?php echo h($row['amount']); ?>" required>
            </div>
          </div>

          <div class="ff-actions">
            <button class="ff-btn ff-btn-primary" type="submit" name="submit" id="submit">
              Actualizar Plan
            </button>
            <button class="ff-btn ff-btn-secondary" type="reset" id="reset">
              Borrar
            </button>
            <a class="ff-btn ff-btn-ghost" href="view_plan.php">Volver</a>
          </div>
        </form>
      </div>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>
</body>
</html>
