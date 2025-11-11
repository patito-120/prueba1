<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Gym Cañones | Rutinas</title>

  <!-- CSS base -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">
  <!-- CSS específico de esta pantalla -->
  <link rel="stylesheet" href="../../css/routine_list.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js"></script>

  <style>
    /* resalta el item del menú lateral */
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
        <a href="main.php"><img src="../../images/logo2.png" alt="FITFUSION" width="192" height="80"/></a>
      </div>
      <div class="sidebar-collapse" onclick="collapseSidebar()">
        <a href="#" class="sidebar-collapse-icon with-animation"><i class="entypo-menu"></i></a>
      </div>
    </header>
    <?php include('nav.php'); ?>
  </div>

  <div class="main-content ff-routine-list">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenid@ <?php echo htmlspecialchars($_SESSION['full_name'] ?? ''); ?></li>
          <li><a class="ff-pill" href="logout.php">Cerrar Sesión <i class="entypo-logout right"></i></a></li>
        </ul>
      </div>
    </div>

    <div class="ff-section">
      <h3 class="ff-page-title">Rutinas</h3>
      <div class="ff-page-underline"></div>
    </div>

    <div class="ff-table-wrap">
      <table class="ff-table">
        <thead>
          <tr>
            <th>Sl.No</th>
            <th>Nombre de Rutina</th>
            <th class="ff-col-actions">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql = "SELECT tid, tname FROM timetable ORDER BY tname";
            $res = mysqli_query($con, $sql);
            $n = 1;

            if ($res && mysqli_num_rows($res) > 0) {
              while ($row = mysqli_fetch_assoc($res)) {
                $tid  = $row['tid'];
                $tname= $row['tname'];
                echo '<tr>';
                echo '  <td>'. $n++ .'</td>';
                echo '  <td>'. htmlspecialchars($tname) .'</td>';
                echo '  <td class="ff-actions">';
                // Editar
                echo '    <a class="ff-btn ff-btn--primary" href="editdetailroutine.php?id='. urlencode($tid) .'">Editar Rutina</a>';
                // Eliminar
                echo '    <form action="deleteroutine.php" method="post" class="ff-inline" onsubmit="return confirm(\'¿Seguro que deseas eliminar esta rutina?\');">';
                echo '      <input type="hidden" name="name" value="'. htmlspecialchars($tid) .'">';
                echo '      <button type="submit" class="ff-btn ff-btn--danger">Eliminar</button>';
                echo '    </form>';
                echo '  </td>';
                echo '</tr>';
              }
            } else {
              echo '<tr><td colspan="3" class="ff-empty">No hay rutinas registradas.</td></tr>';
            }
          ?>
        </tbody>
      </table>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>
</body>
</html>

										
