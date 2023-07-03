<?php
session_start();
require dirname(__FILE__, 2) . '/phpFunctions/databaseConnection.php';
require dirname(__FILE__, 2) . '/phpFunctions/util.php';

$conn = buildConnection();
$debug = false;
$debug2 = false;
if ($debug) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
if (isset($_SESSION['uid']) && isset($_SESSION['access_token']) || $_SESSION['access_token'] == true) {
	$pageContent = loadOrderHistory($conn, $_SESSION['uid']);
	echo $pageContent;
}

function loadOrderHistory($conn, $u_id, $timespan = 0)
{
	$results = getOrderHistory($conn, $u_id);
	ob_start();
	foreach ($results as $bestellung) {
		echo "
		<div class='order-card'>
			<div class='row'>
				<div class='col-1-5'>
					<p><strong>Bestellung aufgegeben</strong></p>
				</div>
				<div class='col-1-5'>
					<p><strong>Anzahl</strong></p>
				</div>
				<div class='col-1-5'>
					<p><strong>Summe</strong></p>
				</div>
				<div class='col-1-5'>
					<p class='order-dropdown-arrow'><i class='arrow left'></i></p>
				</div>
			</div>
			<div class='row'>
				<div class='col-1-5'>
					<p>" . date_format(date_create( $bestellung['bestell_datum']),'d.m.Y') . "</p>
				</div>
				<div class='col-1-5'>
					<p>" . $bestellung['anzahl_bestellpos'] . " Stk.</p>
				</div>
				<div class='col-1-5'>
					<p>" . $bestellung['gesamtkosten'] . " €</p> <!-- Gesamtpreis -->
				</div>
				<div class='col-1-5'>";
		$value = ($bestellung['geliefert'] == 1 && $bestellung['geliefert'] != null) ? "<button type='button' id='order-refund'>Gesamte Bestellung stornieren </button> <!-- Wert wird dynmaisch ermittelt -->" : "";
		echo $value;
		echo "</div>
			</div>
			<div class='trennlinie' style='width: 100%; border-color: black'></div>
			<div class='order-dropdown'>
				<div class='row'>
					<div class='col-6 order-dropdown-closed'>
						<p>...</p>
					</div>
				</div>";
		foreach ($bestellung['Bestellpositionen'] as $bestellposition) {
			echo "<div class='order-dropdown-open' id='" . $bestellung['b_id'] . "' style='display:none'> <!-- ID wird von order in der Datenbank bestimmt -->
					<div class='row'>
						<div class='col-2'>";
			if ( date_format(date_create( $bestellung['bestell_datum']),'d.m.Y') == getCustomBussinessDate()) {
				echo "<p><strong>ausstehender Versand am </strong></p>";
			} else {
				echo "<p><strong>Versand am </strong></p>";
			}
			echo "</div>
						<div class='col-2'>";
			if ( date_format(date_create( $bestellung['lieferdatum']),'d.m.Y') >= getCustomBussinessDate()) {
				echo "<p><strong>voraussichtliche Zustellung am</strong></p>";
			} else {
				echo "<p><strong>Zugestellt am</strong></p>";
			}
			echo "</div>
					</div>
					<div class='row'>
						<div class='col-2'>
							<p>" . date_format(date_create( $bestellung['bestell_datum']),'d.m.Y'). "</p>
						</div>
						<div class='col-2'>
							<p>" . date_format(date_create( $bestellung['lieferdatum']),'d.m.Y') . "</p>
						</div>
						<div class='col-2'>";
			if ($bestellung['geliefert'] == 1 && $bestellung['geliefert'] != null) {
				echo "<button type='button' class='article-refund'>Artikel zurückschicken</button>";
			} else {
				echo "<button type='button' class='article-refund'>Artikel stornieren</button>";
			}
			echo "</div>
					</div>
					<div class='trennlinie' style='width: 100%; border-color: black'></div>
					<div class='row'>
						<div class='col-1-5'>
							<p><strong>Produktname</strong></p>
						</div>
						<div class='col-1-5'>
							<p><strong>Einzelpreis</strong></p>
						</div>
						<div class='col-1-5'>
							<p><strong>Menge</strong></p>
						</div>
						<div class='col-1-5'>
							<p><strong>Summe</strong></p>
						</div>
					</div>
					<div class='row'>
						<div class='col-1-5'>
							<a href='./productPage?produkt_id=" . $bestellposition['p_id_ref'] . "'>
								<p>" . $bestellposition['bezeichnung'] . "</p>
							</a>
						</div>
						<div class='col-1-5'>
							<p>" . $bestellposition['akt_preis'] . " €</p>
						</div>
						<div class='col-1-5'>
							<p>" . $bestellposition['menge'] . " Stk.</p>
						</div>
						<div class='col-1-5'>
							<p>" . ($bestellposition['akt_preis'] * $bestellposition['menge']) . " €</p>
						</div>
					</div>
					<div class='row'>
						<div class='col-2'>
							<img src='" . getImage($bestellposition['p_id_ref'], $conn) . "' alt='Produktbild'>
						</div>
						<div class='col-4'>
							<p> " . $bestellposition['details'] . "</p>
						</div>
					</div>
				</div>";
		}
		echo "</div>
		</div>";
	}
	$result = ob_get_clean();
	return $result;
}
?>