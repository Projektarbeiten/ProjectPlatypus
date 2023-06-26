<?php
// Creates a connection to the Database
// Zum Debuggen Zeile 4-6 auskommentieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
#print  (dirname(__FILE__,2)."/config/app.ini");
function buildConnection()
{
	$inipath = (dirname(__FILE__,2)."/config/app.ini");
	$ini = parse_ini_file($inipath);
	header('Content-type: text/html; charset=utf-8');
	$host = $ini['db_host'];
	$username = $ini['db_user'];
	$user_pwd = $ini['db_password'];
	$database = $ini['database'];
	$port = $ini['db_port'];

	try {
		#$pdo = new PDO("mysql:host=$host;dbname=$database;","port=$port", $username, $user_pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$pdo = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $user_pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		return $pdo;
	} catch (PDOException $ex) {
		error_log(date("Y-m-d H:i:s", time()) . " Connection failed \n
		host: {$host}, Database: {$database}, Username: {$username}", 3, "my-errors.log");
		die(json_encode(array('outcome' => false, 'message' => "Unable to connect to the Database. Try if the Database exists :) " . $ex)));
	}
}
