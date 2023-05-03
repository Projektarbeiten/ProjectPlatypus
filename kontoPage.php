<?php
    session_start();
    require './phpFunctions/databaseConnection.php';
    require './phpFunctions/sqlQueries.php';
    $debug = true;

    if(!isset($_SESSION['uid'])){
        header("Location: login"); // TODO: Login Seite hinzufügen - Weiterleitung wenn man nicht eingeloggt ist
        exit;
    }else{
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
				if(!empty($row['titel'])){
					echo '<p>'. $row['titel'] .'</p>';
				}else{
					echo '<p>'.'</p>';
				}
            ?>
        </div>
        <div class="boxrechts">
            <?php
                echo '<p>'. $row['anrede'] .'</p>';
            ?>
        </div>
    </div>

    <!-- Vorname und Nachname Boxen -->
    <div style="display: flex;">
        <div class="boxlinks">
            <?php
                echo '<p>'. $row['vorname'] .'</p>';
            ?>
        </div>
        <div class="boxrechts">
            <?php
                echo '<p>'. $row['nachname'] .'</p>';
            ?>
        </div>
    </div>

    <!-- Email Box -->
    <div class="boxfull">
            <?php
                echo '<p>'. $row['email'] .'</p>';
            ?>
    </div>

    <!-- Geburtsdatum Box -->
    <div class="boxgeb">
            <?php
                echo '<p>'. date_format(date_create($row['geburtsdatum']),"d.m.Y") .'</p>';
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
				echo'<p>'.$row['userland'].'</p>';
            ?>
    </div>

    <!-- Postleitzahl und Ort Boxen -->
    <div style="display: flex;">
        <div class="boxlinks">
            <?php
                echo '<p>'. $row['plz'] .'</p>';
            ?>
        </div>
        <div class="boxrechts">
            <?php
                echo '<p>'. $row['ort'] .'</p>';
            ?>
        </div>
    </div>

    <!-- Straße und Straßen Nr. Boxen -->
    <div style="display: flex;">
        <div class="boxstraße">
            <?php
                echo '<p>'. $row['strasse'] .'</p>';
            ?>
        </div>
        <div class="boxstrnr">
            <?php
                echo '<p>'. $row['hausnr'] .'</p>';
            ?>
        </div>
    </div>

    <!-- Adresszusatz Box -->
    <div class="boxfull">
            <?php
                if(!empty($row['adresszusatz'])){
					echo '<p>'. $row['adresszusatz'] .'</p>';
				}else{
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

    <!-- Bankname Box -->
    <div class="boxfull">
            <?php
                if(!empty($row['bankname'])){
					echo '<p>'. $row['bankname'] .'</p>';
				}else{
					echo '<p> Nicht angegeben </p>';
				}
            ?>
    </div>

    <!-- BIC und Land Dropdown-Button -->
    <div style="display: flex;">
        <div class="boxbic">
            <?php
                if(!empty($row['bic'])){
					echo '<p>'. $row['bic'] .'</p>';
				}else{
					echo '<p> Nicht angegeben </p>';
				}
            ?>
        </div>
        <div>
            <select class="dropdown">
                <?php
                    echo '<option>'. $row['zahlland'] .'</option>'
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
                if(!empty($row['iban'])){
					echo '<p>'. $row['iban'] .'</p>';
				}else{
					echo '<p> Nicht angegeben </p>';
				}
            ?>
    </div>

    <!-- Footer -->
    <?php
    require("footer.php");
    ?>
</body>

</html>