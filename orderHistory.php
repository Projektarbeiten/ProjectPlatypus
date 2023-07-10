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
            <div class='container' id='order-filter-section' style="display:none">
                <div class='row'>
                    <div class='col-1-5'>
                        <label for='neueste'>Neueste</label>

                    </div>
                    <div class='col-1-5'>
                        <label for='aelteste'>Älteste</label>
                    </div>
                    <div class='col-1-5'>
                        <label for='jahr'>Jahr</label>

                    </div>
                    <div class='col-1-5'>
                        <label for='monat'>Monat</label>

                    </div>
                </div>
                <div class='row'>
                    <div class='col-1-5'>
                        <input type='checkbox' id='neueste'>
                    </div>
                    <div class='col-1-5'>
                        <input type='checkbox' id='aelteste'>

                    </div>
                    <div class='col-1-5'>
                        <select id='jahr'>
                            <option value='2023'>2023</option>
                        </select>
                    </div>
                    <div class='col-1-5'>

                        <select id='monat'>
                            <option value='1'>Januar</option>
                            <option value='2'>Februar</option>
                            <option value='3'>März</option>
                            <option value='4'>April</option>
                            <option value='5'>Mai</option>
                            <option value='6'>Juni</option>
                            <option value='7'>Juli</option>
                            <option value='8'>August</option>
                            <option value='9'>September</option>
                            <option value='10'>Oktober</option>
                            <option value='11'>November</option>
                            <option value='12'>Dezember</option>
                        </select>
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