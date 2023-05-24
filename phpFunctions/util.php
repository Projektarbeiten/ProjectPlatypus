<?php
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
?>