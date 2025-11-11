<?php
require '../../include/db_conn.php';
page_protect();

/* -------- Helpers -------- */
function p($key, $default = '') { return isset($_POST[$key]) ? trim($_POST[$key]) : $default; }
function nullIfEmpty($v) { return ($v === '' ? null : $v); }

/* Solo por POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Método no permitido');window.location.href='view_mem.php';</script>";
    exit;
}

mysqli_begin_transaction($con);

try {
    /* 1) Tomar datos del formulario */
    $uid     = p('uid');
    $uname   = p('uname');
    $gender  = p('gender');
    $mobile  = p('phone');
    $dob     = nullIfEmpty(p('dob'));
    $jdate   = nullIfEmpty(p('jdate'));

    // Dirección (solo llega streetName en tu UI; el resto opcional)
    $stname  = p('stname');
    $state   = nullIfEmpty(p('state'));
    $city    = nullIfEmpty(p('city'));
    $zipcode = nullIfEmpty(p('zipcode'));

    // Salud (opcionales en tu UI)
    $calorie = nullIfEmpty(p('calorie'));
    $height  = nullIfEmpty(p('height'));
    $weight  = nullIfEmpty(p('weight'));
    $fat     = nullIfEmpty(p('fat'));
    $remarks = nullIfEmpty(p('remarks'));

    if ($uid === '') {
        throw new Exception('Falta el ID de usuario (uid).');
    }

    /* 2) Cargar valores actuales para respaldos (por si algún campo vino vacío) */
    $stmt = $con->prepare("SELECT username, gender, mobile, dob, joining_date FROM users WHERE userid = ?");
    if (!$stmt) throw new Exception($con->error);
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $current = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$current) {
        throw new Exception('Usuario no encontrado.');
    }

    // Rellenar respaldos: si algún campo vino vacío, usamos el de DB
    if ($uname  === '') $uname  = $current['username'];
    if ($gender === '') $gender = $current['gender'];
    if ($mobile === '') $mobile = $current['mobile'];
    if ($dob    === null) $dob  = $current['dob'];            // puede quedar null si en DB es null
    if ($jdate  === null) $jdate= $current['joining_date'];   // idem

    /* 3) Actualizar USERS */
    $stmt = $con->prepare("
        UPDATE users
           SET username = ?, gender = ?, mobile = ?, dob = ?, joining_date = ?
         WHERE userid = ?
    ");
    if (!$stmt) throw new Exception($con->error);
    $stmt->bind_param('ssssss', $uname, $gender, $mobile, $dob, $jdate, $uid);
    if (!$stmt->execute()) throw new Exception($stmt->error);
    $stmt->close();

    /* 4) Upsert ADDRESS (crea si no existe) */
    $stmt = $con->prepare("SELECT 1 FROM address WHERE id = ?");
    if (!$stmt) throw new Exception($con->error);
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $stmt->store_result();
    $hasAddress = $stmt->num_rows > 0;
    $stmt->close();

    if ($hasAddress) {
        $stmt = $con->prepare("
            UPDATE address
               SET streetName = ?, state = ?, city = ?, zipcode = ?
             WHERE id = ?
        ");
        if (!$stmt) throw new Exception($con->error);
        $stmt->bind_param('sssss', $stname, $state, $city, $zipcode, $uid);
    } else {
        $stmt = $con->prepare("
            INSERT INTO address (streetName, state, city, zipcode, id)
            VALUES (?, ?, ?, ?, ?)
        ");
        if (!$stmt) throw new Exception($con->error);
        $stmt->bind_param('sssss', $stname, $state, $city, $zipcode, $uid);
    }
    if (!$stmt->execute()) throw new Exception($stmt->error);
    $stmt->close();

    /* 5) Upsert HEALTH_STATUS (crea si no existe) */
    $stmt = $con->prepare("SELECT 1 FROM health_status WHERE uid = ?");
    if (!$stmt) throw new Exception($con->error);
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $stmt->store_result();
    $hasHealth = $stmt->num_rows > 0;
    $stmt->close();

    if ($hasHealth) {
        $stmt = $con->prepare("
            UPDATE health_status
               SET calorie = ?, height = ?, weight = ?, fat = ?, remarks = ?
             WHERE uid = ?
        ");
        if (!$stmt) throw new Exception($con->error);
        $stmt->bind_param('ssssss', $calorie, $height, $weight, $fat, $remarks, $uid);
    } else {
        $stmt = $con->prepare("
            INSERT INTO health_status (uid, calorie, height, weight, fat, remarks)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) throw new Exception($con->error);
        $stmt->bind_param('ssssss', $uid, $calorie, $height, $weight, $fat, $remarks);
    }
    if (!$stmt->execute()) throw new Exception($stmt->error);
    $stmt->close();

    mysqli_commit($con);
    echo "<script>alert('Miembro Actualizado Satisfactoriamente');window.location='view_mem.php';</script>";
    exit;

} catch (Exception $e) {
    mysqli_rollback($con);
    $msg = addslashes($e->getMessage());
    echo "<script>alert('Error al actualizar: {$msg}');history.back();</script>";
    exit;
}
