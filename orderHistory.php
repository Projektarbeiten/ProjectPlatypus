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
                    <h1 id="history-headline">Meine Bestellungen</h1>
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
            <div id="loader" class="center" style="display: none;">
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