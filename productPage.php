<?php
session_start();
$produkt = $_GET['produkt_id'];
require("./phpFunctions/databaseConnection.php");
require("./phpFunctions/sqlQueries.php");
$conn = buildConnection(".");
$pInfo = getProduktInfos($produkt, $conn);

if ($pInfo === "ERROR") {
    # Wird ausgeführt, wenn unter der Produkt ID kein oder ein unvollständiges Verzeichnis gefunden wird.
    header("Location: error404.php");
}
$produktName = $pInfo[0];
$bildSrc = $pInfo[1];
$produktEigenschaft1 = $pInfo[2];
$produktEigenschaft2 = $pInfo[3];
$produktEigenschaft3 = $pInfo[4];
$produktEigenschaft4 = $pInfo[5];
$produktEigenschaft5 = $pInfo[6];
$produktEigenschaft6 = $pInfo[7];
$produktBeschreibung = $pInfo[8];
$produktMenge = $pInfo[9];
// Eventuell noch Rabatt (auch im SQL Query hinzufuegen) 
$produktPreis = $pInfo[10];
$oemBezeichnung = $pInfo[11];

if ($_SESSION['loggedin'] = true) {
    $adressInfo = getUserAdresse($_SESSION['u_id'], $conn);
    $userLand =  $adressInfo[0];
    $userPlz = $adressInfo[1];
    $userOrt = $adressInfo[2];
    $userStrasse = $adressInfo[3];
    $userHausnr = $adressInfo[4];
    $userAdresszusatz = $adressInfo[5];
}

function eingenschaften($eigenschaft)
{
    try {
        $str_arr = explode(":", $eigenschaft);
        echo "<tr class='eigenschaft-row'>
    <td>{$str_arr[0]}</td>
    <td>{$str_arr[1]}</td>
    </tr>";
    } catch (Exception $e) {
        echo "<tr class='eigenschaft-row'>
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
                    <p style="font-weight: bold"><?php echo $produktName ?></p>
                    <p style="font-weight: bold"><?php echo $oemBezeichnung ?></p>
                    <table id="eigenschafts-tabelle">
                        <!-- Eigenschaften werden dynamisch hinzugefügt -->
                        <?php if ($produktEigenschaft1 != "")
                            eingenschaften($produktEigenschaft1)
                        ?>
                        <?php if ($produktEigenschaft2 != "")
                            eingenschaften($produktEigenschaft2)
                        ?>
                        <?php if ($produktEigenschaft3 != "")
                            eingenschaften($produktEigenschaft3)
                        ?>
                        <?php if ($produktEigenschaft4 != "")
                            eingenschaften($produktEigenschaft4)
                        ?>
                        <?php if ($produktEigenschaft5 != "")
                            eingenschaften($produktEigenschaft5)
                        ?>
                        <?php if ($produktEigenschaft6 != "")
                            eingenschaften($produktEigenschaft6)

                        ?>
                    </table>
                </div>
                <div class="col-1-5" style="float:right">
                    <div id="bestell-section">
                        <div id="preis-section">
                            <p>Preiskarte</p>
                            <!-- Preis wird dynamisch hinzugefügt -->
                            <!-- Alter Preis wird mit UVP ausgetauscht <p>Alter Preis: <strong>20.99€</strong></p> -->
                            <p>Aktueller Preis: <strong><?php echo $produktPreis ?>€</strong></p>
                        </div>
                        <div class="trennlinie"></div>
                        <form action="" method="post" id="bestell-form">
                            <!-- Lieferadresse wird nur angezeigt, wenn der User eingeloggt ist. Menge und Inventar wird dynamisch angezeigt. -->
                            <label for="lieferadresse"></label>
                            <?php if ($_SESSION['loggedin'] = true) {
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
                                <button id="cart-button" type="submit">In den Einkaufswagen</button>';
                            } else {
                                echo "<p><strong>Produkt ist ausverkauft</strong></p>
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
    require("./footer.php");
    ?>
</body>
</html>