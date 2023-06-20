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
    $accRow = login($email, $conn);
    $pw = $accRow[0];
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

        <?php
        if (isset($_POST["value"], $_POST["dataTarget"])) {
            echo "Update";
            updateUserEntry($uid, $_POST["dataTarget"], $_POST["value"], $conn);
        }
        ?>

        <!-- Persönliche Daten Sektion -->
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <label for="konto-anrede">Anrede</label>
                    <?php
                    echo "<p class='editable-field' id='konto-anrede'>$anrede</p>";
                    ?>
                </div>
                <div class="col-3">
                    <label for="konto-titel">Titel</label>
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
                    <label for="konto-vorname">Vorname</label>
                    <?php
                    echo "<p class='editable-field' id='konto-vorname'>$vorname</p>";
                    ?>
                </div>
                <div class="col-3">
                    <label for="konto-nachname">Nachname</label>
                    <?php
                    echo "<p class='editable-field' id='konto-nachname'>$nachname</p>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="konto-email">Email</label>
                    <?php
                    echo "<p id='konto-email'>$email</p>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <label for="konto-geburtsdatum">Geburtsdatum</label>
                    <?php
                    echo "<p  class='editable-field' id='konto-geburtsdatum'>$geburtsdatum</p>";
                    ?>
                </div>
                <div class="col-3">
                    <p id="password-change">
                        Passwort ändern?
                    </p>
                    <?php
                    //Changes the user password
                    if (isset($_POST['old-password'], $_POST['add-new-password'], $_POST['repeat-new-password'])) {
                        if (password_verify($_POST['old-password'], $pw)) {
                            if ($_POST['add-new-password'] === $_POST['repeat-new-password']) {
                                $newPassword = password_hash($_POST['add-new-password'], PASSWORD_DEFAULT);
                                if (updateUserPassword($uid, $newPassword, $conn)) {
                                    $_SESSION['userPassword'] = $newPassword;
                                    echo "<p style='color:limegreen; text-align:center; border: 0'><strong>Passwort geändert</strong></p>";
                                } else {
                                    echo "ERROR: Could not execute the query. " . $stmt->errorInfo();
                                }
                            } else {
                                echo "<p style='color:red; text-align:center; border: 0'>Passwort Wiederholung falsch</p>";
                            }
                        } else {
                            echo "<p style='color:red; text-align:center; border: 0'>Altes Passwort falsch</p>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        </div>

        <div class="trennlinie" style="width: 60%; display: block; margin: 0 auto;"></div>

        <!-- Adressangabe Header -->
        <div class="headline">
            <h3>Adressangabe</h3>
        </div>

        <!-- Adressangabe -->
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <label for="konto-land">Land</label>

                    <?php
                    echo "<p class='editable-field' id='konto-land'>$userland</p>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <label for="konto-plz">Postleitzahl</label>

                    <?php
                    echo "<p  class='editable-field' id='konto-plz'>$plz</p>";
                    ?>
                </div>
                <div class="col-3">
                    <label for="konto-ort">Wohnort</label>
                    <?php
                    echo "<p class='editable-field' id='konto-ort'>$ort</p>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <label for="konto-strasse">Straße</label>
                    <?php
                    echo "<p id='konto-strasse' class='editable-field'>$strasse</p>";
                    ?>
                </div>
                <div class="col-3">
                    <label for="konto-hausnr">Hausnummer</label>
                    <?php
                    echo "<p id='konto-hausnr' class='editable-field'>$hausnr</p>";
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label for="konto-adresszusatz">Adesszusatz</label>
                    <?php
                    if (!empty($adresszusatz)) {
                        echo "<p id='konto-adresszusatz' class='editable-field'>$adresszusatz</p>";
                    } else {
                        echo "<p id='konto-adresszusatz' class='editable-field'>Kein Adresszusatz</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="trennlinie" style="width: 60%; display: block; margin: 0 auto;"></div>


        <!-- Zahlungsmethode Header -->
        <div class="headline">
            <h3>Zahlungsmethode</h3>
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
                                    echo "<p style='text-align: center; color: ForestGreen; border: 0'>Erfolgreich abgespeichert</p>";
                                    break;

                                case 2:
                                    echo "<p style='text-align: center; color: ForestGreen; border: 0'>Erfolgreich abgespeichert</p>";
                                    break;

                                case 3:
                                    echo "<p style='text-align: center; color: red; border: 0'>Es sind Fehler bei der Sendung der Daten an die Datenbank entstanden</p>";
                                    break;
                                default:
                                    echo "<p style='text-align: center; color: red; border: 0'>Ein unbekannter Fehler ist entstanden. Bitte erneut versuchen</p>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="Bankname">Bankname</label>
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
                        <label for="bic">BIC</label>
                        <?php
                        if (!empty($bic)) {
                            echo "<input type='text' name='bic' value='$bic' title='Bitte gültige BIC angeben' placeholder='Bitte BIC angeben' pattern='^([a-zA-Z]{4}[a-zA-Z]{2}[a-zA-Z0-9]{2}([a-zA-Z0-9]{3})?)$'>";
                        } else {
                            echo "<input type='text' required name='bic' value='' title='Bitte gültige BIC angeben' placeholder='Bitte BIC angeben' pattern='^([a-zA-Z]{4}[a-zA-Z]{2}[a-zA-Z0-9]{2}([a-zA-Z0-9]{3})?)$'>";
                        }
                        ?>
                    </div>
                    <div class="col-3">
                        <label for="zahlland">Land</label>
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
                        <label for="iban">IBAN</label>
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