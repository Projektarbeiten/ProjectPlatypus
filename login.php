<?php
session_start();
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
	header("Location: index");
}
require './phpFunctions/databaseConnection.php';
require './phpFunctions/sqlQueries.php';
$conn = buildConnection("./");


?>
<!DOCTYPE html>
<html>

<head>
	<meta charset='utf-8'>
	<meta http-equiv='X-UA-Compatible' content='IE=edge'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel="stylesheet" href="./css/styles.css">
	<title>Anmelden</title>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-6">
				<div id="login-form">
					<form method="post">
						<label for="t_username">Email:</label> <br>
						<input type="email" name="t_username" value="
							<?php if (isset($_POST['t_username'])) {
								echo $_POST['t_username'];
							} ?>" pattern="(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|\u0022(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\u0022)@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])"> <br>
						<label for="t_password" id="login-label">Password:</label> <br>
						<input type="password" name="t_password"> <br>
						<button type="submit" class="login-button">Anmelden</button> <br>

						<?php
						if (isset($_POST['t_username'], $_POST['t_password'])) {
							try {
								if ($result = login($_POST['t_username'], $conn)) {
									if (password_verify($_POST['t_password'], $result[0])) {
										session_regenerate_id();
										$_SESSION['loggedin'] = true;
										$_SESSION['uid'] = $result[1];
										header("Location: home");
									} else {
										echo "<p style='color:red'>Incorrect E-Mail and/or password!</p>";
									}
								} else {
									echo "<p style='color:red'>Incorrect E-Mail and/or password!</p>";
								}
							} catch (PDOException $e) {
								echo 'Error: ' . $e->getMessage();
							}
						}
						?>
						<a href="" id="login-page-link">Passwort vergessen?</a><!-- TODO: Verlinkung einfügen -->
					</form>
					<hr>
					<p style="text-align: center">Neuer Benutzer?</p>
					<a href="register.php">
						<button class="login-button">Registrieren</button>
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Footer -->
	<div id="login-footer">
		<?php
		require("footer.php");
		?>
	</div>
</body>

</html>