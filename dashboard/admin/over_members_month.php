<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Miembros por Mes</title>

  <!-- CSS base -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">

  <!-- CSS ESPECÍFICO (scope aislado) -->
  <link rel="stylesheet" href="../../css/over_members_month.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js"></script>

  <style>
    /* Marca activo en el menú lateral */
    .page-container .sidebar-menu #main-menu li#overviewhassubopen > a{
      background-color:#2b303a; color:#fff;
    }
  </style>
</head>

<body class="page-body page-fade" onload="collapseSidebar();showMember();">
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

  <!-- SCOPE -->
  <div class="main-content ff-members-month">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenid@ <?php echo htmlspecialchars($_SESSION['full_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
          <li><a class="ff-pill" href="logout.php">Cerrar Sesión <i class="entypo-logout right"></i></a></li>
        </ul>
      </div>
    </div>

    <!-- Título -->
    <div class="ff-section">
      <h3 class="ff-page-title">Miembros por Mes</h3>
      <div class="ff-page-underline"></div>
    </div>

    <!-- Toolbar filtros -->
    <form class="ff-toolbar" onsubmit="showMember();return false;">
      <?php
        $yearArray = range(2000, date('Y'));
        $formattedMonthArray = [
          "01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril",
          "05"=>"Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto",
          "09"=>"Septiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre"
        ];
      ?>
      <label class="sr-only" for="syear">Año</label>
      <select name="year" id="syear" class="ff-input ff-input--select">
        <option value="0">Seleccione el Año</option>
        <?php
          foreach ($yearArray as $year) {
            $selected = ($year == date('Y')) ? 'selected' : '';
            echo '<option '.$selected.' value="'.$year.'">'.$year.'</option>';
          }
        ?>
      </select>

      <label class="sr-only" for="smonth">Mes</label>
      <select name="month" id="smonth" class="ff-input ff-input--select">
        <option value="0">Selecciona Mes</option>
        <?php
          foreach ($formattedMonthArray as $mm => $label) {
            $selected = ($mm == date('m')) ? 'selected' : '';
            echo '<option '.$selected.' value="'.$mm.'">'.$label.'</option>';
          }
        ?>
      </select>

      <button type="button" class="ff-btn ff-btn-primary" onclick="showMember()">Búsqueda</button>
    </form>

    <!-- Contenedor tabla (AJAX llena el contenido) -->
    <div class="ff-table-wrap">
      <!-- Nota: over_month.php retorna el contenido de la tabla (thead/tbody ó filas).
           Aquí dejamos un <table> para que el CSS aplique siempre. -->
      <table id="memmonth" class="ff-table"></table>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>

<script>
function showMember(){
  var year  = document.getElementById("syear").value;
  var month = document.getElementById("smonth").value;

  if(month === "0" || year === "0"){
    document.getElementById("memmonth").innerHTML = "";
    return;
  }

  var xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
  xhr.onreadystatechange = function(){
    if(this.readyState === 4 && this.status === 200){
      document.getElementById("memmonth").innerHTML = this.responseText;
    }
  };
  xhr.open("GET", "over_month.php?mm=" + encodeURIComponent(month) + "&yy=" + encodeURIComponent(year) + "&flag=0", true);
  xhr.send();
}
</script>

</body>
</html>
