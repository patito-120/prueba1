<?php
require '../../include/db_conn.php';
page_protect();

if (!isset($_POST['name']) || $_POST['name'] === '') {
  echo "<script>window.location='table_view.php';</script>";
  exit;
}

$memid = mysqli_real_escape_string($con, $_POST['name']);

/* -------- 1) Datos del usuario + address + salud ---------- */
$sqlUser = "
  SELECT 
    u.userid, u.username, u.gender, u.mobile, u.email, u.dob, u.joining_date,
    a.streetName, a.state, a.city, a.zipcode,
    h.calorie, h.height, h.weight, h.fat, h.remarks
  FROM users u
  LEFT JOIN address a       ON a.id  = u.userid
  LEFT JOIN health_status h ON h.uid = u.userid
  WHERE u.userid = '$memid'
  LIMIT 1;
";
$rUser = mysqli_query($con, $sqlUser);
if (!$rUser || mysqli_num_rows($rUser) !== 1) {
  echo "<script>alert('No se encontró el miembro');history.back();</script>";
  exit;
}
$u = mysqli_fetch_assoc($rUser);

/* -------- 2) Plan actual (último con renewal='yes') -------- */
$plan = [
  'planName' => '-', 'amount' => '-', 'validity' => '-', 
  'description' => '-', 'paid_date' => '-', 'expire' => '-'
];

$sqlPlan = "
  SELECT p.planName, p.amount, p.validity, p.description, e.paid_date, e.expire
  FROM enrolls_to e
  INNER JOIN plan p ON p.pid = e.pid
  WHERE e.uid = '$memid' AND e.renewal = 'yes'
  ORDER BY STR_TO_DATE(e.paid_date,'%Y-%m-%d') DESC, e.et_id DESC
  LIMIT 1;
";
$rPlan = mysqli_query($con, $sqlPlan);
if ($rPlan && mysqli_num_rows($rPlan) === 1) {
  $plan = mysqli_fetch_assoc($rPlan);
}

/* -------- Helpers -------- */
function e($v){ return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
function dval($v){ if(!$v) return ''; $t = strtotime($v); return $t ? date('Y-m-d',$t) : (string)$v; }

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>FITFUSION | Detalle de Miembro</title>

  <!-- CSS base -->
  <link rel="stylesheet" href="../../css/style.css"  id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">

  <!-- CSS aislado de ESTA página -->
  <link rel="stylesheet" href="../../css/view_member_full.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js"></script>
</head>
<body class="page-body page-fade" onload="collapseSidebar()">

<div class="page-container sidebar-collapsed" id="navbarcollapse">
  <div class="sidebar-menu">
    <header class="logo-env">
      <div class="logo">
        <a href="main.php">
          <img src="../../images/logo.png" alt="" width="192" height="80" />
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

  <div class="main-content ff-viewfull">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenid@ <?php echo e($_SESSION['full_name'] ?? ''); ?></li>
          <li><a class="ff-pill" href="logout.php">Cerrar Sesión <i class="entypo-logout right"></i></a></li>
        </ul>
      </div>
    </div>

    <h3 class="ff-title">Editar Detalles de Miembro</h3>
    <div class="ff-underline"></div>

    <div class="ff-card">
      <div class="ff-card__header">Información del Miembro</div>
      <div class="ff-card__body">

        <!-- Datos personales -->
        <div class="ff-grid">
          <div class="ff-field"><label>ID de Usuario</label><input type="text" value="<?php echo e($u['userid']); ?>" readonly></div>
          <div class="ff-field"><label>Nombre</label><input type="text" value="<?php echo e($u['username']); ?>" readonly></div>

          <div class="ff-field"><label>Género</label><input type="text" value="<?php echo e($u['gender']); ?>" readonly></div>
          <div class="ff-field"><label>Móvil</label><input type="text" value="<?php echo e($u['mobile']); ?>" readonly></div>

          <div class="ff-field"><label>Correo</label><input type="text" value="<?php echo e($u['email']); ?>" readonly></div>
          <div class="ff-field"><label>Fecha de Nacimiento</label><input type="date" value="<?php echo dval($u['dob']); ?>" readonly></div>

          <div class="ff-field"><label>Fecha de Ingreso</label><input type="date" value="<?php echo dval($u['joining_date']); ?>" readonly></div>
          <div class="ff-field ff-field--full"><label>Dirección</label><input type="text" value="<?php echo e($u['streetName']); ?>" readonly></div>

          <div class="ff-field"><label>Departamento</label><input type="text" value="<?php echo e($u['state']); ?>" readonly></div>
          <div class="ff-field"><label>Ciudad</label><input type="text" value="<?php echo e($u['city']); ?>" readonly></div>
          <div class="ff-field"><label>Código Postal</label><input type="text" value="<?php echo e($u['zipcode']); ?>" readonly></div>

          <div class="ff-field"><label>Calorías</label><input type="text" value="<?php echo e($u['calorie']); ?>" readonly></div>
          <div class="ff-field"><label>Altura</label><input type="text" value="<?php echo e($u['height']); ?>" readonly></div>
          <div class="ff-field"><label>Peso</label><input type="text" value="<?php echo e($u['weight']); ?>" readonly></div>
          <div class="ff-field"><label>Grasa</label><input type="text" value="<?php echo e($u['fat']); ?>" readonly></div>

          <div class="ff-field ff-field--full">
            <label>Observaciones</label>
            <textarea rows="3" readonly><?php echo e($u['remarks']); ?></textarea>
          </div>
        </div>

        <!-- Plan -->
        <div class="ff-subtitle">Plan actual</div>
        <div class="ff-grid">
          <div class="ff-field"><label>Nombre del Plan</label><input type="text" value="<?php echo e($plan['planName']); ?>" readonly></div>
          <div class="ff-field"><label>Monto</label><input type="text" value="<?php echo e($plan['amount']); ?>" readonly></div>
          <div class="ff-field"><label>Validez (meses)</label><input type="text" value="<?php echo e($plan['validity']); ?>" readonly></div>
          <div class="ff-field"><label>Fecha de Pago</label><input type="text" value="<?php echo e($plan['paid_date']); ?>" readonly></div>
          <div class="ff-field"><label>Fecha de Expiración</label><input type="text" value="<?php echo e($plan['expire']); ?>" readonly></div>
          <div class="ff-field ff-field--full"><label>Descripción</label><input type="text" value="<?php echo e($plan['description']); ?>" readonly></div>
        </div>

        <!-- Acciones -->
        <div class="ff-actions">
          <form action="edit_member.php" method="post">
            <input type="hidden" name="name" value="<?php echo e($memid); ?>">
            <button class="ff-btn ff-btn--primary" type="submit">Editar</button>
          </form>

          <form action="read_member.php" method="post">
            <input type="hidden" name="name" value="<?php echo e($memid); ?>">
            <button class="ff-btn ff-btn--muted" type="submit">Historial</button>
          </form>

          <a class="ff-btn ff-btn--ghost" href="table_view.php">Volver</a>
        </div>

      </div>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>

</body>
</html>
