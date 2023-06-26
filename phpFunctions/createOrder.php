<?php
    session_start();
    require dirname(__FILE__,2) . '/phpFunctions/databaseConnection.php';
    require dirname(__FILE__,2) .'/phpFunctions/util.php';

    $conn = buildConnection('../');
    $debug = false;
    $debug2= false;
    if($debug){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
    if (isset($_SESSION['uid'])) {
        getOrderData($conn);
    }else{
        header("Location:../login");
    }
    function getOrderData($conn){
        $counter = 0;
        $orderPosArray = array();
        $orderArray = array();
        $endprice = 0.00;
        $amountOfProducts = 0;
        foreach ($_SESSION['produkt_array'] as $array){
            $returns = getProduktInfos($array['produkt'], $conn);
            $orderPosArray[$counter] = $array;
            $orderPosArray[$counter]['bezeichnung'] = $returns[0];
            $orderPosArray[$counter]['akt_preis'] = $returns[9];
            $amountOfProducts += $orderPosArray[$counter]['menge'];
            $endprice += intval($orderPosArray[$counter]['menge']) * number_format(floatval($orderPosArray[$counter]['akt_preis']),2);
            $counter += 1;
        }
        $orderArray = array(
            'uid' => $_SESSION['uid'],
            'gesamtkosten' => $endprice,
            'produktAnzahl' => $amountOfProducts,
            'bestellDatum' => getCustomBussinessDate(0),
            'bestellPositionen' => $orderPosArray,
            'Lieferdatum' => getCustomBussinessDate(5)
        );
        return $orderArray;
    }

?>