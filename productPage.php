<?php
session_start();
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
    header("Location: index");
}
$produkt_ID = $_GET['produkt_id'];
require(dirname(__FILE__) . "/phpFunctions/databaseConnection.php");
require(dirname(__FILE__) . "/phpFunctions/util.php");

$conn = buildConnection(".");
$pInfo = getProduktInfos($produkt_ID, $conn);

if ($pInfo === "ERROR") {
    # Wird ausgeführt, wenn unter der Produkt ID kein oder ein unvollständiges Verzeichnis gefunden wird.
    header("Location: error404.php");
}
$produktName = $pInfo[0];
$produktEigenschaft1 = $pInfo[1];
$produktEigenschaft2 = $pInfo[2];
$produktEigenschaft3 = $pInfo[3];
$produktEigenschaft4 = $pInfo[4];
$produktEigenschaft5 = $pInfo[5];
$produktEigenschaft6 = $pInfo[6];
$produktBeschreibung = $pInfo[7];
$produktMenge = $pInfo[8];
// Eventuell noch Rabatt (auch im SQL Query hinzufuegen)
$produktPreis = $pInfo[9];
$oemBezeichnung = $pInfo[10];

if (isset($_SESSION['uid'])) {
    $adressInfo = getUserAdresse($_SESSION['uid'], $conn);
    $userLand =  $adressInfo[0];
    $userPlz = $adressInfo[1];
    $userOrt = $adressInfo[2];
    $userStrasse = $adressInfo[3];
    $userHausnr = $adressInfo[4];
    $userAdresszusatz = $adressInfo[5];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./img/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
    <title><?php $produktName ?></title> <!-- Name will be changed from product name -->
</head>

<body>
    <!-- Header -->
    <?php
    require "header.php";
    ?>
    <main id="product-seite">
        <div class="container">
            <div class="flash_red">
                <div class="flash__icon_r">
                    <i class="icon bi bi-bag-x"></i>
                </div>
                <p class="flash__body_r"></p>
            </div>

            <div class="flash_green">
                <a href="shoppingCart" style="z-index: 999">
                    <div class="flash__icon_g">
                        <i class="icon bi bi-bag-plus"></i>
                    </div>
                    <p class="flash__body_g"></p>
                </a>
            </div>

            <div class="row">
                <div class="col-2">
                    <img id="produkt-bild" src="<?php echo getImage($produkt_ID, $conn); ?>" alt=""> <!-- Image wird anhand der Produkt ID Base64 encoded angezeigt und dynamisch geladen-->
                </div>
                <div class="col-2" id="produkt-eigenschaften">
                    <p style="font-weight: bold"><?php echo $produktName ?></p>
                    <p style="font-weight: bold"><?php echo $oemBezeichnung ?></p>
                    <table id="eigenschafts-tabelle">
                        <!-- Eigenschaften werden dynamisch hinzugefügt -->
                        <?php
                        for ($i = 1; $i <= 6; $i++) {
                            $eigenschaft = "produktEigenschaft$i";
                            # $$ Macht mit einer loop dynamische variablenNamen
                            if (!empty($$eigenschaft)) {
                                eingenschaften($$eigenschaft);
                            }
                        }
                        ?>
                    </table>
                </div>
                <div class="col-1-5" style="float:right">
                    <div id="bestell-section">
                        <div id="preis-section">
                            <div class='pp-price-before'>
                                <p id='pp-zw'> Aktueller Preis: </p>
                                <p id='sc-price-before-tag'><?php echo number_format(doubleval($produktPreis), 2, ',', '') . '€*' ?></p>
                            </div>
                            <div class='sc-mwst-box'>
                                <p id='sc-mwst'> *inkl. 19% MwSt: </p>
                                <p id='pp-price-mwst'> <?php echo number_format(doubleval($produktPreis - (($produktPreis / 119) * 100)), 2, ',', '') . ' €' ?></p>
                            </div>
                        </div>
                        <div class="trennlinie"></div>
                        <form method="post" id="bestell-form">
                            <input type="hidden" name="produkt_id" value="<?php echo $produkt_ID ?>">
                            <!-- Lieferadresse wird nur angezeigt, wenn der User eingeloggt ist. Menge und Inventar wird dynamisch angezeigt. -->
                            <label for="lieferadresse"></label>
                            <?php if (isset($_SESSION['uid'])) {
                                echo "<p>Ihre Lieferadresse: $userPlz $userOrt {$userStrasse}$userHausnr</p>";
                            } ?>

                            <?php
                            // Sollte die Produktmenge 0 sein, wird der Button und die Funktion eine Menge auszuwählen deaktiviert, um Fehler zub vermeiden
                            if ($produktMenge > 0) {
                                if ($produktMenge == 1) {
                                    echo "<p>Es ist noch ein Stück übrig</p>";
                                } else {
                                    echo "<p>Es sind noch $produktMenge Stück auf Lager</p>";
                                }
                                echo "<label for='mengenauswahl'>Menge:</label>
                            <select style='width:50px' name='mengenauswahl' id='mengenauswahl'>";
                                for ($i = 1; $i <= $produktMenge; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                };

                                echo '</select>
                                <button id="cart-button" name="AddToShippingCart" type="button">In den Einkaufswagen</button>';
                            } else {
                                echo "<p style='text-align:center'><strong>Produkt ist ausverkauft</strong></p>
                                <button id='cart-button' disabled>Ausverkauft</button>";
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h1>Details</h1>
                    <article>
                        <!-- Beschreibung wird von Datenbank Daten eingefügt -->
                        <?php if ($produktBeschreibung != "") {
                            echo "<p>$produktBeschreibung";
                        } else {
                            echo "<p style='text-align: center'>Keine Beschreibung vorhanden";
                        } ?>
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </main>

    <?php
    require "./footer.php";
    ?>
    <script src="javascript/jquery-3.6.1.min.js"></script>
    <script src="javascript\addToShoppingCart.js"></script>
</body>

</html>