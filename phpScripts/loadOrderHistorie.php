<?php
	session_start();
	require dirname(__FILE__,2) . '/phpFunctions/databaseConnection.php';
	require dirname(__FILE__,2) .'/phpFunctions/util.php';

	$conn = buildConnection();
    $debug = false;
    $debug2= false;
    if($debug){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
    if (isset($_POST[''])) {
		$orders = array();
        $sql = "SELECT *
        FROM bestellung, bestellposition
        WHERE bestellung.b_id = bestellposition.b_id_ref
        AND bestellung.u_id_ref = :uid";
        $sql2 = "SELECT COUNT(*) FROM bestellung WHERE u_id_ref = :uid";
        $sqlstmtamount = $conn->prepare($sql2);
        $stmt =$conn->prepare($sql);
        $sqlstmtamount->bindParam(":uid",$uid);
        $stmt->bindParam(":uid",$uid);
        $sqlstmtamount->execute();
        $stmt->execute();
        $orderCounter = $sqlstmtamount->fetch();
        $count = $orderCounter[0];
        $count = intval($count);
        while ($count>0)
        {
            while ($row = $stmt->fetch()) {
                array_push($orders,$row);
            }
            $count--;
        }
        for ($i=0; $i < Count($orders); $i++) {

        }
	}

function loadOrderHistory($u_id,$timespan = 0){
	$results = getOrderHistory($u_id);
	ob_start();
	echo "
	<div class='order-card'>
		<div class='row'>
			<div class='col-1-5'>
				<p><strong>Bestellung aufgegeben</strong></p>
			</div>
			<div class='col-1-5'>
				<p><strong>Anzahl Artikel</strong></p>
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
				<p>20.02.2023</p>
			</div>
			<div class='col-1-5'>
				<p>4</p>
			</div>
			<div class='col-1-5'>
				<p>36,88€</p> <!-- Gesamtpreis -->
			</div>
			<div class='col-1-5'>
				<button type='button' id='order-refund'>Bestellung zurückschicken/Bestellung stornieren</button> <!-- Wert wird dynmaisch ermittelt -->
			</div>
		</div>
		<div class='trennlinie' style='width: 100%; border-color: black'></div>
		<div class='order-dropdown'>
			<div class='row'>
				<div class='col-6 order-dropdown-closed'>
					<p>...</p>
				</div>
			</div>

			<div class='order-dropdown-open' id='{$b_id}' style='display:none'> <!-- ID wird von order in der Datenbank bestimmt -->
				<div class='row'>
					<div class='col-2'>
						<p><strong>Versand am</strong></p>
					</div>
					<div class='col-2'>
						<p><strong>Zugestellt am /voraussichtliche Zustellung</strong></p>
					</div>
				</div>
				<div class='row'>
					<div class='col-2'>
						<p>20.02.2023</p>
					</div>
					<div class='col-2'>
						<p>22.02.2023</p>
					</div>
					<div class='col-2'>
						<button type='button' class='article-refund'>Artikel zurückschicken/Artikel stornieren</button>
					</div>
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
						<a href=''>
							<p>Produkt XYZ</p>
						</a> <!-- An Verlinkung denken -->
					</div>
					<div class='col-1-5'>
						<p>12.34€</p>
					</div>
					<div class='col-1-5'>
						<p>3</p>
					</div>
					<div class='col-1-5'>
						<p>37.02€</p>
					</div>
				</div>
				<div class='row'>
					<div class='col-2'>
						<img src='./img/testBild.png' alt='Produktbild'>
					</div>
					<div class='col-4'>
						<p> Test Beschreibung: Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam illum autem tempore, sequi suscipit iste ipsum repudiandae itaque iure eligendi,
							dolor est delectus vel odit quasi nihil laboriosam corporis. Vero.</p>
					</div>
				</div>
			</div>
		</div>
	</div>";
	ob_end_flush();
	ob_clean();
}

?>