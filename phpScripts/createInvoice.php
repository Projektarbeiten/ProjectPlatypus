<?php
session_start();

require_once dirname(__FILE__,2) . "/phpFunctions/databaseConnection.php";
require dirname(__FILE__,2) . "/phpFunctions/invoicePackage.php";
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
	header("Location: index");
}
$conn = null;
$test = true;
if (!isset($_POST['conn'])){
    $conn = buildConnection();
}else{
    $conn = $_POST['conn'];
}
if(isset($_POST['type']) && $_POST['email'] && $_POST['orderArray']){
    $orderArray = $_POST['orderArray'];
    startInvoiceCreation($orderArray,$conn);
}elseif($test){
    startInvoiceCreation(OrderArray: '',conn: $conn);
}

?>