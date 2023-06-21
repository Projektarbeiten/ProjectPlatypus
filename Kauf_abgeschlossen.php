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
			<p><strong>Bestell_ID:</strong> [ID EINFÜGEN]</p>
			<p><strong>Artikelanzahl:</strong> [ANZAHL EINFÜGEN]</p>
			<p><strong>Lieferdatum:</strong> [DATUM EINFÜGEN]</p>
			<p><strong>Lieferadresse:</strong> [ADRESSE EINFÜGEN]</p>
			<p><strong>Versandart:</strong> [VERSANDART EINFÜGEN]</p>
		</div>
	</div>

	<!-- Footer -->
	<?php
	require("footer.php");
	?>
</body>

</html>