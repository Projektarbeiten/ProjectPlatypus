<?php
// Creates a connection to the Database

  function buildConnection($connpath){
    $inipath = ("$connpath/config/app.ini");
    $ini = parse_ini_file($inipath);
    header('Content-type: text/html; charset=utf-8');
    $host = $ini['db_host'];
    $username = $ini['db_user'];
    $user_pwd = $ini['db_password'];
    $database = $ini['db_name'];

    try {
      $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $user_pwd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
      return $pdo;

    } catch (PDOException $ex) {
      die(json_encode(array('outcome' => false, 'message' => "Unable to connect to the Database. Try if the Database exists :) ")));
    }
  }

