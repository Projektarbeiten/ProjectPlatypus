<?php
    session_start();
    require dirname(__FILE__,2) . '/phpFunctions/databaseConnection.php';
    require dirname(__FILE__,2) .'/phpFunctions/util.php';
    require dirname(__FILE__,2) .'/phpFunctions/sqlInserts.php';

    #ini_set('display_errors', 1);
    #ini_set('display_startup_errors', 1);
    #error_reporting(E_ALL);

    $conn = buildConnection();
    $debug = false;
    $debug2= false;
    $putArrayToFile = true;

    if($debug){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
    if (isset($_SESSION['uid'])) {
        $orderDataArray = getOrderData($conn);
        try {
            foreach ($orderDataArray['bestellPositionen'] as $orderData){
                if($orderData['lagermenge'] < $orderData['menge']){
                    throw new Exception("Die Bestellung beinhaltet mehr als Lagerbestand vorhanden ist!");
                }
            }
        } catch (Exception $e) {
            error_log(date("Y-m-d H:i:s", time()) . " Das generieren der Bestellung ist fehlgeschlagen - CreateOrder.php - CreateOrder.php \n
            PHP Fehler: \n $e \n", 3, "my-errors-Scripts.log");
        }
        $return = saveOrderData($conn,$orderDataArray);
        if($return){
        # todo: Bestellungsmail versenden #
        $_SESSION['produkt_array'] = Null;
        unset($_SESSION['produkt_array']);
		$_SESSION['order_array'] = $orderDataArray;
        header("location: ../orderConfirmation");
        }
    }else{
        header("Location: ../login");
    }
    function getOrderData($conn){
        global $putArrayToFile;
        $counter = 0;
        $endprice = 0.00;
        $amountOfProducts = 0;
        $orderPosArray = array();
        foreach ($_SESSION['produkt_array'] as $array){
            $returns = getProduktInfos($array['produkt'], $conn);
            $orderPosArray[$counter] = $array;
            $orderPosArray[$counter]['bezeichnung'] = $returns[0];
            $orderPosArray[$counter]['akt_preis'] = $returns[9];
            $orderPosArray[$counter]['lagermenge'] = $returns[8];
            $amountOfProducts += $orderPosArray[$counter]['menge'];
            $endprice += intval($orderPosArray[$counter]['menge']) * number_format(floatval($orderPosArray[$counter]['akt_preis']),2);
            $counter += 1;
        }
        $zi_returns = getZahlungsmittel($conn, $_SESSION['uid']);
        $orderArray = array(
            'uid' => $_SESSION['uid'],
            'zi_id' => $zi_returns['zi_id'],
            'gesamtkosten' => $endprice,
            'produktAnzahl' => $amountOfProducts,
            'bestellDatum' => date_format(date_create(getCustomBussinessDate(0)),"Y-m-d"),
            'bestellPositionen' => $orderPosArray,
            'lieferdatum' => date_format(date_create(getCustomBussinessDate(5)),"Y-m-d"),
			'anzahlBestellpositionen' => $counter
        );
        if($putArrayToFile){
            $data = json_encode($orderArray);
            $file = 'order_array_data.json';
            file_put_contents($file, $data);
        }
        return $orderArray;
    }

    function saveOrderData($conn,$orderDataArray): bool  {
       $bestellId = insertOrder($conn,$orderDataArray);
	   $_SESSION['bid'] = $bestellId;
       $return = insertOrderPos($conn,$orderDataArray,$bestellId);
       if(!$return){
        #header('Location: Error') # TODO: Error Seite erstellen.
       }else{
        updateProduktMenge($conn,$orderDataArray);
       }
       return $return;
    }

?>