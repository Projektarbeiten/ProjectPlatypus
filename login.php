<?php
session_start();

// Verbindung zur Datenbank
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
	<div class="login-div">
		<h3>Anmelden</h3>
		<hr>
		<form id="login-form">
			<label for="t_username">Username:</label> <br>
			
			<input type="email" name="t_username" value="
				<?php
				// Sollte der Login fehlschlagen, wird die email in dem Feld gespeichert 
				if (isset($_POST['t_username'])) {
					echo $_POST['t_username'];
				} ?>" pattern="(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|\u0022(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\u0022)@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])"> <br>
			<label for="t_password" id="login-label">Password:</label> <br>
			<input type="password" name="t_password"> <br>
			<button type="submit" id="login-button">Anmelden</button> <br>

			<?php
			// Sobald das formular gesendet wird, wird eine sql query ausgeführt welche nach der Mail sucht und die Passwörter abgleicht. Ist das Passwort richtig, wird die User Session erstellt.
			if (isset($_POST['t_username'], $_POST['t_password'])) {
				try {
					if ($result = login($conn, $_POST['t_username'])) {
						if (password_verify($_POST['t_username'], $result[1])) {
							session_regenerate_id();
							$_SESSION['loggedin'] = true;
							$_SESSION['u_id'] = $row["u_id"];
							header("Location: home");
						} else {
							echo '<p>Incorrect E-Mail and/or password!</p>';
						}
					} else {
						echo '<p>Incorrect E-Mail and/or password!</p>';
					}
				} catch (PDOException $e) {
					echo 'Error: ' . $e->getMessage();
				}
			}
			?>
		</form>
		<a href="" id="login-page-link">Passwort vergessen?</a><!-- TODO: Verlinkung einfügen --><br>
		<form action=""> <!-- TODO: Verlinkung einfügen -->
			<hr>
			<p>Neuer Benutzer?</p>
			<button type="submit" id="login-button">Registrieren</button>
		</form>
	</div>
	<!-- Footer -->
	<div id="login-footer">
		<?php
		require("footer.php");
		?>
	</div>
</body>
</html>