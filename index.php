<?php
session_start();
if (isset($_SESSION["user_data"])) {
	header("location:./dashboard/admin/");
	exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Gym Cañones</title>
	<link rel="stylesheet" href="./css/login_modern.css">
	<link rel="shortcut icon" href="images/icono.png" type="image/x-icon">
</head>
<body>

<div class="login-container">
	<div class="login-card">

		<div class="login-header">
			<img src="images/logo2.png" alt="FITFUSION" class="logo">
			<h2>Bienvenido a <span>Gym Cañones</span></h2>
			<p>Inicia sesión para continuar</p>
		</div>

		<form action="secure_login.php" method="post" class="login-form">
			<div class="form-group">
				<label for="user_id_auth">Usuario</label>
				<div class="input-box">
					<i class="icon">&#128100;</i>
					<input type="text" name="user_id_auth" id="user_id_auth" placeholder="Ingresa tu usuario" required>
				</div>
			</div>

			<div class="form-group">
				<label for="pass_key">Contraseña</label>
				<div class="input-box">
					<i class="icon">&#128274;</i>
					<input type="password" name="pass_key" id="pass_key" placeholder="********" required>
				</div>
			</div>

			<button type="submit" name="btnLogin" class="btn">Ingresar</button>

			<div class="bottom-links">
				<a href="forgot_password.php">¿Olvidaste tu contraseña?</a>
			</div>
		</form>

	</div>
</div>

<footer>
	<p>© <?php echo date('Y'); ?> Gym Cañones — Todos los derechos reservados</p>
</footer>

</body>
</html>
