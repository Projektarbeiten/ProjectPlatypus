<?php
    session_start();

    require './phpFunctions/databaseConnection.php';
    require './phpFunctions/sqlQueries.php';
    $debug = true;

    if(!isset($_SESSION['uid'])){
        header("Location: http://www.google.de"); // TODO: Login Seite hinzufügen - Weiterleitung wenn man nicht eingeloggt ist
        exit;
    }else{
        $uid = $_SESSION['uid'];
        $conn = buildConnection(".");
        $result = getAccountInformation($conn,$uid);
        if(!$result->rowCount() > 0){
            echo ($debug) ?:'No Rows found';
             // Weiterleitung an Login Page
        }else{
            $row = $result->fetch();
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
    <button class="button1">Bestellungen</button>

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
                echo '<p>'. $row['u.titel'] .'</p>'
            ?>
        </div>
        <div class="boxrechts">
            <?php
                echo '<p>'. $row['u.anrede'] .'</p>'
            ?>
        </div>
    </div>

    <!-- Vorname und Nachname Boxen -->
    <div style="display: flex;">
        <div class="boxlinks">
            <?php
                echo '<p>'. $row['u.vorname'] .'</p>'
            ?>
        </div>
        <div class="boxrechts">
            <?php
                echo '<p>'. $row['u.nachname'] .'</p>'
            ?>
        </div>
    </div>

    <!-- Email Box -->
    <div class="boxfull">
            <?php
                echo '<p>'. $row['u.email'] .'</p>'
            ?>
    </div>

    <!-- Geburtsdatum Box -->
    <div class="boxgeb">
            <?php
                echo '<p>'. $row['u.geburtsdatum'] .'</p>'
            ?>
    </div>

    <div class="password">
        <p>
            Passwort ändern?
        </p>
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
                echo '<p>'. $row['u.land'] .'</p>'
            ?>
    </div>

    <!-- Postleitzahl und Ort Boxen -->
    <div style="display: flex;">
        <div class="boxlinks">
            <?php
                echo '<p>'. $row['u.plz'] .'</p>'
            ?>
        </div>
        <div class="boxrechts">
            <?php
                echo '<p>'. $row['u.ort'] .'</p>'
            ?>
        </div>
    </div>

    <!-- Straße und Straßen Nr. Boxen -->
    <div style="display: flex;">
        <div class="boxstraße">
            <?php
                echo '<p>'. $row['u.strasse'] .'</p>'
            ?>
        </div>
        <div class="boxstrnr">
            <?php
                echo '<p>'. $row['u.hausnr'] .'</p>'
            ?>
        </div>
    </div>

    <!-- Adresszusatz Box -->
    <div class="boxfull">
            <?php
                echo '<p>'. $row['u.adresszusatz'] .'</p>'
            ?>
    </div>

    <br>

    <!-- Zahlungsmethode Header -->
    <div class="headline">
        <u>
            <h3>Zahlungsmethode</h3>
        </u>
    </div>

    <!-- Bankname Box -->
    <div class="boxfull">
            <?php
                echo '<p>'. $row['zi.bankname'] .'</p>'
            ?>
    </div>

    <!-- BIC und Land Dropdown-Button -->
    <div style="display: flex;">
        <div class="boxbic">
            <?php
                echo '<p>'. $row['zi.bic'] .'</p>'
            ?>
        </div>
        <div>
            <select class="dropdown">
                <?php
                    echo '<option>'. $row['zi.land'] .'</option>'
                ?>
                <option>Deutschland</option>
                <option>Österreich</option>
                <option>Schweiz</option>
            </select>
        </div>
    </div>

    <!-- IBAN Box -->
    <div class="boxfull">
            <?php
                echo '<p>'. $row['zi.iban'] .'</p>'
            ?>
    </div>

    <!-- Footer -->
    <?php
    require("footer.php");
    ?>
</body>

</html>