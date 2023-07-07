<?php
require dirname(__FILE__) .'/sqlQueries.php';
require dirname(__FILE__) .'/sqlInserts.php';
function getImage($artikelNr, $conn)
{
	$mime = 'image/jpg';
	$imageBinary = getProductImageData($artikelNr, $conn);
	$base64 = base64_encode($imageBinary);
	return ("data: $mime; base64, $base64");
}

function checkCode($code, $conn)
{
	$row = getCode($conn, $code);
	if (boolval($row['valid']) == true) {
		return $row['value'];
	}
}
function eingenschaften($eigenschaft)
{
	try {
		$str_arr = explode(":", $eigenschaft);
		echo "<tr class='eigenschaft-row'>
         <td>{$str_arr[0]}</td>
         <td>{$str_arr[1]}</td>
         </tr>";
	} catch (Exception $e) {
		echo "<tr class='eigenschaft-row'>
         <td>{$eigenschaft}</td>
         </tr>";
	}
}

// TODO: Write Debug Log Function

function getProductAmountOptions($lagermenge, $menge)
{
	for ($i = 1; $i <= $lagermenge; $i++) {
		if ($i == $menge) {
			echo "<option value='$i' selected>$i</option>";
		} else {
			echo "<option value='$i'>$i</option>";
		}
	}
}

function getCustomBussinessDate($days = 0)
{
	while (date('N', strtotime(date(strtotime('+' . $days . ' day')))) >= 6){
	$days += 1;
	}
	$dateObject = date_create_from_format('d.m.Y', date('d.m.Y', strtotime('+' . $days . ' day')));
	$result = $dateObject->format('d.m.Y');
	return $result;
}

function checkVerification($token, $conn){
	$date = getValidityDate($token,$conn);
	if(!is_bool($date)){
		$date = date_format(date_create($date),"d.m.Y");
		$today = date("d.m.Y",time());
		if($date <= $today){
			return true;
		}
	}
}

function invokeEmailRequest($email,$type,$conn) {
	$url = 'http://host.docker.internal/phpScripts/sendEmail'; // TODO: Angepasst werden bei Deployment
	$fields = array(
		 'email' => $email,
		 'type' => $type,
		 'conn' => $conn
	 );
	 $postvars = http_build_query($fields);
	 $streamVerboseHandle = fopen('./temp.txt', 'w+');
	 $curlOptions = array(
			 CURLOPT_URL => $url,
			 CURLOPT_POST => true,
			 CURLOPT_POSTFIELDS => $postvars,
			 //CURLOPT_VERBOSE => false,
			 //CURLOPT_STDERR => $streamVerboseHandle
	 );
	 $connection = curl_init();
	 curl_setopt_array($connection, $curlOptions);
	 $result = curl_exec($connection);
	 curl_close($connection);
 }

function deleteVerificationeCode($token, $conn){
	setVerified($token, $conn);
	updateVerificationCode($token,NULL,conn:$conn);
}

function createVerificationToken(){
	$uniqueId = uniqid(true);
	return $uniqueId;
}

function loadShoppingCartInformation($conn): array
{ // INFO: Kann für die Main Page und dessen ShoppingCart Info genutzt werden.
	$shoppingCartProductAmount = 0;
	$shoppingCartValue = 0;
	$productInfoArray = array();
	foreach ($_SESSION['produkt_array'] as $sessionArray) {
		$produkt_ID = $sessionArray['produkt'];
		$menge = intval($sessionArray['menge']);
		$returnsArray = getProduktInfos($produkt_ID, $conn);
		$bezeichnung = $returnsArray[0];
		$akt_preis = doubleval($returnsArray[9]);
		$lagermenge = intval($returnsArray[8]);

		$productInfo = array(
			'id' => $produkt_ID,
			'menge' => $menge,
			'bezeichnung' => $bezeichnung,
			'akt_preis' => $akt_preis,
			'lagermenge' => $lagermenge
		);
		array_push($productInfoArray, $productInfo);

		$shoppingCartProductAmount += $menge;
		$shoppingCartValue += $akt_preis * $menge;
	}
	$shoppingCartInformationArray = array(
		'shoppingCartproduktAmount' => $shoppingCartProductAmount,
		'shoppingCartValue' => $shoppingCartValue
	);
	$shoppingCartInformationArray['productInfoArray'] = $productInfoArray; // Adds the Parent Array including all Child Arrays to the Main Return Array
	return $shoppingCartInformationArray;
}
function loadAndPrintShoppingCartInformation($conn)
{
	#var_dump($_SESSION['produkt_array']);
	ob_start();
	$shoppingCartInformationArray = loadShoppingCartInformation($conn);
	foreach ($shoppingCartInformationArray['productInfoArray'] as $productInfo) {
		$produkt_ID = $productInfo['id'];
		$menge = intval($productInfo['menge']);
		$bezeichnung = $productInfo['bezeichnung'];
		$akt_preis = doubleval($productInfo['akt_preis']);
		$lagermenge = intval($productInfo['lagermenge']);
		echo "
                <article class='sc-product-cart row' id='$produkt_ID'>
			<div class='row'>
			<div class='col-1-5'>
				<a href='productPage?produkt_id=$produkt_ID'><img src='" . getImage($produkt_ID, $conn) . "' class='sc-product-cart-img'></a>
			</div>
			<div class='col-1-5'>
				<table id='eigenschafts-tabelle'>
					<tr class='eigenschaft-row'>
						<td><a href='productPage?produkt_id=$produkt_ID'>$bezeichnung</a></td>
                    </tr>
					<tr class='eigenschaft-row'>
						<td> Produkt Nummer: $produkt_ID</td>
                    </tr>
					<tr class='eigenschaft-row'>
						<td>";
		if ($lagermenge > 0) {
			echo "Lieferbar bis zum " . getCustomBussinessDate(1);
			// berechnet ein Pseudo Lieferdatum
		} else {
			echo "Aktuell nicht Lieferbar";
		}
		echo "</td>
					</tr>
					<tr class='eigenschaft-row'> <!-- #INFO: Platzhalter für Garantien -->
						<td>Platzhalter</td>
                    </tr>
				</table>
			</div>
			<div class='col-1-5 sc-mengen-div' >
            <fieldset class='sc-fd'>
            <legend id='sc-legend'>Menge:</legend>
					<select style='width:50px' name='mengenauswahl' class='sc-mengenauswahl'>";
		getProductAmountOptions($lagermenge, $menge);
		echo "</select> </fieldset>
					<button type='button' class='sc-bt-remove-product'>
                        <i class='bi bi-trash-fill'></i>
                    </button>
				</div>
				<div class='sc-price col-1-5'>
						<p class='sc-article-price'>" . ($akt_preis * $menge) . " €</p>
				</div>
			</div>
			<hr />
			</article>
			";
	}
	ob_end_flush();
	ob_clean();
}

function loadOrderConfirmation($orderArray,$bid,$conn) {
	$adressInfo = getUserAdresse($orderArray['uid'],$conn);
	$userLand =  $adressInfo[0];
    $userPlz = $adressInfo[1];
    $userOrt = $adressInfo[2];
    $userStrasse = $adressInfo[3];
    $userHausnr = $adressInfo[4];
    $userAdresszusatz = $adressInfo[5];
	$produktAnzahl = $orderArray['produktAnzahl'];
	$lieferdatum = $orderArray['lieferdatum'];
	$lieferdatum = date_format(date_create($lieferdatum),'d.m.Y');
	echo "<p><strong>Bestell_ID:</strong> {$bid}</p>
	<p><strong>Artikelanzahl:</strong> {$produktAnzahl}</p>
	<p><strong>Lieferdatum:</strong> {$lieferdatum}</p>
	<p><strong>Lieferadresse:</strong> {$userStrasse} {$userHausnr} {$userAdresszusatz}, {$userPlz} {$userOrt}, {$userLand}</p>
	<p><strong>Versandart:</strong> Standard DHL Versand</p>";
}
?>