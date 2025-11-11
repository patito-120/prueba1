<?php
require '../../include/db_conn.php';
page_protect();

$etid = $_GET['etid'] ?? '';
$pid  = $_GET['pid']  ?? '';
$uid  = $_GET['id']   ?? '';

$sql = "
  SELECT *
  FROM users u
  INNER JOIN enrolls_to e ON u.userid = e.uid
  INNER JOIN plan p ON p.pid = e.pid
  WHERE u.userid = '".$uid."' AND e.et_id = '".$etid."'
";
$res = mysqli_query($con, $sql);
$row = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : [];

function h($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
function dineroBOB($monto){
  $num = is_numeric($monto) ? (float)$monto : 0;
  return number_format($num, 2, ',', '.');
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones — Comprobante de Pago</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{
      --bg:#f7f7f8;
      --ink:#0f172a;           /* texto principal (casi negro) */
      --muted:#64748b;         /* texto secundario */
      --brand:#ff6a00;         /* naranja poderoso del panel */
      --brand-ink:#1a1a1a;     /* negro/gris grafito para contraste */
      --border:#e5e7eb;
      --card:#ffffff;
      --gold:#d4af37;          /* dorado suave para detalles */
      --radius:14px;
      --shadow:0 8px 28px rgba(17,24,39,.10);
    }

    *{ box-sizing:border-box; }
    html,body{ margin:0; padding:0; background:var(--bg); color:var(--ink); }
    body{ font-family: "Segoe UI", Roboto, Arial, "Noto Sans", "Helvetica Neue", sans-serif; }

    .wrap{
      max-width: 900px;
      margin: 28px auto;
      padding: 0 20px;
    }

    .toolbar.no-print{
      display:flex; gap:12px; justify-content:flex-end; margin: 12px 0 6px;
    }
    .btn{
      border:0; padding:10px 16px; border-radius:10px; cursor:pointer;
      font-weight:600; background:var(--brand); color:#fff; box-shadow: var(--shadow);
    }
    .btn:active{ transform: translateY(1px); }

    .card{
      background:var(--card);
      border:1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
    }

    /* Header */
    .header{
      display:flex; align-items:center; gap:18px;
      padding:22px 24px; border-bottom:1px solid var(--border);
      background: linear-gradient(90deg, #fff 0%, #fff 55%, #fff0 100%), #fff;
    }
    .brand-logo{
      width:86px; height:86px; object-fit:contain;
      filter: drop-shadow(0 2px 6px rgba(0,0,0,.12));
    }
    .brand-block{ flex:1; }
    .brand-title{
      margin:0; font-size:28px; letter-spacing:.5px; font-weight:900; color:var(--brand-ink);
    }
    .brand-sub{
      margin:4px 0 0; color:var(--muted); font-size:13px;
    }
    .meta{
      text-align:right; min-width: 220px;
    }
    .meta .lbl{ color:var(--muted); font-size:12px; }
    .meta .val{ font-weight:800; font-size:16px; color:var(--brand-ink); }
    .meta .badge{
      display:inline-block; padding:6px 10px; border-radius:999px;
      border:1px solid var(--brand); color:var(--brand-ink); background:#fff5f0;
      font-weight:700; font-size:12px; margin-top:6px;
    }

    /* Body */
    .body{ padding: 22px 24px 8px; }
    .row{ display:flex; gap:18px; flex-wrap:wrap; }
    .col{ flex:1 1 280px; }
    .field{
      display:flex; gap:10px; align-items:flex-start; margin: 10px 0;
      padding:12px 14px; border:1px solid var(--border); border-radius:12px; background:#fff;
    }
    .field .k{ min-width:150px; color:var(--muted); font-weight:600; }
    .field .v{ font-weight:700; color:var(--brand-ink); }

    .highlight{
      margin-top:10px;
      padding:18px 16px; border-radius:12px; border:1px dashed var(--brand);
      background: #fff7f2;
    }
    .highlight .amount{
      font-size:26px; font-weight:900; color:var(--brand-ink);
    }

    /* Footer */
    .footer{
      display:flex; justify-content:space-between; align-items:flex-end;
      gap:22px; padding: 22px 24px;
      border-top:1px solid var(--border);
    }
    .sign{
      text-align:center; min-width:240px;
    }
    .sign .line{
      margin-top:36px; border-top:2px solid var(--ink);
    }
    .sign .lbl{ color:var(--muted); font-size:12px; margin-top:6px; }

    /* Print */
    @media print {
      body{ background:#fff; }
      .toolbar.no-print{ display:none !important; }
      .wrap{ margin:0; padding:0; }
      .card{ border:none; box-shadow:none; }
      @page{ size: A4 portrait; margin: 14mm; }
    }
  </style>
</head>
<body>

<div class="wrap">

  <div class="toolbar no-print">
    <button class="btn" onclick="window.print()">Imprimir comprobante</button>
  </div>

  <section class="card">
    <!-- ENCABEZADO -->
    <div class="header">
      <img class="brand-logo" src="logo4.png" alt="Logo Gym Cañones">
      <div class="brand-block">
        <h1 class="brand-title">Gym Cañones</h1>
        <p class="brand-sub">Comprobante de pago • Fitness & Strength</p>
      </div>
      <div class="meta">
        <div><span class="lbl">N° de Serie</span><br><span class="val"><?= h($row['et_id']) ?></span></div>
        <div style="margin-top:8px"><span class="lbl">Fecha de pago</span><br><span class="val"><?= h($row['paid_date']) ?></span></div>
        <div class="badge">Pago recibido</div>
      </div>
    </div>

    <!-- CUERPO -->
    <div class="body">
      <div class="row">
        <div class="col">
          <div class="field">
            <div class="k">Recibido de</div>
            <div class="v"><?= h($row['username']) ?></div>
          </div>
          <div class="field">
            <div class="k">Membresía</div>
            <div class="v"><?= h($row['planName']) ?></div>
          </div>
        </div>

        <div class="col">
          <div class="highlight">
            <div class="k" style="color:var(--muted); font-weight:700;">Importe</div>
            <div class="amount">Bs. <?= dineroBOB($row['amount'] ?? 0) ?></div>
            <div style="font-size:12px; color:var(--muted); margin-top:4px;">(Bolivianos)</div>
          </div>
        </div>
      </div>
    </div>

    <!-- PIE -->
    <div class="footer">
      <div style="color:var(--muted); font-size:12px;">
        Este documento es válido como recibo de pago. Conserva una copia para tu control.
      </div>
      <div class="sign">
        <div class="line"></div>
        <div class="lbl">Firma autorizada</div>
      </div>
    </div>
  </section>
</div>

</body>
</html>
