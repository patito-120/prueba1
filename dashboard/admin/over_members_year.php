<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Miembros por Año</title>

  <!-- CSS base -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">

  <!-- CSS específico (scope aislado) -->
  <link rel="stylesheet" href="../../css/over_members_year.css">

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
  <div class="main-content ff-members-year">
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
      <h3 class="ff-page-title">Miembros por Año</h3>
      <div class="ff-page-underline"></div>
    </div>

    <!-- Toolbar filtros -->
    <form class="ff-toolbar" onsubmit="showMember();return false;">
      <?php $yearArray = range(2000, date('Y')); ?>
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

      <button type="button" class="ff-btn ff-btn-primary" onclick="showMember()">Búsqueda</button>
    </form>

    <!-- Contenedor tabla (AJAX llena el contenido) -->
    <div class="ff-table-wrap">
      <table id="meyear" class="ff-table"></table>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>

<script>
function showMember(){
  var year = document.getElementById("syear").value;
  if(year === "0"){
    document.getElementById("meyear").innerHTML = "";
    return;
  }
  var xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
  xhr.onreadystatechange = function(){
    if(this.readyState === 4 && this.status === 200){
      document.getElementById("meyear").innerHTML = this.responseText;
    }
  };
  // Mantengo tu endpoint original: usa flag=1 y mm=0
  xhr.open("GET","over_month.php?mm=0&flag=1&yy="+encodeURIComponent(year),true);
  xhr.send();
}
</script>

</body>
</html>
