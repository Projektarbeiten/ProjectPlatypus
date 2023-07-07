<?php
session_start();

require_once dirname(__FILE__,2) . "/phpFunctions/databaseConnection.php";
require dirname(__FILE__, 2) . '/phpFunctions/emailPackage.php';
$conn = buildConnection();
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
	header("Location: index");
}
if(isset($_POST['type']) && $_POST['email']){
    startOfEmailProcess($_POST['type'], $_POST['email'], $conn);
}

?>