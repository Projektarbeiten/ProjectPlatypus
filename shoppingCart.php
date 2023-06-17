<?php
session_start();
require("./phpFunctions/databaseConnection.php");
require("./phpFunctions/util.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$conn = buildConnection(".");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    <title>Home</title>
</head>

<body>
    <!-- Header -->
    <?php
    require("header.php");
    ?>
    <!-- /Header -->
    <main>
        <div class="container artikel-container">
            <div style="display: flex;">
                <div class="boxlinks" style="background-color: grey; opacity: 90%">
                    <h3>
                        Warenkorb
                    </h3>
                </div>
                <div class="sc-boxrechts" style="margin-left: -39.5px;">
                    <h3>
                        Kasse
                    </h3>
                </div>
            </div>
            <hr>
            <?php
            if (isset($_SESSION['produkt_array']) && $_SESSION['produkt_array'] != null) {
                echo "
                            <table class='col-6'>
                                <thead>
                                    <tr>
                                        <th class='col-1'>Produktbild</th>
                                        <th class='col-2'>Produktinformationen</th>
                                        <th class='col-1'>Menge</th>
                                        <th class='col-1-5'>Preis</th>
                                    </tr>
                                </thead>
                            </table> ";
            }
            ?>
            <div class='col-6'>
                <?php
                # Produktkarte
                #var_dump($_SESSION['produkt_array']);
                if (!isset($_SESSION['produkt_array']) || empty(($_SESSION['produkt_array']))) {
                    echo "
                            <p style='text-align: center'>Kein Produkt im Warenkorb</p>";
                    error_log(date("Y-m-d H:i:s", time()) . "\n
                                Session_id: " . session_id() . "\n", 3, "my-debug-shoppingCart.log");
                } else {
                    loadAndPrintShoppingCartInformation($conn);
                }
                ?>
            </div>
            <?php
            if (isset($_SESSION['produkt_array']) && $_SESSION['produkt_array'] != null) {
                echo "
                    <div class='col-4'></div>
                        <div class='sc-box-between-endprice-and-productcard col-2 '>
                            <p>Gutschein/Rabattcode:</p>
                            <input type='text' id='sc-gutschein' class='col-4'></input>
                            <div class='col-4 sc-price-before'>
                                <p id='sc-zw'> Zwischensumme: </p>
                                <p id='sc-price-before-tag'></p>
                            </div>
                            <div>
                                <div class='col-4 sc-mwst-box'>
                                    <p id='sc-mwst'> 19% MwSt: </p>
                                    <p id='sc-price-mwst'></p>
                                </div>
                            </div>
                        </div>
                    <hr>
                    <div class='col-4' ></div>
                        <div class='col-2'>
                            <div class='col-4 sc-price-end-box'>
                                <p id='sc-gs'> Gesamtsumme: </p>
                                <p id='sc-price-end-tag'></p>
                            </div>
                            <div class='col-4 sc-price-safed-box' >
                                <p id='sc-safed'> Du sparst bei diesem Einkauf:  </p>
                                <p id='sc-price-safed-tag'></p>
                            </div>
                        </div>
                <div class='row'>
                    <div class='col-1'></div>
                    <div class='col-2'>
                        <button id='buy-more'>Weiter Einkaufen</button>
                    </div>
                    <div class='col-1'></div>
                    <div class='col-2'>
                        <button id='zur-kasse'>Zur Kasse</button>
                    </div>
                </div>
            </div>";
            } #TODO: Button Styling überarbeiten (mehr Platz)
            ?>
        </div>
    </main>
    <?php
    require("./footer.php");
    ?>
    <script src="./javascript/jquery-3.6.1.min.js"></script>
    <script src="./javascript/removeFromShoppingCart.js"></script>
</body>

</html>