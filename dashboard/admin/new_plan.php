<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Gym Cañones | Nuevo Plan</title>

  <!-- CSS base del sistema -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">
  <!-- CSS específico de esta página -->
  <link rel="stylesheet" href="../../css/new_plan.css">
  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon" />
  <script src="../../js/Script.js"></script>

  <style>
    /* Mantener resaltado del menú actual (compatibilidad con tu tema) */
    .page-container .sidebar-menu #main-menu li#planhassubopen > a{
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

  <div class="main-content">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right">
          <li>Bienvenid@ <?php echo htmlspecialchars($_SESSION['full_name']); ?></li>
          <li><a href="logout.php">Cerrar Sesión <i class="entypo-logout right"></i></a></li>
        </ul>
      </div>
    </div>

    <h3>Crear Plan</h3>
    <hr />

    <div class="a1-container a1-small a1-padding-32" style="margin-top:2px; margin-bottom:2px;">
      <div class="a1-card-8 a1-light-gray" style="width:600px; margin:0 auto;">
        <div class="a1-container a1-dark-gray a1-center">
          <h6>Detalles de Nuevo Plan</h6>
        </div>

        <form id="form1" name="form1" method="post" class="a1-container" action="submit_plan_new.php" autocomplete="off">
          <table width="100%" border="0" align="center" role="presentation">
            <tr>
              <td>PLAN ID:</td>
              <td>
                <?php
                  function getRandomWord($len = 6){
                    $word = range('A','Z');
                    shuffle($word);
                    return substr(implode($word), 0, $len);
                  }
                ?>
                <input type="text" name="planid" id="planID" readonly
                       value="<?php echo getRandomWord(); ?>">
              </td>
            </tr>

            <tr>
              <td>Nombre de Plan:</td>
              <td>
                <input name="planname" id="planName" type="text"
                       placeholder="Ingrese el nombre del plan" size="40" required>
              </td>
            </tr>

            <tr>
              <td>Descripción de Plan</td>
              <td>
                <input type="text" name="desc" id="planDesc"
                       placeholder="Ingrese la descripción del plan" size="40" required>
              </td>
            </tr>

            <tr>
              <td>Validez del Plan</td>
              <td>
                <input type="number" name="planval" id="planVal" min="1" step="1"
                       placeholder="Meses de validez (ej. 1, 3, 12)" size="40" required>
              </td>
            </tr>

            <tr>
              <td>Plan Amount:</td>
              <td>
                <input type="number" name="amount" id="planAmnt" min="0" step="1"
                       placeholder="Monto del plan" size="40" required>
              </td>
            </tr>

            <tr>
              <td></td>
              <td>
                <input class="a1-btn a1-blue" type="submit" name="submit" id="submit" value="Crear Plan">
                <input class="a1-btn a1-blue" type="reset"  name="reset"  id="reset"  value="Borrar">
              </td>
            </tr>
          </table>
        </form>

      </div>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>
</body>
</html>
