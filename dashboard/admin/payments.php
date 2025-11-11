<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Pagos</title>

  <!-- CSS base -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">
  <!-- CSS específico de esta página -->
  <link rel="stylesheet" href="../../css/payments.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js"></script>

  <style>
    .page-container .sidebar-menu #main-menu li#paymnt > a{
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
          <img src="../../images/logo2.png" alt="FitFusion" width="192" height="80" />
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

  <div class="main-content ff-payments">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenid@ <?php echo htmlspecialchars($_SESSION['full_name'] ?? ''); ?></li>
          <li>
            <a href="logout.php" class="ff-pill">Cerrar Sesión <i class="entypo-logout right"></i></a>
          </li>
        </ul>
      </div>
    </div>

    <h2 class="ff-title">Pagos</h2>
    <div class="ff-underline"></div>

    <!-- Toolbar: buscador + tamaño de página -->
    <div class="ff-toolbar" role="region" aria-label="Controles de tabla de pagos">
      <div class="ff-search">
        <input id="ffSearch" type="text" placeholder="Buscar por cualquier columna..." aria-label="Buscar">
      </div>
      <div class="ff-page-size">
        Mostrar
        <select id="ffPageSize" aria-label="Filas por página">
          <option value="10" selected>10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        filas
      </div>
    </div>

    <div class="ff-table-wrap">
      <table class="ff-table ff-table-compact" id="paymentsTable">
        <thead>
          <tr>
            <th>No</th>
            <th>Fecha de Expiración</th>
            <th>Nombre</th>
            <th>ID de Miembro</th>
            <th>Teléfono</th>
            <th>Género</th>
            <th class="ff-col-right">Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $query  = "SELECT * FROM enrolls_to WHERE renewal='yes' ORDER BY expire";
            $result = mysqli_query($con, $query);
            $sno    = 1;

            if ($result && mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $uid    = $row['uid'];
                $planid = $row['pid'];

                $result1 = mysqli_query($con, "SELECT * FROM users WHERE userid='$uid' LIMIT 1");
                if ($result1 && mysqli_num_rows($result1) === 1) {
                  $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);

                  $gender = $row1['gender'];
                  $badgeClass = ($gender === 'Mujer') ? 'ff-badge ff-badge-pink' : 'ff-badge ff-badge-blue';

                  echo '<tr>';
                    echo '<td class="ff-col-number">'. $sno .'</td>';
                    echo '<td>'. htmlspecialchars($row['expire']) .'</td>';
                    echo '<td>'. htmlspecialchars($row1['username']) .'</td>';
                    echo '<td>'. htmlspecialchars($row1['userid']) .'</td>';
                    echo '<td>'. htmlspecialchars($row1['mobile']) .'</td>';
                    echo '<td><span class="'.$badgeClass.'">'. htmlspecialchars($gender) .'</span></td>';
                    echo '<td class="ff-col-right">';
                      echo '<form action="make_payments.php" method="post" class="ff-inline">';
                        echo '<input type="hidden" name="userID" value="'. htmlspecialchars($uid) .'"/>';
                        echo '<input type="hidden" name="planID" value="'. htmlspecialchars($planid) .'"/>';
                        echo '<button type="submit" class="ff-btn ff-btn-orange ff-btn-xs">Agregar Pago</button>';
                      echo '</form>';
                    echo '</td>';
                  echo '</tr>';

                  $sno++;
                }
              }
            } else {
              echo '<tr><td colspan="7" class="ff-empty">No hay pagos para mostrar.</td></tr>';
            }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Paginador -->
    <div id="ffPager" class="ff-pagination" aria-live="polite"></div>

    <?php include('footer.php'); ?>
  </div>
</div>

<!-- JS de búsqueda + paginación (vanilla) -->
<script>
(function(){
  const scope = document.querySelector('.ff-payments');
  if(!scope) return;

  const table = scope.querySelector('#paymentsTable');
  const tbody = table.tBodies[0];
  const allRows = Array.from(tbody.rows); // snapshot original
  const elSearch = scope.querySelector('#ffSearch');
  const elPageSize = scope.querySelector('#ffPageSize');
  const pager = scope.querySelector('#ffPager');

  let query = '';
  let page = 1;
  let pageSize = parseInt(elPageSize.value, 10) || 10;

  // Filtro por texto en cualquier columna
  function filterRows() {
    if (!query) return allRows;
    const q = query.toLowerCase();
    return allRows.filter(r => r.textContent.toLowerCase().includes(q));
  }

  // Render del paginador
  function renderPager(total, pages) {
    pager.innerHTML = '';
    const container = document.createElement('div');
    container.className = 'ff-pager';

    function btn(label, disabled, goTo, isActive=false) {
      const b = document.createElement('button');
      b.type = 'button';
      b.textContent = label;
      b.className = 'ff-page-btn';
      if (isActive) b.classList.add('is-active');
      if (disabled) b.disabled = true;
      b.addEventListener('click', () => { page = goTo; render(); });
      return b;
    }

    // Info de filas
    const rowsInfo = document.createElement('div');
    rowsInfo.className = 'ff-rows-info';
    const start = total ? ((page-1)*pageSize + 1) : 0;
    const end = Math.min(page*pageSize, total);
    rowsInfo.textContent = `${start}–${end} de ${total}`;

    // Navegación
    const nav = document.createElement('div');
    nav.className = 'ff-page-nav';

    const pagesToShow = 5;
    const totalPages = Math.max(1, pages);
    const from = Math.max(1, Math.min(page - Math.floor(pagesToShow/2), totalPages - pagesToShow + 1));
    const to = Math.min(totalPages, from + pagesToShow - 1);

    nav.appendChild(btn('‹', page <= 1, page-1));
    for (let p = from; p <= to; p++) {
      nav.appendChild(btn(String(p), false, p, p === page));
    }
    nav.appendChild(btn('›', page >= totalPages, page+1));

    container.appendChild(rowsInfo);
    container.appendChild(nav);
    pager.appendChild(container);
  }

  // Render de la tabla (paginada)
  function render() {
    const rows = filterRows();
    const total = rows.length;
    const pages = Math.max(1, Math.ceil(total / pageSize));
    if (page > pages) page = pages;

    tbody.innerHTML = '';
    const start = (page - 1) * pageSize;
    const slice = rows.slice(start, start + pageSize);

    // Re-numerar columna "No" (primera columna)
    slice.forEach((tr, idx) => {
      const clone = tr.cloneNode(true);
      if (clone.cells.length) clone.cells[0].textContent = start + idx + 1;
      tbody.appendChild(clone);
    });

    renderPager(total, pages);
  }

  // Eventos
  elSearch.addEventListener('input', (e) => { query = e.target.value.trim(); page = 1; render(); });
  elPageSize.addEventListener('change', (e) => { pageSize = parseInt(e.target.value, 10) || 10; page = 1; render(); });

  // Primera renderización
  render();
})();
</script>

</body>
</html>
