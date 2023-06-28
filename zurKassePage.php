<?php
session_start();
require("./phpFunctions/databaseConnection.php");
require("./phpFunctions/util.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$conn = buildConnection(".");
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="./css/styles.css">
	<title>Zur Kasse</title>
</head>

<body>
	<!-- Header -->
	<?php
	require("header.php");
	?>

	<div class="container">
		<br>
		<div class="bestelldaten">
			<h3>1 Versandadresse</h3>
			<div id="box_versadd">
				<p>Max Mustermann</p>
				<p>Mustermannstr. 1</p>
				<p>70173 Stuttgart, Baden-Württemberg </p>
				<p>Deutschland</p>
			</div>
		</div>
		<br>
		<hr>
		<br>
		<div class="bestelldaten">
			<h3>2 Zahlungsart</h3>
			<div id="box_zahlart">
				<p>Bankeinzugskonto endet auf ****</p>
			</div>
		</div>
		<hr>
		<br>

		<div class="bestelldaten">
			<h3>3 Artikel und Versand überprüfen</h3>
		</div>
		<br>
		<div class="box_check_art_vers">
			<div class="row">
				<div class="col-5 lief_vers">
					<h4> voraussichtliche Lieferung:</h4>
					<p id="dotted-box-lief">Datum</p>
					<div class='col-0-5 lief_vers'>
						<p id="ch-box-preis">Menge:</p>
						<p>2</p>
					</div>
					<div class='col-0-5 lief_vers'>
						<p id="ch-box-preis">Preis:</p>
						<p>2€</p>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-3">
					<img src="./img/testBild.png" class="center-element" alt="Produktbild">
				</div>
				<div class="col-3">
					<p id="dotted-box-artbeschreibung">Artikelbeschreibung</p>
				</div>
			</div>

			<div class="row">
				<div class="col-6 lief_vers">
					<h4>Versandart:</h4>
					<form id> <!--- TODO: Dynamisches Inserten + Versandarten Tabelle erstellen -->
						<input type="radio" id="versandart" name="v1" value="dhl">
						<label for="versandart">Standard DHL Versand</label>
					</form>
				</div>
			</div>
		</div>

		<div class="box_check_art_vers">
			<div id="box_check_art_vers_zent">
				<a href="" id="checkout-btn" style="margin-left: 5%; width: 15%;">Jetzt kaufen</a><!-- TODO: Verlinkung einfügen -->
				<h4 style="margin-left: 10%;">Gesamtbetrag:</h4>
				<p id="dotted-box-summe" style="margin-left: 5%;">Summe</p>
				<br>
			</div>
			<div style="text-align: center;">
				<a href="" id="agb">Mit dem Klick auf "Zur Kasse" akzeptieren Sie unsere AGB.</a><!-- TODO: Verlinkung einfügen -->
			</div>
		</div>
		<br>
		<a href="" id="back-btn" style="margin-left: 5%; width: 15%;">Zurück zum Warenkorb</a><!-- TODO: Verlinkung einfügen -->
	</div>

	<!-- Footer -->
	<?php
	require("footer.php");
	?>
</body>

</html>