<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gym Cañones | Detalles de Miembros</title>

  <!-- CSS base del sistema -->
  <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
  <link rel="stylesheet" href="../../css/dashMain.css">
  <link rel="stylesheet" href="../../css/entypo.css">
  <link rel="stylesheet" href="a1style.css">

  <!-- CSS AISLADO SOLO PARA ESTA PÁGINA -->
  <link rel="stylesheet" href="../../css/table_members.css">

  <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
  <script src="../../js/Script.js"></script>
</head>

<body class="page-body page-fade" onload="collapseSidebar()">
<div class="page-container sidebar-collapsed" id="navbarcollapse">

  <div class="sidebar-menu">
    <header class="logo-env">
      <div class="logo">
        <a href="main.php">
          <img src="../../images/logo2.png" alt="" width="192" height="80">
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

  <div class="main-content ff-tablelist">
    <div class="row">
      <div class="col-md-6 col-sm-8 clearfix"></div>
      <div class="col-md-6 col-sm-4 clearfix hidden-xs">
        <ul class="list-inline links-list pull-right ff-links-list">
          <li>Bienvenid@ <?php echo htmlspecialchars($_SESSION['full_name']); ?></li>
          <li><a class="ff-pill" href="logout.php">Cerrar Sesión <i class="entypo-logout right"></i></a></li>
        </ul>
      </div>
    </div>

    <h3 class="ff-title">Detalles de Miembros</h3>
    <div class="ff-underline"></div>

    <!-- Toolbar -->
    <div class="ff-toolbar" role="region" aria-label="Controles de tabla">
      <div class="ff-search">
        <input type="text" id="ffSearch" placeholder="Buscar por nombre, ID, contacto, etc." aria-label="Buscar">
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
      <table class="ff-table" id="membersTable">
        <thead>
          <tr>
            <th>No</th>
            <th>Fecha de Expiración de Membresía</th>
            <th>ID Miembro</th>
            <th>Nombre</th>
            <th>Contacto</th>
            <th>Género</th>
            <th>Fecha de Ingreso</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $result = mysqli_query($con, "SELECT * FROM users ORDER BY joining_date");
            $sno = 1;

            if ($result && mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                $uid = $row['userid'];
                $result1 = mysqli_query($con, "SELECT * FROM enrolls_to WHERE uid='$uid' AND renewal='yes' LIMIT 1");
                if ($result1 && mysqli_num_rows($result1) === 1) {
                  $row1 = mysqli_fetch_assoc($result1);

                  echo '<tr>';
                  echo '<td>'. $sno .'</td>';
                  echo '<td>'. htmlspecialchars($row1['expire']) .'</td>';
                  echo '<td>'. htmlspecialchars($row['userid']) .'</td>';
                  echo '<td>'. htmlspecialchars($row['username']) .'</td>';
                  echo '<td>'. htmlspecialchars($row['mobile']) .'</td>';
                  echo '<td>'. htmlspecialchars($row['gender']) .'</td>';
                  echo '<td>'. htmlspecialchars($row['joining_date']) .'</td>';
                  echo '<td>
                          <form action="viewall_detail.php" method="post" style="margin:0">
                            <input type="hidden" name="name" value="'. htmlspecialchars($uid) .'">
                            <button type="submit" class="ff-btn ff-btn--info">Ver Todo</button>
                          </form>
                        </td>';
                  echo '</tr>';

                  $sno++;
                }
              }
            } else {
              echo '<tr><td colspan="8" class="ff-empty">No hay miembros para mostrar.</td></tr>';
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

<!-- JS de Búsqueda + Paginación -->
<script>
(function(){
  const scope = document.querySelector('.ff-tablelist');
  if(!scope) return;

  const table = scope.querySelector('#membersTable');
  const tbody = table.tBodies[0];
  const allRows = Array.from(tbody.rows); // snapshot original de filas generadas por PHP
  const elSearch = scope.querySelector('#ffSearch');
  const elPageSize = scope.querySelector('#ffPageSize');
  const pager = scope.querySelector('#ffPager');

  let query = '';
  let page = 1;
  let pageSize = parseInt(elPageSize.value, 10) || 10;

  function filterRows() {
    if (!query) return allRows;
    const q = query.toLowerCase();
    return allRows.filter(r => r.textContent.toLowerCase().includes(q));
  }

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

    const rowsInfo = document.createElement('div');
    rowsInfo.className = 'ff-rows-info';
    const start = total ? ((page-1)*pageSize + 1) : 0;
    const end = Math.min(page*pageSize, total);
    rowsInfo.textContent = `${start}–${end} de ${total}`;

    const nav = document.createElement('div');
    nav.className = 'ff-page-nav';

    const totalPages = Math.max(1, Math.ceil(total / pageSize));
    const windowSize = 5;
    const from = Math.max(1, Math.min(page - Math.floor(windowSize/2), totalPages - windowSize + 1));
    const to = Math.min(totalPages, from + windowSize - 1);

    nav.appendChild(btn('‹', page <= 1, page-1));
    for (let p = from; p <= to; p++) {
      nav.appendChild(btn(String(p), false, p, p === page));
    }
    nav.appendChild(btn('›', page >= totalPages, page+1));

    container.appendChild(rowsInfo);
    container.appendChild(nav);
    pager.appendChild(container);
  }

  function render() {
    const rows = filterRows();
    const total = rows.length;
    const pages = Math.max(1, Math.ceil(total / pageSize));
    if (page > pages) page = pages;

    tbody.innerHTML = '';
    const start = (page - 1) * pageSize;
    const slice = rows.slice(start, start + pageSize);

    // Re-indexar columna "No"
    slice.forEach((tr, idx) => {
      const clone = tr.cloneNode(true);
      if (clone.cells.length) clone.cells[0].textContent = start + idx + 1;
      tbody.appendChild(clone);
    });

    renderPager(total, pages);
  }

  elSearch.addEventListener('input', (e) => { query = e.target.value.trim(); page = 1; render(); });
  elPageSize.addEventListener('change', (e) => { pageSize = parseInt(e.target.value, 10) || 10; page = 1; render(); });

  render();
})();
</script>

</body>
</html>
