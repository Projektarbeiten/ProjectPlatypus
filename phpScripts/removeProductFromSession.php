<?php
    session_start();
    $debug = true;
    $debug2= false;

    $status;
    $response;

    if($debug){
        ini_set('log_errors', TRUE);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
    if(isset($_POST['produkt_id']) && intval($_POST['produkt_id']) != -1){
        $found;
        $produkt_id = $_POST['produkt_id'];
        foreach ($_SESSION['produkt_array'] as &$array){
            if(in_array($produkt_id,$array)){
                $found = 1;
                $index = array_search($array,$_SESSION['produkt_array']);
                unset($_SESSION['produkt_array'][$index]);
                $status = "Product id found and unsettet";
                $response = "200";
            }
        }
        if($found != 1){
            $found = 0;
        }
    }else{
        $status = "- no produkt_id send";
        $response = "404";
        $found = -1;
    }

    $returnArray = array(
                    "responseStatus"=> $response,
                    "status" => $status . " + Found: ".$found
    );
    $jsonResponse = json_encode($returnArray);
    echo $jsonResponse;
?>