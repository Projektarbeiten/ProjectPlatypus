<?php
session_start();
require dirname(__FILE__) . '/phpFunctions/databaseConnection.php';
require dirname(__FILE__) . '/phpFunctions/util.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$conn = buildConnection();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./css/styles.css">
	<title>Kontakt</title>
</head>

<body>
	<!-- Header -->
	<?php
	require("header.php");
	?>
    <!-- /Header -->
	<main>
		<h1>Kontakt</h1>
		<br>
		<div class="sc-nav-container">
			<div class="abstandregions">
				<h2>Kontaktieren Sie uns</h2>
				<br>
				<form method="post">
					<div class="row">
						<input type="text" id="contactinput" size="25" placeholder="Vorname" required>
						<input type="text" id="contactinput" size="25" placeholder="Nachname" required>
					</div>
					<div class="row">
						<div class='abstand'>
							<input type="email" id="contactinput" size="54" placeholder="E-Mail" required>
						</div>
					</div>
					<div class="row">
						<div class='abstand'>
							<input type="text" id="contactinput" size="54" placeholder="Betreff" required>
						</div>
					</div>
					<div class='row'>
						<div class='abstand'>
							<textarea id="textareaheight" placeholder="Nachricht eingeben..." cols="53" required></textarea>
						</div>
					</div>
					<div class='row'>
						<div class='abstandbutton'>
							<button id='send' type="submit">Absenden</button>
						</div>
					</div>
				</form>
			</div>

			<div class="row">
				<h2>Kontaktdaten</h2>
				<br>
				<p>Bei Fragen oder Wünschen</p>
				<p>können Sie sich auch gerne</p>
				<p>direkt bei uns melden.</p>
				<br>
				<p>Max Mustermann</p>
				<p>E-Mail: max.mustermann@example.com</p>
				<p>Tel.: +49 123456789</p>
			</div>
		</div>
	</main>
	<!-- Footer -->
	<?php
	require("footer.php");
	?>
	<!-- /Footer -->
</body>

</html>