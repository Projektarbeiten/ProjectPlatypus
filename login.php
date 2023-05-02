<?php
session_start();

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
				<div class="login-div">
					<h3>Anmelden</h3>
					<hr>
					<form id="login-form">
						<label for="t_username">Username:</label> <br>
						<input type="text" name="t_username"> <br>
						<label for="t_password" id="login-label">Password:</label> <br>
						<input type="password" name="t_password"> <br>
						<button type="submit" id="login-button">Anmelden</button> <br>
					</form>
					<a href="_blank" id="login-page-link">Passwort vergessen?</a><!-- TODO: Verlinkung einfügen --><br>
					<form action="_blank"> <!-- TODO: Verlinkung einfügen -->
						<hr>
						<p>Neuer Benutzer?</p>
						<button type="submit" id="login-button">Registrieren</button>
					</form>
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