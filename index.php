<?php
session_start();
require dirname(__FILE__) . "/phpFunctions/databaseConnection.php";
require dirname(__FILE__) . '/phpFunctions/util.php';
$conn = buildConnection();

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset='utf-8'>
	<meta http-equiv='X-UA-Compatible' content='IE=edge'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<link rel="stylesheet" href="./css/styles.css">
	<link rel="icon" type="image/x-icon" href="./img/favicon/favicon-32x32.png">
	<title>Authentifizieren</title>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-6">
				<div id="code-login-form">
					<p>Bitte den Code eingeben</p>
					<form method="post">
						<input type="password" id="code" name='code'></input>
						<div style="display: flex">
							<button class="boxrechts access-code-button">Zurück</button>
							<button type="submit" class="boxlinks access-code-button">Eingabe</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
		if (isset($_POST['code'])) {
			$conn = buildConnection();
			if (checkCode($_POST['code'], $conn)) {
				$_SESSION['access_token'] = true;
				header("Location: home");
			}
		}
		?>
	</div>
	<div id="login-footer">>
		<?php
		require("footer.php");
		?>
	</div>
	<script src="./javascript/jquery-3.6.1.min.js"></script>
	<script src="./javascript/indexLogin.js"></script>
</body>

</html>