<?php
require '../../include/db_conn.php';
page_protect();

/* Validar entrada */
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['name'])) {
    echo "<script>alert('Solicitud inválida'); window.location='view_plan.php';</script>";
    exit;
}

$pid = trim($_POST['name']);

/* Desactivar el plan (active = 'no') usando consulta preparada */
$stmt = $con->prepare("UPDATE plan SET active = 'no' WHERE pid = ?");
if (!$stmt) {
    echo "<script>alert('Error preparando consulta: ".addslashes($con->error)."'); window.location='view_plan.php';</script>";
    exit;
}
$stmt->bind_param('s', $pid);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Mensaje correcto: Plan eliminado (desactivado)
    echo "<script>alert('Plan eliminado correctamente'); window.location='view_plan.php';</script>";
} else {
    // Puede ser que no exista o ya estuviera desactivado
    echo "<script>alert('No se pudo eliminar el plan (no encontrado o ya desactivado)'); window.location='view_plan.php';</script>";
}
$stmt->close();
