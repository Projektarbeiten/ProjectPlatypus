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

 function getDate($days){
	date_format(date_create($row['geburtsdatum']),"d.m.Y")
 }

	function loadShoppingCartInformation($conn){
		session_start();
		foreach ($$_SESSION['produkt_array'] as $sessionArray) {
			$produkt_ID = $sessionArray['produkt'];
			$menge = $sessionArray['menge'];
			$returnsArray = getProduktInfos($produkt_ID,$conn);

			echo
			"
			<div class='sc-product-cart'>
			    <div class='col-1'>
					<img src='" . getImage($produkt_ID, $conn) . "' class='sc-product-cart-img'>
				</div>
				<div class='col-3'>
					<tr class='eigenschaft-row'>
						<td>".$returnsArray['bezeichnung']."</td>
					<tr class='eigenschaft-row'>
						<td>".$produkt_ID."</td>
					<tr class='eigenschaft-row'>
						<td>". if($returnsArray['bezeichnung']>0){
							   echo "Lieferbar bis zum ";
                            }else{
                                $menge = $returnsArray['bezeichnung'] * -1;
                            }
						}."</td>
					<tr class='eigenschaft-row'>
						<td></td>
					<tr class='eigenschaft-row'>
						<td></td>
				</div>
				<div class='col-1-5'>
				</div>
				<div class='col-1'>
				</div>
			</div>
			";
		}
	}
?>