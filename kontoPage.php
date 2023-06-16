<?php
session_start();
require('./phpFunctions/databaseConnection.php');
require('./phpFunctions/sqlQueries.php');
require('./phpFunctions/sqlInserts.php');
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
    header("Location: index");
}
$debug = false;

if (!isset($_SESSION['uid'])) {
    header("Location: login");
    exit;
} else {
    $uid = $_SESSION['uid'];
    $conn = buildConnection(".");
    $row = getAccountInformation($uid, $conn);
    $anrede = $row['anrede'];
    $titel = $row['titel'];
    $vorname = $row['vorname'];
    $nachname = $row['nachname'];
    $email = $row['email'];
    $geburtsdatum = date_format(date_create($row['geburtsdatum']), "d.m.Y");
    $userland = $row['userland'];
    $plz = $row['plz'];
    $ort = $row['ort'];
    $strasse = $row['strasse'];
    $hausnr = $row['hausnr'];
    $adresszusatz = $row['adresszusatz'];
    $banknamen = $row['banknamen'];
    $bic = $row['bic'];
    $zahlland = $row['zahlland'];
    $iban = $row['iban'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>Konto</title>
</head>

<body>
    <!-- Header -->
    <?php
    require "header.php";
    ?>
    <main id="kontopage-personal-data">
        <!-- Dein Konto Box -->
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h1>Dein Konto</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <!-- Bestellungen Button -->
                    <a href="orderHistory"><button class="konto-page-button">Bestellungen</button></a>
                </div>
            </div>
        </div>

        <!-- Persönliche Daten Header -->
        <div class="headline">
            <h3>Persönliche Daten</h3>
        </div>

        <!-- Persönliche Daten Sektion -->
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <?php
                    echo "<p class='editable-field' id='konto-anrede'>$anrede</p>";
                    ?>
                </div>
                <div class="col-3">
                    <?php
                    if (!empty($row['titel'])) {
                        echo "<p class='editable-field' id='konto-titel'>$titel</p>";
                    } else {
                        echo "<p class='editable-field' id='konto-titel'> Titel nicht angegeben</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <?php
                    echo "<p class='editable-field' id='konto-vorname'>$vorname</p>";
                    ?></div>
                <div class="col-3">
                    <?php
                    echo "<p class='editable-field' id='konto-nachname'>$nachname</p>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <?php
                    echo "<p>$email</p>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <?php
                    echo "<p  class='editable-field' id='konto-geburtsdatum'>$geburtsdatum</p>";
                    ?>
                </div>
                <div class="col-3">
                    <p>
                        Passwort ändern?
                    </p> <!-- # TODO: Passwort änderung Funktion in Phase 4 -->
                </div>
            </div>
        </div>

        </div>

        <br>

        <div class="trennlinie" style="width: 60%; display: block; margin: 0 auto;"></div>

        <br>

        <!-- Adressangabe Header -->
        <div class="headline">
            <u>
                <h3>Adressangabe</h3>
            </u>
        </div>

        <!-- Adressangabe -->
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <?php
                    echo "<p class='editable-field' id='konto-userland'>$userland</p>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <?php
                    echo "<p  class='editable-field' id='konto-plz'>$plz</p>";
                    ?>
                </div>
                <div class="col-3">
                    <?php
                    echo "<p class='editable-field' id='konto-ort'>$ort</p>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <?php
                    echo "<p id='konto-strasse'>$strasse</p>";
                    ?>
                </div>
                <div class="col-3">
                    <?php
                    echo "<p id='konto-hausnr'>$hausnr</p>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <?php
                    if (!empty($adresszusatz)) {
                        echo "<p id='konto-adresszusatz'>$adresszusatz</p>";
                    } else {
                        echo "<p id='konto-adresszusatz'>Kein Adresszusatz</p>";
                    }
                    ?>
                </div>
            </div>
        </div>


        <!-- Zahlungsmethode Header -->
        <div class="headline">
            <u>
                <h3>Zahlungsmethode</h3>
            </u>
        </div>
        <form method="POST" action="?sendBankData">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <?php
                        if (isset($_POST['banknamen']) && isset($_POST['bic']) && isset($_POST['zahlland']) && isset($_POST['iban'])) {

                            $zahlungsmethode = array($_POST['iban'], $_POST['zahlland'], $_POST['bic'], $_POST['banknamen']);
                            $return = setZahlungsmittel($uid, $zahlungsmethode, $conn);
                            #INFO: Zahluingsinformation Speicher - Funktion
                            switch ($return) {
                                case 1:
                                    echo "<p style='text-align: center; color: ForestGreen'>Erfolgreich abgespeichert</p>";
                                    break;

                                case 2:
                                    echo "<p style='text-align: center; color: ForestGreen'>Erfolgreich abgespeichert</p>";
                                    break;

                                case 3:
                                    echo "<p style='text-align: center; color: red'>Es sind Fehler bei der Sendung der Daten an die Datenbank entstanden</p>";
                                    break;
                                default:
                                    echo "<p style='text-align: center; color: red'>Ein unbekannter Fehler ist entstanden. Bitte erneut versuchen</p>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="Bankname">Bankname:</label>
                        <?php
                        if (!empty($banknamen)) {
                            echo "<input type='text' name='banknamen' value='$banknamen' required placeholder='Bitte Banknamen eingeben'>";
                        } else {
                            echo "<input type='text' name='banknamen' required value='' placeholder='Bitte Banknamen eingeben'>";
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <label for="bic">BIC:</label>
                        <?php
                        if (!empty($bic)) {
                            echo "<input type='text' name='bic' value='$bic' title='Bitte gültige BIC angeben' placeholder='Bitte BIC angeben' pattern='^([a-zA-Z]{4}[a-zA-Z]{2}[a-zA-Z0-9]{2}([a-zA-Z0-9]{3})?)$'>";
                        } else {
                            echo "<input type='text' required name='bic' value='' title='Bitte gültige BIC angeben' placeholder='Bitte BIC angeben' pattern='^([a-zA-Z]{4}[a-zA-Z]{2}[a-zA-Z0-9]{2}([a-zA-Z0-9]{3})?)$'>";
                        }
                        ?>
                    </div>
                    <div class="col-3">
                        <label for="zahlland">Land:</label>
                        <select name="zahlland" class="dropdown">
                            <?php
                            if (!empty($zahlland)) {
                                echo "<option value='$zahlland' selected>$zahlland</option>'";
                            } else {
                                echo "<option value='NichtAngegeben' selected> Nicht angegeben </option>";
                            }
                            ?>
                            <option value="Deutschland">Deutschland</option>
                            <option value="Österreich">Österreich</option>
                            <option value="Schweiz">Schweiz</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="iban">IBAN: </label>
                        <?php
                        if (!empty($iban)) {
                            echo "<input type='text' name='iban' value='$iban' required placeholder='Bitte gültige IBAN angeben' title='IBAN nicht gültig' pattern='^[A-Z]{2}[0-9]{2}(?:[ ]?[0-9]{4}){4}(?:[ ]?[0-9]{1,2})?$'>";
                        } else {
                            echo "<input type='text' name='iban' value='' required placeholder='Bitte gültige IBAN angeben' title='IBAN nicht gültig' pattern='^[A-Z]{2}[0-9]{2}(?:[ ]?[0-9]{4}){4}(?:[ ]?[0-9]{1,2})?$'>";
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <button class="konto-page-button" type="submit">Zahlungsmethode speichern</button>

                    </div>
                </div>
            </div>
        </form>
    </main>
    <!-- Footer -->
    <?php
    require "footer.php";
    ?>
    <script src="./javascript/jquery-3.6.1.min.js"></script>
    <script src="./javascript/kontoPage.js"></script>
</body>

</html>