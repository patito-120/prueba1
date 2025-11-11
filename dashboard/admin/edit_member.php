<?php
require '../../include/db_conn.php';
page_protect();

if (!isset($_POST['name'])) {
    echo "<script>window.location.href='view_mem.php';</script>";
    exit;
}

$memid = $_POST['name'];

/* ========= Carga segura de datos ========= */
$name = $gender = $mobile = $email = $dob = $jdate = '';
$streetname = $state = $city = $zipcode = '';
$calorie = $height = $weight = $fat = $remarks = '';

$sql = "
    SELECT 
        u.userid, u.username, u.gender, u.mobile, u.email, u.dob, u.joining_date,
        a.streetName, a.state, a.city, a.zipcode,
        h.calorie, h.height, h.weight, h.fat, h.remarks
    FROM users u
    LEFT JOIN address a ON u.userid = a.id
    LEFT JOIN health_status h ON u.userid = h.uid
    WHERE u.userid = '" . mysqli_real_escape_string($con, $memid) . "'
    LIMIT 1
";

$result = mysqli_query($con, $sql);
if ($result && mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    $name   = $row['username']      ?? '';
    $gender = $row['gender']        ?? '';
    $mobile = $row['mobile']        ?? '';
    $email  = $row['email']         ?? '';
    $dob    = $row['dob']           ?? '';
    $jdate  = $row['joining_date']  ?? '';

    $streetname = $row['streetName'] ?? '';
    $state      = $row['state']      ?? '';
    $city       = $row['city']       ?? '';
    $zipcode    = $row['zipcode']    ?? '';

    $calorie = $row['calorie'] ?? '';
    $height  = $row['height']  ?? '';
    $weight  = $row['weight']  ?? '';
    $fat     = $row['fat']     ?? '';
    $remarks = $row['remarks'] ?? '';
} else {
    echo "<script>alert('No se encontró el miembro.'); window.history.back();</script>";
    exit;
}

/* Fechas para inputs date (YYYY-MM-DD) */
$dob_value   = ($dob && strtotime($dob))     ? date('Y-m-d', strtotime($dob))     : '';
$jdate_value = ($jdate && strtotime($jdate)) ? date('Y-m-d', strtotime($jdate))   : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gym Cañones | Editar Miembro</title>

    <!-- CSS base del sistema -->
    <link rel="stylesheet" href="../../css/style.css" id="style-resource-5">
    <link rel="stylesheet" href="../../css/dashMain.css">
    <link rel="stylesheet" href="../../css/entypo.css">
    <link rel="stylesheet" href="a1style.css">

    <!-- CSS específico de esta página (scope .ff-edit-member) -->
    <link rel="stylesheet" href="../../css/edit_member.css?v=3">

    <link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
    <script src="../../js/Script.js" type="text/javascript"></script>
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

    <!-- 👇 OJO: usamos ff-edit-member como scope -->
    <div class="main-content ff-edit-member">
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
            <h3 class="ff-page-title">Editar Detalles de Miembro</h3>
            <div class="ff-page-underline"></div>
        </div>

        <!-- Tarjeta -->
        <div class="ff-card ff-card--narrow">
            <div class="ff-card__header">Datos del Miembro</div>
            <div class="ff-card__body">
                <form id="form1" name="form1" method="post" action="edit_mem_submit.php" class="ff-form">
                    <div class="ff-row">
                        <div class="ff-col">
                            <label class="ff-label">ID de Usuario</label>
                            <input class="ff-input" type="text" name="uid" readonly required
                                   value="<?php echo htmlspecialchars($memid); ?>">
                        </div>
                        <div class="ff-col">
                            <label class="ff-label">Nombre</label>
                            <input class="ff-input" type="text" name="uname"
                                   value="<?php echo htmlspecialchars($name); ?>">
                        </div>
                    </div>

                    <div class="ff-row">
                        <div class="ff-col">
                            <label class="ff-label">Género</label>
                            <select class="ff-input" name="gender" required>
                                <option value="">-- Favor Seleccionar --</option>
                                <option value="Hombre" <?php echo ($gender==='Hombre'?'selected':''); ?>>Hombre</option>
                                <option value="Mujer"  <?php echo ($gender==='Mujer' ?'selected':''); ?>>Mujer</option>
                            </select>
                        </div>
                        <div class="ff-col">
                            <label class="ff-label">Móvil</label>
                            <input class="ff-input" type="text" name="phone" maxlength="15"
                                   value="<?php echo htmlspecialchars($mobile); ?>">
                        </div>
                    </div>

                    <div class="ff-row">
                        <div class="ff-col">
                            <label class="ff-label">Fecha de Nacimiento</label>
                            <input class="ff-input" type="date" name="dob" value="<?php echo $dob_value; ?>">
                        </div>
                        <div class="ff-col">
                            <label class="ff-label">Fecha de Ingreso</label>
                            <input class="ff-input" type="date" name="jdate" value="<?php echo $jdate_value; ?>">
                        </div>
                    </div>

                    <div class="ff-row">
                        <div class="ff-col">
                            <label class="ff-label">Dirección</label>
                            <input class="ff-input" type="text" name="stname"
                                   placeholder="Calle, número, etc."
                                   value="<?php echo htmlspecialchars($streetname); ?>">
                        </div>
                    </div>

                    <!-- (Opcionales: descomentar si los usas)
                    <div class="ff-row">
                        <div class="ff-col">
                            <label class="ff-label">Departamento</label>
                            <input class="ff-input" type="text" name="state" value="<?php //echo htmlspecialchars($state); ?>">
                        </div>
                        <div class="ff-col">
                            <label class="ff-label">Ciudad</label>
                            <input class="ff-input" type="text" name="city" value="<?php //echo htmlspecialchars($city); ?>">
                        </div>
                    </div>

                    <div class="ff-row">
                        <div class="ff-col">
                            <label class="ff-label">Código Postal</label>
                            <input class="ff-input" type="text" name="zipcode" value="<?php //echo htmlspecialchars($zipcode); ?>">
                        </div>
                    </div>
                    -->

                    <div class="ff-actions">
                        <button class="ff-btn ff-btn--primary" type="submit" name="submit" id="submit">Actualizar</button>
                        <button class="ff-btn ff-btn--ghost"   type="reset"  name="reset"  id="reset">Borrar</button>
                    </div>
                </form>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>
</div>

</body>
</html>
