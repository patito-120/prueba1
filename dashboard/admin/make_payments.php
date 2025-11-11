<?php
require '../../include/db_conn.php';
page_protect();

/* ---------- Validación de entrada ---------- */
if (!isset($_POST['userID'], $_POST['planID']) || $_POST['userID']==='' || $_POST['planID']==='') {
  echo "<script>alert('Faltan datos del miembro o del plan');window.location='view_payment.php';</script>";
  exit;
}

$uid    = $_POST['userID'];
$planid = $_POST['planID'];

/* ---------- Cargar datos de usuario ---------- */
$name = '';
if ($stmt = $con->prepare("SELECT username FROM users WHERE userid = ? LIMIT 1")) {
  $stmt->bind_param('s', $uid);
  $stmt->execute();
  $stmt->bind_result($name);
  $stmt->fetch();
  $stmt->close();
}

/* ---------- Cargar nombre de plan actual (el que vino) ---------- */
$planName = '';
if ($stmt = $con->prepare("SELECT planName FROM plan WHERE pid = ? LIMIT 1")) {
  $stmt->bind_param('s', $planid);
  $stmt->execute();
  $stmt->bind_result($planName);
  $stmt->fetch();
  $stmt->close();
}

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Gym Cañones | Hacer Pago</title>

  <!-- Base del sistema -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">

  <!-- Estilos de esta página -->
  <link rel="stylesheet" href="../../css/make_pay.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js"></script>

  <style>
    /* Resalta el item del menú actual */
    .page-container .sidebar-menu #main-menu li#paymnt > a{
      background-color:#2b303a; color:#fff;
    }
  </style>
</head>
<body class="page-body page-fade" onload="collapseSidebar()">

<div class="page-container sidebar-collapsed" id="navbarcollapse">

  <div class="sidebar-menu">
    <header class="logo-env">
      <div class="logo">
        <a href="main.php">
          <img src="../../images/logo2.png" alt="FitFusion" width="192" height="80" />
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

  <div class="main-content ff-pay">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenid@ <?php echo h($_SESSION['full_name'] ?? ''); ?></li>
          <li><a href="logout.php" class="pill-orange">Cerrar Sesión <i class="entypo-logout right"></i></a></li>
        </ul>
      </div>
    </div>

    <h2 class="ff-title">Hacer Pago</h2>
    <div class="ff-underline"></div>

    <div class="ff-card ff-pay-card">
      <div class="ff-card__head">HACER PAGO</div>

      <form id="form1" name="form1" method="post" class="ff-form" action="submit_payments.php">
        <table class="ff-form-table" width="100%">
          <tr>
            <td>ID Membresía</td>
            <td>
              <input class="ff-input" type="text" name="m_id" value="<?php echo h($uid); ?>" readonly>
            </td>
          </tr>

          <tr>
            <td>Nombre</td>
            <td>
              <input class="ff-input" type="text" name="u_name" value="<?php echo h($name); ?>" readonly>
            </td>
          </tr>

          <tr>
            <td>Plan actual</td>
            <td>
              <input class="ff-input" type="text" name="prevPlan" value="<?php echo h($planName); ?>" readonly>
            </td>
          </tr>

          <tr>
            <td>Seleccionar nuevo plan</td>
            <td>
              <select class="ff-input" name="plan" required onchange="myplandetail(this.value)">
                <option value="">-- Selecciona --</option>
                <?php
                  $q = mysqli_query($con, "SELECT pid, planName FROM plan WHERE active='yes' ORDER BY planName");
                  if ($q && mysqli_num_rows($q) > 0) {
                    while ($row = mysqli_fetch_assoc($q)) {
                      echo '<option value="'.h($row['pid']).'">'.h($row['planName']).'</option>';
                    }
                  }
                ?>
              </select>
            </td>
          </tr>

          <tr>
            <td></td>
            <td>
              <table id="plandetls" class="ff-plan-detail"></table>
            </td>
          </tr>

          <tr>
            <td></td>
            <td class="ff-actions">
              <button type="submit" class="ff-btn ff-btn--primary">Agregar Pago</button>
              <button type="reset"  class="ff-btn ff-btn--muted">Borrar</button>
              <a href="view_payment.php" class="ff-btn ff-btn--ghost">Volver</a>
            </td>
          </tr>
        </table>
      </form>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>

<script>
function myplandetail(planId){
  var cont = document.getElementById("plandetls");
  if(!planId){ cont.innerHTML=""; return; }

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function(){
    if(this.readyState === 4 && this.status === 200){
      cont.innerHTML = this.responseText;
    }
  };
  xhr.open("GET", "plandetail.php?q=" + encodeURIComponent(planId), true);
  xhr.send();
}
</script>

</body>
</html>
