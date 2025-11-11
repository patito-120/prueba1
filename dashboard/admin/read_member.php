<?php
require '../../include/db_conn.php';
page_protect();

/**
 * Escapa valores seguros para imprimir en HTML
 * Evita warnings por NULL y protege contra XSS.
 */
function h($v) {
  return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

// Validar origen
if (!isset($_POST['name']) || $_POST['name'] === '') {
  echo "<script>window.location.href='view_mem.php';</script>";
  exit;
}

$memid = mysqli_real_escape_string($con, $_POST['name']);

// Cargar datos del miembro
$qUser = "SELECT userid, username, gender, mobile, email, joining_date FROM users WHERE userid='$memid' LIMIT 1";
$rUser = mysqli_query($con, $qUser);

if ($rUser && mysqli_num_rows($rUser) === 1) {
  $u = mysqli_fetch_assoc($rUser);
  $name   = $u['username'] ?? '';
  $gender = $u['gender'] ?? '';
  $mobile = $u['mobile'] ?? '';
  $email  = $u['email'] ?? '';
  $joinon = $u['joining_date'] ?? '';
} else {
  echo "<script>alert('Miembro no encontrado');window.history.back();</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Historial de Miembro</title>

  <!-- CSS base -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">
  <!-- CSS exclusivo -->
  <link rel="stylesheet" href="../../css/member_history.css">
  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js" type="text/javascript"></script>

  <style>
    .page-container .sidebar-menu #main-menu li#memhistory > a {
      background-color:#2b303a; color:#fff;
    }
  </style>
</head>

<body class="page-body page-fade" onload="collapseSidebar()">
<div class="page-container sidebar-collapsed" id="navbarcollapse">
  <div class="sidebar-menu">
    <header class="logo-env">
      <div class="logo">
        <a href="main.php"><img src="../../images/logo.png" alt="Logo" width="192" height="80" /></a>
      </div>
      <div class="sidebar-collapse" onclick="collapseSidebar()">
        <a href="#" class="sidebar-collapse-icon with-animation">
          <i class="entypo-menu"></i>
        </a>
      </div>
    </header>
    <?php include('nav.php'); ?>
  </div>

  <div class="main-content ff-history">
    <!-- Header -->
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenid@ <?php echo h($_SESSION['full_name'] ?? ''); ?></li>
          <li><a class="ff-pill" href="logout.php">Cerrar Sesión <i class="entypo-logout right"></i></a></li>
        </ul>
      </div>
    </div>

    <!-- Datos del miembro -->
    <div class="ff-section">
      <h3 class="ff-page-title">Historial de Miembro</h3>
      <div class="ff-page-underline"></div>
      <p class="ff-subtle">Detalles de: <strong><?php echo h($name); ?></strong></p>
    </div>

    <div class="ff-card ff-card--narrow">
      <div class="ff-card__header">Datos del Miembro</div>
      <div class="ff-card__body">
        <div class="ff-table-wrap">
          <table class="ff-table">
            <thead>
              <tr>
                <th>ID Membresía</th>
                <th>Nombre</th>
                <th>Género</th>
                <th>Móvil</th>
                <th>Correo</th>
                <th>Ingresó en</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo h($memid); ?></td>
                <td><?php echo h($name); ?></td>
                <td><?php echo h($gender); ?></td>
                <td><?php echo h($mobile); ?></td>
                <td><?php echo h($email); ?></td>
                <td><?php echo h($joinon); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Historial de Pagos -->
    <div class="ff-section">
      <h3 class="ff-page-title">Historial de Pago de: <?php echo h($name); ?></h3>
      <div class="ff-page-underline"></div>
    </div>

    <div class="ff-card">
      <div class="ff-card__header">Pagos y Membresías</div>
      <div class="ff-card__body">
        <div class="ff-table-wrap">
          <table class="ff-table ff-table--compact">
            <thead>
              <tr>
                <th>Sl.No</th>
                <th>Nombre de Plan</th>
                <th>Descripción del Plan</th>
                <th>Validez</th>
                <th>Monto</th>
                <th>Fecha de Pago</th>
                <th>Fecha de Expiración</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
            <?php
              $qEnroll = "SELECT * FROM enrolls_to WHERE uid='$memid' ORDER BY paid_date ASC";
              $rEnroll = mysqli_query($con, $qEnroll);
              $sno = 1;

              if ($rEnroll && mysqli_num_rows($rEnroll) > 0) {
                while ($row = mysqli_fetch_assoc($rEnroll)) {
                  $pid = $row['pid'];
                  $qPlan = "SELECT planName, description, validity, amount FROM plan WHERE pid='$pid' LIMIT 1";
                  $rPlan = mysqli_query($con, $qPlan);
                  if ($rPlan && mysqli_num_rows($rPlan) === 1) {
                    $p = mysqli_fetch_assoc($rPlan);
                    ?>
                    <tr>
                      <td><?php echo $sno; ?></td>
                      <td><?php echo h($p['planName']); ?></td>
                      <td class="ff-cell-desc"><?php echo h($p['description']); ?></td>
                      <td><?php echo h($p['validity']); ?></td>
                      <td><?php echo h($p['amount']); ?></td>
                      <td><?php echo h($row['paid_date']); ?></td>
                      <td><?php echo h($row['expire']); ?></td>
                      <td>
                        <a class="ff-btn ff-btn--info"
                           href="gen_invoice.php?id=<?php echo urlencode($row['uid']); ?>&pid=<?php echo urlencode($row['pid']); ?>&etid=<?php echo urlencode($row['et_id']); ?>">
                          Memo
                        </a>
                      </td>
                    </tr>
                    <?php
                    $sno++;
                  }
                }
              } else {
                echo '<tr><td colspan="8" class="ff-empty">Sin pagos registrados.</td></tr>';
              }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>
</body>
</html>
