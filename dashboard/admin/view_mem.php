<?php
require '../../include/db_conn.php';
page_protect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gym Cañones | Vista de Miembro</title>
    <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
    <script type="text/javascript" src="../../js/Script.js"></script>
    <link rel="stylesheet" href="../../css/dashMain.css">
    <link rel="stylesheet" type="text/css" href="../../css/entypo.css">
    <link href="a1style.css" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
    <style>
        /* Activo del menú lateral (como ya tenías) */
        .page-container .sidebar-menu #main-menu li#hassubopen > a {
            background-color: #2b303a;
            color: #ffffff;
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

    <div class="main-content">

        <div class="row">
            <div class="col-md-6 col-sm-8 clearfix"></div>
            <div class="col-md-6 col-sm-4 clearfix hidden-xs">
                <ul class="list-inline links-list pull-right ff-links-list">
                    <li>Bienvenido <?php echo $_SESSION['full_name']; ?></li>
                    <li>
                        <a href="logout.php" class="ff-pill">
                            Cerrar Sesión <i class="entypo-logout right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <h3 class="ff-page-title">Editar Miembro</h3>
        <span class="ff-title-underline"></span>

        <!-- Toolbar: buscador + tamaño de página -->
        <div class="ff-table-toolbar">
            <div class="ff-search">
                <input type="text" id="ffSearch" placeholder="Buscar en la tabla..." aria-label="Buscar">
            </div>
            <div class="ff-page-size">
                Mostrar
                <select id="ffPageSize" aria-label="Tamaño de página">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                filas
            </div>
        </div>

        <!-- Tabla -->
        <div class="ff-table-wrap">
            <table class="ff-table ff-table--members" id="memberTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Fecha de Expiración</th>
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
                    $query  = "SELECT * FROM users ORDER BY joining_date";
                    $result = mysqli_query($con, $query);
                    $sno    = 1;

                    if (mysqli_affected_rows($con) != 0) {
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $uid   = $row['userid'];
                            $result1 = mysqli_query($con, "SELECT * FROM enrolls_to WHERE uid='$uid' AND renewal='yes'");
                            if (mysqli_affected_rows($con) == 1) {
                                while ($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>".$sno."</td>";
                                    echo "<td>" . $row1['expire'] . "</td>";
                                    echo "<td>" . $row['userid'] . "</td>";
                                    echo "<td>" . $row['username'] . "</td>";
                                    echo "<td>" . $row['mobile'] . "</td>";
                                    echo "<td>" . $row['gender'] . "</td>";
                                    echo "<td>" . $row['joining_date'] ."</td>";
                                    $sno++;
                                    echo "<td>
                                            <div class='ff-actions'>
                                                <form action='read_member.php' method='post'>
                                                    <input type='hidden' name='name' value='" . $uid . "'/>
                                                    <input type='submit' class='ff-btn ff-btn--info' value='Ver Historia'/>
                                                </form>
                                                <form action='edit_member.php' method='post'>
                                                    <input type='hidden'  name='name' value='" . $uid . "'/>
                                                    <input type='submit' class='ff-btn ff-btn--primary' value='Editar'/>
                                                </form>
                                                <form action='del_member.php' method='post' onsubmit='return ConfirmDelete()'>
                                                    <input type='hidden' name='name' value='" . $uid . "'/>
                                                    <input type='submit' class='ff-btn ff-btn--danger' value='Eliminar'/>
                                                </form>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                }
                            }
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div id="memberPager" class="ff-pagination"></div>

        <?php include('footer.php'); ?>
    </div>
</div>

<script>
function ConfirmDelete(){
    return confirm("¿Seguro que deseas eliminar este usuario?");
}

/* Buscador + paginación vanilla */
(function(){
  const table = document.getElementById('memberTable');
  if(!table) return;

  const tbody = table.tBodies[0];
  const allRows = Array.from(tbody.rows);  // snapshot original
  const elSearch = document.getElementById('ffSearch');
  const elPageSize = document.getElementById('ffPageSize');
  const pager = document.getElementById('memberPager');

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

    function btn(label, disabled, goTo) {
      const b = document.createElement('button');
      b.textContent = label;
      b.className = 'ff-page-btn';
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

    nav.appendChild(btn('‹', page <= 1, page-1));

    const maxToShow = 5;
    let from = Math.max(1, page - Math.floor(maxToShow/2));
    let to = Math.min(pages, from + maxToShow - 1);
    from = Math.max(1, to - maxToShow + 1);

    for (let p = from; p <= to; p++) {
      const b = btn(p, false, p);
      if (p === page) b.classList.add('is-active');
      nav.appendChild(b);
    }
    nav.appendChild(btn('›', page >= pages, page+1));

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

    slice.forEach((tr, idx) => {
      const clone = tr.cloneNode(true);
      if (clone.cells.length) clone.cells[0].textContent = start + idx + 1; // re-número
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
