<?php
	require("databaseConnection.php");
	require("sqlQueries.php");

	function getImage($artikelNr){
		$mime = 'image/jpg';
		$pdo = buildConnection();
		$imageBinary = getProductImageData($artikelNr,$pdo);
		$base64 = base64_encode($imageBinary);
		return ('data:' . $mime . ';base64,' . $base64);
	}
?>