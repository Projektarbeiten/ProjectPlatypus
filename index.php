<?php
session_start();


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
	<title>Authentifizieren</title>
</head>

<body>
	<div class="container">
		<div class="row">
				<div class="col-6">
					<div id="login-form">
						<p>Bitte den Code eingeben</p>
						<for method="post">
							<input type="text" id="code" name='code'></input>
							<div style="display: flex">
								<button class="boxrechts" id="back">Zurück</button>
								<button class="boxlinks" id="eingabe">Eingabe</button>
							</div>
						</form>
					</div>
				</div>
		</div>
		<?php
			if(isset($_POST['code'])){
				$conn = buildConnection('.');
				if(checkCode($_POST['code'],$conn)){
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
	<script src="javascript/indexLogin.js"></script>
</body>
</html>