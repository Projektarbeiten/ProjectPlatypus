<?php
session_start();

require_once dirname(__FILE__,2) . "/phpFunctions/databaseConnection.php";
require dirname(__FILE__,2) . "/phpFunctions/rechungsPackage.php";
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
	header("Location: index");
}
$conn = null;
if (!isset($_POST['conn'])){
    $conn = buildConnection();
}else{
    $conn = $_POST['conn'];
}
if(isset($_POST['type']) && $_POST['email']){
    if(!is_bool($uid)){
        startInvoiceCreation( $conn);
        echo 1;
    }else{
        echo 0;
    }
}

?>