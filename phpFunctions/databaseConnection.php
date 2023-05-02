<?php
// Creates a connection to the Database
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

  function buildConnection($connpath){
    $inipath = ("$connpath/config/app.ini");
    $ini = parse_ini_file($inipath);
    header('Content-type: text/html; charset=utf-8');
    $host = $ini['db_host'];
    $username = $ini['db_user'];
    $user_pwd = $ini['db_password'];
    $dbname = $ini['db_name'];
    $port = $ini['db_port'];

    try {
      $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $user_pwd);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $pdo;

    } catch (PDOException $ex) {
      die(json_encode(array('outcome' => false, 'message' => "Unable to connect to the Database. Try if the Database exists :) ")));
    }
  }