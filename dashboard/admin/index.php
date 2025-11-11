<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Panel de Control</title>

  <!-- CSS base del proyecto -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <!-- Estilos del dashboard (usa ?v para bust de caché) -->
  <link rel="stylesheet" href="../../css/dashMain.css?v=3">
  <!-- Iconos Entypo -->
  <link rel="stylesheet" type="text/css" href="../../css/entypo.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script type="text/javascript" src="../../js/Script.js"></script>

  <style>
    /* Marca el ítem activo del menú lateral */
    .page-container .sidebar-menu #main-menu li#dash > a {
      background-color: #2b303a;
      color: #ffffff;
    }
  </style>
</head>

<body class="page-body page-fade" onload="collapseSidebar()">

<div class="page-container sidebar-collapsed" id="navbarcollapse">

  <div class="sidebar-menu">
    <header class="logo-env">
      <div class="logo">
        <a href="main.php">
          <img src="../../images/logo2.png" alt="FitFusion Gym" width="192" height="80" />
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

  <div class="main-content">

    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>

      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right topbar">
          <li class="welcome">
            Bienvenid@ <strong class="username"><?php echo $_SESSION['full_name']; ?></strong>
          </li>
          <li>
            <a href="logout.php" class="btn-logout">
              Cerrar Sesión <i class="entypo-logout right"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <h2 class="dash-title">Gym Cañones| Panel de Control</h2>
    <hr class="dash-accent">

    <!-- KPI 1 -->
    <div class="col-sm-3">
      <a href="revenue_month.php">
        <div class="tile-stats kpi-card kpi-red">
          <div class="icon"><i class="entypo-users"></i></div>
          <div class="num">
            <div class="kpi-title">Dinero recibido este Mes</div>
            <div class="kpi-value">
              <?php
              date_default_timezone_set('America/Bogota');
              $date  = date('Y-m');
              $query = "SELECT * FROM enrolls_to WHERE paid_date LIKE '$date%'";
              $result  = mysqli_query($con, $query);
              $revenue = 0;
              if (mysqli_affected_rows($con) != 0) {
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                  $pid = $row['pid'];
                  $result1 = mysqli_query($con, "SELECT * FROM plan WHERE pid='$pid'");
                  if ($result1) {
                    $value   = mysqli_fetch_row($result1);
                    $revenue = $value[4] + $revenue;
                  }
                }
              }
              echo "Bs." . $revenue;
              ?>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- KPI 2 -->
    <div class="col-sm-3">
      <a href="table_view.php">
        <div class="tile-stats kpi-card kpi-green">
          <div class="icon"><i class="entypo-chart-bar"></i></div>
          <div class="num">
            <div class="kpi-title">Miembros Totales</div>
            <div class="kpi-value">
              <?php
              $result = mysqli_query($con, "SELECT COUNT(*) AS c FROM users");
              if ($result && mysqli_affected_rows($con) != 0) {
                $row = mysqli_fetch_assoc($result);
                echo $row['c'];
              }
              ?>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- KPI 3 -->
    <div class="col-sm-3">
      <a href="over_members_month.php">
        <div class="tile-stats kpi-card kpi-cyan">
          <div class="icon"><i class="entypo-mail"></i></div>
          <div class="num">
            <div class="kpi-title">Usuarios Ingresados este mes</div>
            <div class="kpi-value">
              <?php
              date_default_timezone_set("America/Bogota");
              $date  = date('Y-m');
              $result = mysqli_query($con, "SELECT COUNT(*) AS c FROM users WHERE joining_date LIKE '$date%'");
              if ($result && mysqli_affected_rows($con) != 0) {
                $row = mysqli_fetch_assoc($result);
                echo $row['c'];
              }
              ?>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- KPI 4 -->
    <div class="col-sm-3">
      <a href="view_plan.php">
        <div class="tile-stats kpi-card kpi-blue">
          <div class="icon"><i class="entypo-rss"></i></div>
          <div class="num">
            <div class="kpi-title">Planes de Entreno Disponibles</div>
            <div class="kpi-value">
              <?php
              $result = mysqli_query($con, "SELECT COUNT(*) AS c FROM plan WHERE active='yes'");
              if ($result && mysqli_affected_rows($con) != 0) {
                $row = mysqli_fetch_assoc($result);
                echo $row['c'];
              }
              ?>
            </div>
          </div>
        </div>
      </a>
    </div>

    <?php include('footer.php'); ?>
  </div> <!-- .main-content -->

</div> <!-- .page-container -->

</body>
</html>
