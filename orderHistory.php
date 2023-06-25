<?php
session_start();
require "./phpFunctions/databaseConnection.php";
require "./phpFunctions/util.php";

if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
    header("Location: index");
}
if (!isset($_SESSION['uid'])) {
    header("Location: login");
    exit;
} else {
    $uid = $_SESSION['uid'];
    $conn = buildConnection(".");
    $row = getAccountInformation($uid, $conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>Bestellhistorie</title>
</head>

<body>
    <!-- Header -->
    <?php
    require "header.php";
    ?>
    <main id="order-history">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h1>Meine Bestellungen</h1>
                </div>
            </div>
            <div class="row">

                <div class="col-6">
                    <div id="order-filter">
                        <input type="text" id="order-search" placeholder="Alle Bestellungen durchsuchen">
                        <button id="order-filter-button">Filter (Monat/Jahr)</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="orders">
            <div class="order-card">
                <div class="row">
                    <div class="col-1-5">
                        <p><strong>Bestellung aufgegeben</strong></p>
                    </div>
                    <div class="col-1-5">
                        <p><strong>Anzahl Artikel</strong></p>
                    </div>
                    <div class="col-1-5">
                        <p><strong>Summe</strong></p>
                    </div>
                    <div class="col-1-5">
                        <p class="order-dropdown-arrow"><i class="arrow left"></i></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1-5">
                        <p>20.02.2023</p>
                    </div>
                    <div class="col-1-5">
                        <p>4</p>
                    </div>
                    <div class="col-1-5">
                        <p>36,88€</p> <!-- Gesamtpreis -->
                    </div>
                    <div class="col-1-5">
                        <button type="button" id="order-refund">Bestellung zurückschicken/Bestellung stornieren</button> <!-- Wert wird dynmaisch ermittelt -->
                    </div>
                </div>
                <div class="trennlinie" style="width: 100%; border-color: black"></div>
                <div class="order-dropdown">
                    <div class="row">
                        <div class="col-6 order-dropdown-closed">
                            <p>...</p>
                        </div>
                    </div>

                    <div class="order-dropdown-open" id="order-123" style="display:none"> <!-- ID wird von order in der Datenbank bestimmt -->
                        <div class="row">
                            <div class="col-2">
                                <p><strong>Versand am</strong></p>
                            </div>
                            <div class="col-2">
                                <p><strong>Zugestellt am /voraussichtliche Zustellung</strong></p>
                            </div>
                            <div class="col-2">
                                <button type="button" class="article-refund">Artikel zurückschicken/Artikel stornieren</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p>20.02.2023</p>
                            </div>
                            <div class="col-2">
                                <p>22.02.2023</p>
                            </div>
                        </div>
                        <div class="trennlinie" style="width: 100%; border-color: black"></div>
                        <div class="row">
                            <div class="col-1-5">
                                <p><strong>Produktname</strong></p>
                            </div>
                            <div class="col-1-5">
                                <p><strong>Einzelpreis</strong></p>
                            </div>
                            <div class="col-1-5">
                                <p><strong>Menge</strong></p>
                            </div>
                            <div class="col-1-5">
                                <p><strong>Summe</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1-5">
                                <a href="">
                                    <p>Produkt XYZ</p>
                                </a> <!-- An Verlinkung denken -->
                            </div>
                            <div class="col-1-5">
                                <p>12.34€</p>
                            </div>
                            <div class="col-1-5">
                                <p>3</p>
                            </div>
                            <div class="col-1-5">
                                <p>37.02€</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <img src="./img/testBild.png" alt="Produktbild">
                            </div>
                            <div class="col-4">
                                <p> Test Beschreibung: Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam illum autem tempore, sequi suscipit iste ipsum repudiandae itaque iure eligendi,
                                    dolor est delectus vel odit quasi nihil laboriosam corporis. Vero.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php
    require "footer.php";
    ?>
    <script src="./javascript/jquery-3.6.1.min.js"></script>
    <script src="./javascript/orderHistory.js"></script>
</body>

</html>