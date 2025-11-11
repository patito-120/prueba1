<?php
require '../../include/db_conn.php';
page_protect();

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Método no permitido');window.location.href='new_entry.php';</script>";
    exit;
}

// ====== 1) Tomar y validar datos ======
$memID  = trim($_POST['m_id'] ?? '');
$uname  = trim($_POST['u_name'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$dob    = trim($_POST['dob'] ?? '');
$phn    = trim($_POST['mobile'] ?? '');
$jdate  = trim($_POST['jdate'] ?? '');
$plan   = trim($_POST['plan'] ?? '');

// Correo OPCIONAL
$email  = trim($_POST['email'] ?? ''); // puede venir vacío
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Correo inválido. Corrígelo o déjalo vacío.');window.history.back();</script>";
    exit;
}

// Saneado para SQL
$memID  = mysqli_real_escape_string($con, $memID);
$uname  = mysqli_real_escape_string($con, $uname);
$gender = mysqli_real_escape_string($con, $gender);
$dob    = mysqli_real_escape_string($con, $dob);
$phn    = mysqli_real_escape_string($con, $phn);
$jdate  = mysqli_real_escape_string($con, $jdate);
$plan   = mysqli_real_escape_string($con, $plan);
$email  = mysqli_real_escape_string($con, $email);

// Validaciones mínimas
if ($memID === '' || $uname === '' || $gender === '' || $dob === '' || $phn === '' || $jdate === '' || $plan === '') {
    echo "<script>alert('Faltan datos obligatorios.');window.history.back();</script>";
    exit;
}

mysqli_begin_transaction($con);

try {
    // ====== 2) Insertar usuario (email opcional con NULLIF) ======
    $qUser = "
        INSERT INTO users (username, gender, mobile, email, dob, joining_date, userid)
        VALUES ('$uname', '$gender', '$phn', NULLIF('$email',''), '$dob', '$jdate', '$memID')
    ";
    if (!mysqli_query($con, $qUser)) {
        // 1062 = unique violation (por si email es único y ya existe)
        if (mysqli_errno($con) == 1062) {
            throw new Exception('Ese correo ya está registrado con otro miembro.');
        }
        throw new Exception('No se pudo crear el usuario: '.mysqli_error($con));
    }

    // ====== (PASO 1) Asegurar fila en address con valores vacíos ======
    // Esto evita errores al editar si aún no hay dirección cargada.
    $qAddr = "INSERT INTO address (id, streetName, state, city, zipcode)
              VALUES ('$memID', '', '', '', '')";
    if (!mysqli_query($con, $qAddr)) {
        throw new Exception('No se pudo crear la dirección inicial: '.mysqli_error($con));
    }

    // ====== 3) Obtener info del plan ======
    $qPlan   = "SELECT * FROM plan WHERE pid='$plan'";
    $rPlan   = mysqli_query($con, $qPlan);
    if (!$rPlan || mysqli_num_rows($rPlan) === 0) {
        throw new Exception('Plan no encontrado.');
    }
    $value   = mysqli_fetch_row($rPlan);
    // Asumiendo que $value[3] = meses de duración
    $months  = (int)$value[3];

    date_default_timezone_set('America/Bogota');
    $cdate      = date("Y-m-d");
    $expiredate = date("Y-m-d", strtotime("+".$months." Months"));

    // ====== 4) Inscribir al plan ======
    $qEnroll = "
        INSERT INTO enrolls_to (pid, uid, paid_date, expire, renewal)
        VALUES ('$plan', '$memID', '$cdate', '$expiredate', 'yes')
    ";
    if (!mysqli_query($con, $qEnroll)) {
        throw new Exception('No se pudo inscribir al plan: '.mysqli_error($con));
    }

    // ====== 5) Crear registro de salud ======
    $qHealth = "INSERT INTO health_status (uid) VALUES ('$memID')";
    if (!mysqli_query($con, $qHealth)) {
        throw new Exception('No se pudo crear el registro de salud: '.mysqli_error($con));
    }

    // Todo OK
    mysqli_commit($con);
    echo "<script>alert('Miembro agregado correctamente');window.location.href='new_entry.php';</script>";
    exit;

} catch (Exception $e) {
    mysqli_rollback($con);

    // Limpieza defensiva por si alguna tabla no está en InnoDB
    mysqli_query($con, "DELETE FROM enrolls_to WHERE uid='$memID'");
    mysqli_query($con, "DELETE FROM health_status WHERE uid='$memID'");
    mysqli_query($con, "DELETE FROM address WHERE id='$memID'");
    mysqli_query($con, "DELETE FROM users WHERE userid='$memID'");

    $msg = addslashes($e->getMessage());
    echo "<script>alert('Error: $msg');window.history.back();</script>";
    exit;
}
