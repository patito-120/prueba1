<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones  | Rutina</title>

  <!-- CSS base del sistema -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">

  <!-- CSS aislado de esta pantalla -->
  <link rel="stylesheet" href="../../css/routine_new.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js"></script>

  <style>
    /* Marca la opción activa del menú */
    .page-container .sidebar-menu #main-menu li#routinehassubopen > a{
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

  <div class="main-content ff-routine-create">
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

    <!-- Título -->
    <div class="ff-section">
      <h3 class="ff-page-title">Crear Rutina</h3>
      <div class="ff-page-underline"></div>
    </div>

    <!-- Tarjeta -->
    <div class="ff-card ff-card--narrow">
      <div class="ff-card__header">Nueva Rutina</div>
      <div class="ff-card__body">
        <!-- IMPORTANTE: respeta tu flujo actual -->
        <form id="form1" name="form1" method="post" class="ff-form" action="saveroutine.php" autocomplete="off">
          <div class="ff-row">
            <div class="ff-col ff-col--full">
              <label class="ff-label" for="rname">Nombre de Rutina</label>
              <input class="ff-input" type="text" id="rname" name="rname" required placeholder="Ej. Full Body / Empuje-Tirón / Piernas">
            </div>
          </div>

          <div class="ff-grid-2">
            <div class="ff-field">
              <label class="ff-label" for="day1">Día 1</label>
              <textarea class="ff-textarea" id="day1" name="day1" rows="3" placeholder="Ej. Sentadilla 4x8, Press banca 4x8…"></textarea>
            </div>
            <div class="ff-field">
              <label class="ff-label" for="day2">Día 2</label>
              <textarea class="ff-textarea" id="day2" name="day2" rows="3" placeholder="Ej. Peso muerto, Remo con barra…"></textarea>
            </div>
            <div class="ff-field">
              <label class="ff-label" for="day3">Día 3</label>
              <textarea class="ff-textarea" id="day3" name="day3" rows="3" placeholder="Ej. Press militar, Dominadas…"></textarea>
            </div>
            <div class="ff-field">
              <label class="ff-label" for="day4">Día 4</label>
              <textarea class="ff-textarea" id="day4" name="day4" rows="3" placeholder="Opcional"></textarea>
            </div>
            <div class="ff-field">
              <label class="ff-label" for="day5">Día 5</label>
              <textarea class="ff-textarea" id="day5" name="day5" rows="3" placeholder="Opcional"></textarea>
            </div>
            <div class="ff-field">
              <label class="ff-label" for="day6">Día 6</label>
              <textarea class="ff-textarea" id="day6" name="day6" rows="3" placeholder="Opcional"></textarea>
            </div>
          </div>

          <div class="ff-actions">
            <button class="ff-btn ff-btn--primary" type="submit">Agregar Rutina</button>
            <button class="ff-btn ff-btn--ghost" type="reset">Borrar</button>
          </div>
        </form>
      </div>
    </div>

    <?php include('footer.php'); ?>
  </div>
</div>
</body>
</html>

				
