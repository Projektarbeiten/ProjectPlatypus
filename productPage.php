<?php
$produkt = $_GET['produkt_id'];
require("./phpFunctions/databaseConnection.php");
require("./phpFunctions/sqlQueries.php");
$conn = buildConnection(".");
$pInfo = getProduktInfos($produkt, $conn);

if ($pInfo === "ERROR") {
    header("Location: error404.php");
}
$pInfo[0] = $produktName;
$pInfo[1] = $bildSrc;
$pInfo[2] = $produktEigenschaft1;
$pInfo[3] = $produktEigenschaft2;
$pInfo[4] = $produktEigenschaft3;
$pInfo[5] = $produktEigenschaft5;
$pInfo[6] = $produktEigenschaft6;
$pInfo[7] = $produktBeschreibung;
$pInfo[8] = $produktMenge;
// Eventuell noch Rabatt (auch im SQL Query hinzufuegen) 
$pInfo[9] = $produktPreis;
$pInfo[10] = $oemBezeichnung;

$userLand;
$userPlz;
$userOrt;
$userStrasse;
$userHausnr;
$userAdresszusatz;

if ($_SESSION['loggedin'] = true) {
    $adressInfo = getUserAdresse($_SESSION['u_id'], $conn);
    $userLand =  $adressInfo[0];
    $userPlz = $adressInfo[1];
    $userOrt = $adressInfo[2];
    $userStrasse = $adressInfo[3];
    $userHausnr = $adressInfo[4];
    $userAdresszusatz = $adressInfo[5];
}

function eingenschaften($eigenschaft) {
    try {$str_arr = explode (",", $eigenschaft);
    echo"<tr class='eigenschaft-row'>
    <td>{$str_arr[0]}</td>
    <td>{$str_arr[1]}</td>
    </tr>";
}
catch(Exception $e) {
    echo"<tr class='eigenschaft-row'>
    <td>{$eigenschaft}</td>
    </tr>";
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title><?php $produktName ?></title> <!-- Name will be changed from product name -->
</head>

<body>
    <!-- Header -->
    <?php
    require("header.php");
    ?>
    <main id="product-seite">
        <div class="container">
            <div class="row">
                <div class="col-2">
                    <img id="produkt-bild" src="./img/testBild.png" alt=""> <!-- Src wird dynamisch nach Produkt ausgewählt-->
                </div>
                <div class="col-2" id="produkt-eigenschaften">
                    <p style="font-weight: bold"><?php $produktName ?></p>
                    <p style="font-weight: bold"><?php $oemBezeichnung ?></p>
                    <table id="eigenschafts-tabelle">
                        <!-- Eigenschaften werden dynamisch hinzugefügt -->
                        <?php if($produktEigenschaft1 != "") 
                        eingenschaften($eigenschaft1)
                        ?>
                        <?php if($produktEigenschaft2 != "")
                        eingenschaften($eigenschaft2)
                        ?>
                        <?php if($produktEigenschaft3 != "")
                        eingenschaften($eigenschaft3)
                        ?>
                        <?php if($produktEigenschaft4 != "")
                        eingenschaften($eigenschaft4)
                        ?>
                        <?php if($produktEigenschaft5 != "")
                        eingenschaften($eigenschaft5)
                        ?>
                        <?php if($produktEigenschaft6 != "")
                        eingenschaften($eigenschaft6)

                        ?>
                    </table>
                </div>
                <div class="col-1-5" style="float:right">
                    <div id="bestell-section">
                        <div id="preis-section">
                            <p>Preiskarte</p>
                            <!-- Preis wird dynamisch hinzugefügt -->
                            <!-- Alter Preis wird mit UVP ausgetauscht <p>Alter Preis: <strong>20.99€</strong></p> -->
                            <p>Aktueller Preis: <strong><?php $produktPreis ?></strong></p>
                        </div>
                        <div class="trennlinie"></div>
                        <form action="" method="post" id="bestell-form">
                            <!-- Lieferadresse wird nur angezeigt, wenn der User eingeloggt ist. Menge und Inventar wird dynamisch angezeigt. -->
                            <label for="lieferadresse"></label>
                            <a href="">
                            <?php echo"<p>Ihre Lieferadresse: $userPlz $userOrt {$userStrasse}$userHausnr" ?></p>
                            </a>
                            <a href="">
                                <p>Es sind noch <? $produktMenge ?> Stück auf Lager</p>
                            </a>
                            <label for="mengenauswahl">Menge:</label>
                            <select style="width:50px" name="mengenauswahl" id="mengenauswahl">
                                <?php
                                for ($i = 1; $i <= $produktMenge; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                };
                                ?>
                                <button id="cart-button" type="submit">In den Einkaufswagen</button>
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
                        <!-- Beschreibung wird dynamisch eingefügt -->
                        <p><?php $produktBeschreibung ?>
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </main>

    <?php
    require_once("footer.php");
    ?>
</body>
</html>