<?php
require '../../include/db_conn.php';
page_protect();

// Sanitizar el id
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM timetable t WHERE t.tid = $id";
$res = mysqli_query($con, $sql);
$row = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : [];

function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
function rowIf($label, $val){
  $val = trim($val ?? '');
  if($val === '') return '';
  return '<tr><th class="col-day">'.$label.'</th><td class="col-plan">'.nl2br(h($val)).'</td></tr>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Detalles de Rutina</title>

  <!-- (Opcional) estilos globales de tu app -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" type="text/css" href="../../css/entypo.css">
  <link href="a1style.css" rel="stylesheet" type="text/css">
  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">

  <!-- ===== CSS INLINE ESPECÍFICO (optimizado para impresión/PDF A4) ===== -->
  <style>
    :root{
      --bg:#f7f7f8;
      --ink:#0f172a;
      --muted:#64748b;
      --brand:#ff6a00;
      --brand-ink:#1a1a1a;
      --border:#e5e7eb;
      --card:#ffffff;
      --radius:14px;
      --shadow:0 8px 28px rgba(17,24,39,.10);
    }
    *{ box-sizing:border-box; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    body{ font-family:"Segoe UI", Roboto, Arial, "Noto Sans", sans-serif; background:var(--bg); color:var(--ink); margin:0; }

    .page-container .sidebar-menu #main-menu li#routinehassubopen > a{ background-color:#2b303a; color:#fff; }

    .topbar{ display:flex; align-items:center; justify-content:space-between; gap:12px; }
    .title{ margin:10px 0; font-weight:900; letter-spacing:.3px; }
    .divider{ border:0; border-top:1px solid var(--border); margin:10px 0 16px; }
    .btn{ appearance:none; border:0; cursor:pointer; padding:10px 16px; border-radius:10px; font-weight:700; box-shadow:var(--shadow); }
    .btn-print{ background:var(--brand); color:#fff; }
    .btn-print:active{ transform:translateY(1px); }
    .empty-state{ background:#fff; border:1px solid var(--border); border-radius:var(--radius); padding:22px; color:var(--muted); }

    .card{ background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; }
    .sheet{ margin:0 auto; }

    /* Header del PDF */
    .card-header{
      display:flex; align-items:flex-start; justify-content:space-between; gap:20px;
      padding:22px 24px; border-bottom:1px solid var(--border); background:#fff;
    }
    .brand{ display:flex; align-items:center; gap:14px; }
    .brand-logo{ width:84px; height:84px; object-fit:contain; }
    .brand-title{ margin:0; font-size:26px; font-weight:900; color:var(--brand-ink); letter-spacing:.6px; }
    .brand-sub{ margin:2px 0 0; color:var(--muted); font-size:12px; }
    .meta{ text-align:right; min-width:240px; }
    .meta-field{ margin-bottom:8px; }
    .meta .k{ color:var(--muted); font-size:12px; font-weight:700; margin-right:8px; }
    .meta .v{ color:var(--brand-ink); font-weight:800; }

    /* Tabla de rutina */
    .table-wrap{ background:#fff; padding:20px 24px; }
    table.routine{
      width:100%; border-collapse:separate; border-spacing:0; background:#fff;
      border:1px solid var(--border); border-radius:12px; overflow:hidden;
    }
    .routine thead th{
      text-align:left; padding:12px 14px; font-size:13px; letter-spacing:.3px;
      color:#fff; background:linear-gradient(90deg, var(--brand) 0%, #ff7f2a 100%);
    }
    .routine tbody th, .routine tbody td{
      padding:12px 14px; vertical-align:top; border-top:1px solid var(--border);
      background:#fff;
    }
    .routine tbody tr:first-child th, .routine tbody tr:first-child td{ border-top:none; }
    .col-day{ width:120px; color:var(--brand-ink); font-weight:800; }
    .col-plan{ color:var(--ink); white-space:pre-wrap; line-height:1.55; }

    /* Footer */
    .card-footer{
      display:flex; align-items:flex-end; justify-content:space-between; gap:18px;
      padding:18px 24px; border-top:1px solid var(--border); background:#fff;
    }
    .note{ color:var(--muted); font-size:12px; }
    .sign{ text-align:center; min-width:240px; }
    .sign .line{ border-top:2px solid var(--ink); margin-bottom:6px; height:0; }
    .sign span{ color:var(--muted); font-size:12px; }

    /* Impresión */
    @media print{
      body{ background:#fff; }
      .sidebar-menu, .topbar, .divider, .links-list, .sidebar-collapse { display:none !important; }
      .main-content{ margin:0; padding:0; }
      .card{ border:none; box-shadow:none; }
    }
    @page{ size:A4 portrait; margin:14mm; }
  </style>

  <script type="text/javascript" src="../../js/Script.js"></script>
  <script>
    function imprimirRutina(){
      const prt = document.getElementById("print");
      const css = document.querySelector('style').innerHTML;
      const w = window.open('', '', 'left=0,top=0,width=900,height=1000,toolbar=0,scrollbars=1,status=0');
      w.document.write(`
        <html>
          <head>
            <meta charset="utf-8">
            <title>GYM CAÑONES | Rutina</title>
            <style>${css}</style>
          </head>
          <body class="print-area">` + prt.innerHTML + `</body>
        </html>
      `);
      w.document.close(); w.focus(); w.print(); w.close();
    }
    function init(){ try{ collapseSidebar(); }catch(e){} }
  </script>
</head>

<body class="page-body page-fade" onload="init()">

<div class="page-container sidebar-collapsed" id="navbarcollapse">

  <div class="sidebar-menu">
    <header class="logo-env">
      <div class="logo">
        <a href="main.php"><img src="../../images/logo2.png" alt="GYM CAÑONES" width="192" height="80"/></a>
      </div>
      <div class="sidebar-collapse" onclick="collapseSidebar()">
        <a href="#" class="sidebar-collapse-icon with-animation"><i class="entypo-menu"></i></a>
      </div>
    </header>
    <?php include('nav.php'); ?>
  </div>

  <div class="main-content">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right">
          <li>Bienvenid@ <?php echo h($_SESSION['full_name'] ?? ''); ?></li>
          <li><a href="logout.php">Cerrar Sesión <i class="entypo-logout right"></i></a></li>
        </ul>
      </div>
    </div>

    <div class="topbar">
      <h2 class="title">Detalles de la Rutina</h2>
      <div class="actions">
        <button class="btn btn-print" onclick="imprimirRutina()">Imprimir / PDF</button>
      </div>
    </div>
    <hr class="divider"/>

    <?php if(!$row){ ?>
      <div class="empty-state"><p>No se encontró la rutina solicitada.</p></div>
    <?php } else { ?>

    <!-- ÁREA IMPRIMIBLE -->
    <div id="print">
      <section class="sheet card">
        <!-- Encabezado -->
        <header class="card-header">
          <div class="brand">
            <img class="brand-logo" src="logo4.png" alt="GYM CAÑONES">
            <div>
              <h1 class="brand-title">Gym Cañones</h1>
              <p class="brand-sub">Plan de Entrenamiento Personalizado</p>
            </div>
          </div>
          <div class="meta">
            <div class="meta-field"><span class="k">Nombre de la Rutina:</span> <span class="v"><?php echo h($row['tname']); ?></span></div>
            <div class="meta-field"><span class="k">Código:</span> <span class="v">#<?php echo (int)$row['tid']; ?></span></div>
          </div>
        </header>

        <!-- Tabla -->
        <div class="table-wrap">
          <table class="routine">
            <thead>
              <tr>
                <th style="border-top-left-radius:12px">Día</th>
                <th style="border-top-right-radius:12px">Rutina / Ejercicios</th>
              </tr>
            </thead>
            <tbody>
              <?php
                echo rowIf('Día 1', $row['day1'] ?? '');
                echo rowIf('Día 2', $row['day2'] ?? '');
                echo rowIf('Día 3', $row['day3'] ?? '');
                echo rowIf('Día 4', $row['day4'] ?? '');
                echo rowIf('Día 5', $row['day5'] ?? '');
                echo rowIf('Día 6', $row['day6'] ?? '');
              ?>
            </tbody>
          </table>
        </div>

        <!-- Pie -->
        <footer class="card-footer">
          <div class="note"><strong>Nota:</strong> Mantén técnica correcta y descanso adecuado entre series.</div>
          <div class="sign"><div class="line"></div><span>Firma del Instructor</span></div>
        </footer>
      </section>
    </div>
    <!-- /ÁREA IMPRIMIBLE -->

    <?php } ?>
  </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>
