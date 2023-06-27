<?php
    session_start();
    $debug = false;
    $debug2= false;
    if($debug){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
    $menge = 'leer';
    $produkt_ID = 'leer';
    if(!isset($_SESSION['produkt_array'])){
        $_SESSION['produkt_array'] = array();
    }

    if(isset($_POST['mengenauswahl']) && isset($_POST['produkt_id'])){
        $found;
        $menge = $_POST['mengenauswahl'];
        $produkt_ID = $_POST['produkt_id'];
        $arrayOfProdukt = array(
                                'produkt' => $produkt_ID,
                                'menge'   => $menge,
                                );
        if(isset($_SESSION['produkt_array']) && !empty($_SESSION['produkt_array'])){
            foreach ($_SESSION['produkt_array'] as &$array){
                    if(in_array($produkt_ID,$array)){
                        $found = 3;
                        if($debug){
                            echo "before:";
                            var_dump($_SESSION['produkt_array']);
                        }
                        $index = array_search($array,$_SESSION['produkt_array']);
                        $_SESSION['produkt_array'][$index]['menge'] = $menge;
                        if($debug){
                            echo "after:";
                            var_dump($_SESSION['produkt_array']);
                        }
                        break;
                    }
            }
            if($found != 3){
                $found = 2;
                array_push($_SESSION['produkt_array'],$arrayOfProdukt);
            }
        }else{
            $found = 1; // War 0 davor, aber PHP considered 0 als Empty
            array_push($_SESSION['produkt_array'],$arrayOfProdukt);
        }
    }
    if($debug){ // Debuged das Resultat und verhindert wie weiterleitung zum testen
        echo "Result:";
        #var_dump($_SESSION['produkt_array']);
            error_log(date("Y-m-d H:i:s", time()) . "\n
            Found: $found \n
            Menge: $menge \n
            ProduktID: $produkt_ID \n
            Session_id: ".session_id()."\n", 3, "my-debug-addToSession.log");
        #session_destroy();
    }
    if(!empty($found)){
        if($debug2)
            echo 200 ." $menge $produkt_ID ". var_dump($_SESSION['produkt_array']);;
        echo 201;
    }else{
        echo 406;
    }
?>