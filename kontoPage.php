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
            <p>Titel</p>
        </div>
        <div class="boxrechts">
            <p>Anrede</p>
        </div>
    </div>

    <!-- Vorname und Nachname Boxen -->
    <div style="display: flex;">
        <div class="boxlinks">
            <p>Vorname</p>
        </div>
        <div class="boxrechts">
            <p>Nachname</p>
        </div>
    </div>

    <!-- Email Box -->
    <div class="boxfull">
        <p>Email</p>
    </div>

    <!-- Geburtsdatum Box -->
    <div class="boxgeb">
        <p>Geburtsdatum</p>
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
        <p>Land</p>
    </div>

    <!-- Postleitzahl und Ort Boxen -->
    <div style="display: flex;">
        <div class="boxlinks">
            <p>Postleitzahl</p>
        </div>
        <div class="boxrechts">
            <p>Ort</p>
        </div>
    </div>

    <!-- Straße und Straßen Nr. Boxen -->
    <div style="display: flex;">
        <div class="boxstraße">
            <p>Straße</p>
        </div>
        <div class="boxstrnr">
            <p>Straßen Nr.</p>
        </div>
    </div>

    <!-- Adresszusatz Box -->
    <div class="boxfull">
        <p>Adresszusatz</p>
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
        <p>Bankname</p>
    </div>

    <!-- BIC und Land Dropdown-Button -->
    <div style="display: flex;">
        <div class="boxbic">
            <p>BIC</p>
        </div>
        <div>
            <select class="dropdown">
                <option>(Bitte Land wählen)</option>
                <option>Deutschland</option>
                <option>Österreich</option>
                <option>Schweiz</option>
            </select>
        </div>
    </div>

    <!-- IBAN Box -->
    <div class="boxfull">
        <p>IBAN</p>
    </div>

    <!-- Footer -->
    <?php
    require("footer.php");
    ?>
</body>

</html>