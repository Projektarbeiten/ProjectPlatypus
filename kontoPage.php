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
    header("Location: login"); // TODO: Login Seite hinzufügen - Weiterleitung wenn man nicht eingeloggt ist
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
    <title>Konto</title>
</head>

<body>
    <!-- Header -->
    <?php
    require("header.php");
    ?>
    <!-- Dein Konto Box -->
    <div class="box1">
        <h1>Dein Konto</h1>
    </div>

    <!-- Bestellungen Button -->
    <button class="button1">Bestellungen</button> <!-- # TODO: Weiterleitung zur Bestellhistorie -->

    <!-- Persönliche Daten Header -->
    <div class="headline">
        <u>
            <h3>Persönliche Daten</h3>
        </u>
    </div>

    <!-- Titel und Anrede Boxen -->
    <div style="display: flex;">
        <div class="boxlinks">
            <?php
            if (!empty($row['titel'])) {
                echo '<p>' . $row['titel'] . '</p>';
            } else {
                echo '<p>' . '</p>';
            }
            ?>
        </div>
        <div class="boxrechts">
            <?php
            echo '<p>' . $row['anrede'] . '</p>';
            ?>
        </div>
    </div>

    <!-- Vorname und Nachname Boxen -->
    <div style="display: flex;">
        <div class="boxlinks">
            <?php
            echo '<p>' . $row['vorname'] . '</p>';
            ?>
        </div>
        <div class="boxrechts">
            <?php
            echo '<p>' . $row['nachname'] . '</p>';
            ?>
        </div>
    </div>

    <!-- Email Box -->
    <div class="boxfull">
        <?php
        echo '<p>' . $row['email'] . '</p>';
        ?>
    </div>

    <!-- Geburtsdatum Box -->
    <div class="boxgeb">
        <?php
        echo '<p>' . date_format(date_create($row['geburtsdatum']), "d.m.Y") . '</p>';
        ?>
    </div>

    <div class="password">
        <p>
            Passwort ändern?
        </p> <!-- # TODO: Passwort änderung Funktion in Phase 4 -->
    </div>

    <br>

    <hr style="width: 55%;">

    <br>

    <!-- Adressangabe Header -->
    <div class="headline">
        <u>
            <h3>Adressangabe</h3>
        </u>
    </div>

    <!-- Land Box -->
    <div class="boxfull">
        <?php
        echo '<p>' . $row['userland'] . '</p>';
        ?>
    </div>

    <!-- Postleitzahl und Ort Boxen -->
    <div style="display: flex;">
        <div class="boxlinks">
            <?php
            echo '<p>' . $row['plz'] . '</p>';
            ?>
        </div>
        <div class="boxrechts">
            <?php
            echo '<p>' . $row['ort'] . '</p>';
            ?>
        </div>
    </div>

    <!-- Straße und Straßen Nr. Boxen -->
    <div style="display: flex;">
        <div class="boxstraße">
            <?php
            echo '<p>' . $row['strasse'] . '</p>';
            ?>
        </div>
        <div class="boxstrnr">
            <?php
            echo '<p>' . $row['hausnr'] . '</p>';
            ?>
        </div>
    </div>

    <!-- Adresszusatz Box -->
    <div class="boxfull">
        <?php
        if (!empty($row['adresszusatz'])) {
            echo '<p>' . $row['adresszusatz'] . '</p>';
        } else {
            echo '<p> Leer </p>';
        }
        ?>
    </div>

    <br>

    <!-- Zahlungsmethode Header -->
    <div class="headline">
        <u>
            <h3>Zahlungsmethode</h3>
        </u>
    </div>
    <form method="post"> <!-- # TODO: Dieses Form benötigt noch Styling-->
        <!-- Bankname Box -->
        <?php
        if (isset($_POST['banknamen']) && isset($_POST['bic']) && isset($_POST['zahlland']) && isset($_POST['iban'])) {

            $zahlungsmethod = array($_POST['iban'], $_POST['zahlland'], $_POST['bic'], $_POST['banknamen']);
            $return = setZahlungsmittel($uid, $zahlungsmethod, $conn); 
            #INFO: Zahluingsinformation Speicher - Funktion
            switch ($return) {
                case 1:
                    echo "<p style='text-align: center; color: ForestGreen'>Erfolgreich abgespeichert</p>";
                    break;

                case 2:
                    echo "<p style='text-align: center; color: ForestGreen'>Erfolgreich abgespeichert</p>";
                    break;

                case 3:
                    echo "<p style='text-align: center; color: red'>Es sind Fehler entstanden</p>";
                    break;
            }
        }
        ?>
        <div class="boxfull">
            <label for="Bankname">Bankname:</label>
            <?php
            if (!empty($row['banknamen'])) {
                echo '<input type="text" name="banknamen" value="' . $row['banknamen'] . '" ><br>';
            } else {
                echo '<input type="text" name="banknamen" value="Nicht angegeben"><br>';
            }
            ?>
        </div>

        <!-- BIC und Land Dropdown-Button -->
        <div style="display: flex;">
            <div class="boxbic">
                <label for="bic">BIC:</label>
                <?php
                if (!empty($row['bic'])) {
                    echo '<input type="text" name="bic" value="' . $row['bic'] . '" ><br>';
                } else {
                    echo '<input type="text" name="bic" value="Nicht angegeben"> <br>';
                }
                ?>
            </div>
            <div>
                <label for="zahlland">Land:</label>
                <select name="zahlland" class="dropdown">
                    <?php
                    if (!empty($row['zahlland'])) {
                        echo '<option value="' . $row['zahlland'] . '" selected>' . $row['zahlland'] . '</option>';
                    } else {
                        echo '<option value="NichtAngegeben" selected> Nicht angegeben </option>';
                    }
                    ?>
                    <option value="Deutschland">Deutschland</option>
                    <option value="Österreich">Österreich</option>
                    <option value="Schweiz">Schweiz</option>
                </select>
            </div>
        </div>

        <!-- IBAN Box -->
        <div>
            <fieldset class="boxfull">
                <legend>IBAN</legend>
                <?php
                if (!empty($row['iban'])) {
                    echo '<input type="text" name="iban" value="' . $row['iban'] . '"><br>';
                } else {
                    echo '<input type="text" name="iban" value="Nicht angegeben"> <br>';
                }
                ?>
            </fieldset>
        </div>
        <div class=".boxrechts">
            <button class="button1" type="submit">Zahlungsmethode speichern</button>
        </div>
    </form>
    <!-- Footer -->
    <?php
    require("footer.php");
    ?>
</body>

</html>