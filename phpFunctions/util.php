<?php
	require("./phpFunctions/sqlQueries.php");
	function getImage($artikelNr, $conn){
		$mime = 'image/jpg';
		$imageBinary = getProductImageData($artikelNr, $conn);
		$base64 = base64_encode($imageBinary);
		return ('data:' . $mime . ';base64,' . $base64);
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

 function getCustomDate($days){
	$dateObject = date_create_from_format('d.m.Y', date('d.m.Y', strtotime('+'.$days.' day')));
	$result = $dateObject->format('d.m.Y');
	return $result;
 }
	function loadShoppingCartInformation($conn){
		#var_dump($_SESSION['produkt_array']);
		foreach ($_SESSION['produkt_array'] as $sessionArray) {
			$produkt_ID = $sessionArray['produkt'];
			$menge = intval($sessionArray['menge']);
			$returnsArray = getProduktInfos($produkt_ID,$conn);
			$bezeichnung = $returnsArray[0];
			$akt_preis = intval($returnsArray[9]);
			$lagermenge = intval($returnsArray[8]);
	
			echo "
                    <div class='sc-product-cart row'>
			    <div class='col-1'>
					<img src='" . getImage($produkt_ID, $conn) . "' class='sc-product-cart-img' style' width: 25px;
					height: 25px;'>
				</div>
				<div class='col-2'>
					<table id='eigenschafts-tabelle'>
						<tr class='eigenschaft-row'>
							<td>".$bezeichnung."</td>
                        </tr>
						<tr class='eigenschaft-row'>
							<td> Produkt Nummer: ".$produkt_ID."</td>
                        </tr>
						<tr class='eigenschaft-row'>
							<td>";
							if($lagermenge>0){
								echo "Lieferbar bis zum ". getCustomDate(1) ;
								// berechnet ein Pseudo Lieferdatum
								}else{
									echo "Aktuell nicht Lieferbar";
								}
					  echo "</td>
						</tr>
						<tr class='eigenschaft-row'> <!-- #INFO: Platzhalter für Garantien -->
							<td>Platzhalter</td>
                        </tr>
					</table>
				</div>
				<div class='col-1 sc-mengen-div' >
                <fieldset class='sc-fd'>
                <legend id='sc-legend'>Menge:</legend>
					";
						echo "
						<select style='width:50px' name='mengenauswahl' id='sc-mengenauswahl'>";
							/*for ($i = 1; $i <= $lagermenge; $i++) {
								if($i = $menge){
									echo "<option value='$i' selected>$i</option>";
								}else {
									echo "<option value='$i'>$i</option>";
								}
							};*/
						echo "</select> </fieldset>
						<button type='button' class='sc-bt-remove-product'>
                            <i class='bi bi-trash-fill'></i>
                        </button>";
				echo "
				</div>
				<div class='sc-price col-1-5'>
						<p>". $akt_preis * $menge."</p>
				</div>
			</div>";
			
		}
	}
?>