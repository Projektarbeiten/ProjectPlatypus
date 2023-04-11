<?php
	function getImage($artikelNr, $conn){
		$mime = 'image/jpg';
		$imageBinary = getProductImageData($artikelNr, $conn);
		$base64 = base64_encode($imageBinary);
		return ('data:' . $mime . ';base64,' . $base64);
	}
?>