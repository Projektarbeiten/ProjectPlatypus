<?php
session_start();
require dirname(__FILE__) .'/phpFunctions/util.php';
require dirname(__FILE__) .'/phpFunctions/databaseConnection.php';
print dirname(__FILE__) .'/phpFunctions/util.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = buildConnection();
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
    header("Location: index");
}elseif(!isset($_SESSION['uid'])){
    header("Location: login");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./css/styles.css">
	<title>Kauf abgeschlossen</title>
</head>

<body>
	<!-- Header -->
	<?php
	require("header.php");
	?>

	<div class="container">
		<h1>Vielen Dank für Ihre Bestellung!</h1>
		<p style="text-align: center;">Wir schätzen Ihr Vertrauen in unser Unternehmen und werden Ihre Bestellung so
			schnell wie möglich bearbeiten.</p>
		<div class="box">
			<h2>Ihre Bestelldetails:</h2>
				<?php
				loadOrderConfirmation($_SESSION['order_array'],$_SESSION['bid'],$conn);
				unset($_SESSION['order_array']);
				unset($_SESSION['bid']);
				?>
		</div>
	</div>

	<!-- Footer -->
	<?php
	require("footer.php");
	?>
</body>

</html>