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

    <br>
    <div class="unterteilungen">
        <h3>1 Versandadresse</h3>
        <div class="box_kasse_versadd">
        <p>Hier wird die Versandadresse des Kunden dargestellt.</p>
        </div>
    </div>
    <br>
    <hr>
    <br>
    <div class="unterteilungen">
        <h3>2 Zahlungsart</h3>
        <div class="box_kasse_zahlart">
        <p>Hier wird die angegebene Zahlungsart angezeigt.</p>
        </div>
    </div>
    <hr>
    <br>

    <div class="unterteilungen">
    <h3>3 Artikel und Versand überprüfen</h3>
    </div>
    <br>
    <div class="box_kasse_u3">
        <div class="lief_vers">
            <h4>Lieferung:</h4>
            <p class="dotted-box-lief">Datum</p>
        </div>
        <br>
        <div style="display: flex;">
            <div class="solid-box">
            <img src="[PRODUKTBILD-URL]" alt="Produktbild">
            </div>
            <p class="dotted-box" style="width: 60%;">Artikelbeschreibung</p>
            <br>
            <p class="dotted-box-preis">Preis</p>
            <p class="dotted-box-anzahl">Anzahl</p>
        </div>
        <br>
        <div class="lief_vers">
            <h4>Versandart:</h4>
            <p class="dotted-box-vers">Beschreibung der Versandart</p>
        </div>
    </div>

    <div class="box_kasse_u3">
    <div class="box_kasse_u3_zent">
        <button class="checkout-btn" style="margin-left: 5%; width: 15%;">Jetzt kaufen</button>
        <h4 style="margin-left: 10%;">Gesamtbetrag:</h4>
        <p class="dotted-box" style="margin-left: 5%;">Summe</p>
        <br>
    </div>
        <div style="text-align: center;">
        <p class="agb">Mit dem Klick auf "Zur Kasse" akzeptieren Sie unsere AGB.</p>
        </div>
    </div>

	<!-- Footer -->
	<?php
	require("footer.php");
	?>
</body>

</html>

