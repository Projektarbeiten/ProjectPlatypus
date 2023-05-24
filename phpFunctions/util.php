<?php
	require("./phpFunctions/sqlQueries.php");
	function getImage($artikelNr, $conn){
		$mime = 'image/jpg';
		$imageBinary = getProductImageData($artikelNr, $conn);
		$base64 = base64_encode($imageBinary);
		return ('data:' . $mime . ';base64,' . $base64);
	}

	function checkCode($code, $conn){
		$row = getCode($conn, $code);
		if(boolval($row['valid']) == true ){
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

	function getProductAmountOptions($lagermenge, $menge){

					for ($i = 1; $i <= $lagermenge; $i++) {
								if($i == $menge){
									echo "<option value='$i' selected>$i</option>";
								}else {
									echo "<option value='$i'>$i</option>";
								}
							}
	}

 function getCustomDate($days){
	$dateObject = date_create_from_format('d.m.Y', date('d.m.Y', strtotime('+'.$days.' day')));
	$result = $dateObject->format('d.m.Y');
	return $result;
 }
	function loadShoppingCartInformation($conn){
		#var_dump($_SESSION['produkt_array']);
		ob_start();
		foreach ($_SESSION['produkt_array'] as $sessionArray) {
			$produkt_ID = $sessionArray['produkt'];
			$menge = intval($sessionArray['menge']);
			$returnsArray = getProduktInfos($produkt_ID,$conn);
			$bezeichnung = $returnsArray[0];
			$akt_preis = doubleval($returnsArray[9]);
			$lagermenge = intval($returnsArray[8]);
			echo "
                    <article class='sc-product-cart row' id='".$produkt_ID."'>
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
						<select style='width:50px' name='mengenauswahl' class='sc-mengenauswahl'>";
							 echo getProductAmountOptions($lagermenge, $menge); #TODO: Verhindert das Laden des Warenkorb Inhalts
						echo "</select> </fieldset>
						<button type='button' class='sc-bt-remove-product'>
                            <i class='bi bi-trash-fill'></i>
                        </button>";
				echo "
				</div>
				<div class='sc-price col-1-5'>
						<p class='sc-article-price'>". number_format(doubleval($akt_preis * $menge), 2, '. ', '' )." €</p>
				</div>
			</article>
			<hr>
			";
		}
		ob_end_flush();
		ob_clean();
	}
?>