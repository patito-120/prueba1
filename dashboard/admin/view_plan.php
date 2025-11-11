<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Ver Plan</title>

  <!-- CSS base del sistema -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">

  <!-- CSS específico y aislado de esta página -->
  <link rel="stylesheet" href="../../css/view_plan_legacy.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js"></script>

  <style>
    /* Resalta el item del menú lateral para Planes */
    .page-container .sidebar-menu #main-menu li#planhassubopen > a {
      background-color:#2b303a; color:#ffffff;
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
        <a href="#" class="sidebar-collapse-icon with-animation">
          <i class="entypo-menu"></i>
        </a>
      </div>
    </header>
    <?php include('nav.php'); ?>
  </div>

  <div class="main-content ff-plan-scope">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenid@ <?php echo htmlspecialchars($_SESSION['full_name']); ?></li>
          <li>
            <a class="ff-pill" href="logout.php">
              Cerrar Sesión <i class="entypo-logout right"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <h3 class="ff-title">Administrar Plan</h3>
    <div class="ff-underline"></div>

    <div class="ff-table-wrap">
      <table class="ff-table" id="table-1">
        <thead>
          <tr>
            <th>No</th>
            <th>ID de Plan</th>
            <th>Nombre del Plan</th>
            <th>Detalles de Plan</th>
            <th>Meses</th>
            <th>Costo</th>
            <th class="ff-col-actions">Acción</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $query  = "SELECT pid, planName, description, validity, amount
                     FROM plan
                     WHERE active='yes'
                     ORDER BY amount DESC";
          $result = mysqli_query($con, $query);
          $sno    = 1;

          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $pid   = $row['pid'];
              $name  = $row['planName'];
              $desc  = $row['description'];
              $valid = $row['validity'];
              $amt   = $row['amount'];

              echo '<tr>';
              echo '  <td>'. $sno++ .'</td>';
              echo '  <td>'. htmlspecialchars($pid) .'</td>';
              echo '  <td>'. htmlspecialchars($name) .'</td>';
              echo '  <td class="ff-desc">'. htmlspecialchars($desc) .'</td>';
              echo '  <td class="ff-num">'. htmlspecialchars($valid) .'</td>';
              echo '  <td class="ff-num">$'. number_format((float)$amt, 0, ',', '.') .'</td>';
              echo '  <td class="ff-actions">';

              // Editar
              echo '    <a class="ff-btn ff-btn--primary" href="edit_plan.php?id='. urlencode($pid) .'">Editar Plan</a>';

              // Eliminar
              echo '    <form action="del_plan.php" method="post" class="ff-inline" onsubmit="return confirmDel();">';
              echo '      <input type="hidden" name="name" value="'. htmlspecialchars($pid) .'">';
              echo '      <button type="submit" class="ff-btn ff-btn--danger">Eliminar Plan</button>';
              echo '    </form>';

              echo '  </td>';
              echo '</tr>';
            }
          } else {
            echo '<tr><td colspan="7" class="ff-empty">No hay planes activos.</td></tr>';
          }
        ?>
        </tbody>
      </table>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>

<script>
  function confirmDel(){
    return confirm('¿Seguro que deseas eliminar este plan?');
  }
</script>
</body>
</html>
