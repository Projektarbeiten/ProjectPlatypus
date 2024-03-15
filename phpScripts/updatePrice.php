<?php
    session_start();
    require dirname(__FILE__,2) .'/phpFunctions/sqlQueries.php';
    require dirname(__FILE__,2) .'/phpFunctions/databaseConnection.php';
    $debug = true;
    $debug2= false;
    $shoppingCartArray = array();

    if(isset($_SESSION['produkt_array'])){

        $conn = buildConnection();
        if($debug){
            ini_set('log_errors', TRUE);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
        $sessionArray = $_SESSION['produkt_array'];

        foreach ($sessionArray as $array => $value){
            $produkt_id = $value['produkt'];
            $menge =  $value['menge'];
            $akt_preis = getPrice($conn, $produkt_id);
            $shoppingCartArtikleArray = array(
                    'produkt_id' => $produkt_id,
                    'menge' => $menge,
                    'akt_preis' => doubleval($akt_preis)
            );
            array_push($shoppingCartArray,$shoppingCartArtikleArray);
        }
        $jsonData = json_encode($shoppingCartArray);
        echo $jsonData;
    }
?>