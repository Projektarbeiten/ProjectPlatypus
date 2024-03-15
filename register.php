<?php
session_start();
require_once './phpFunctions/databaseConnection.php';
require_once './phpFunctions/sqlInserts.php';
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
    header("Location: index");
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
    <title>Registrierung</title>
</head>

<body>
    <?php require "header.php" ?>
    <main>
        <?php
        if (!empty($_POST['pw'])) {
            if ($_POST['pw'] === $_POST['pwWiederholen']) {
                $conn = buildConnection('.');
                $response = registerUser(
                    $conn,
                    $_POST['email'],
                    $_POST['pw'],
                    $_POST['titel'],
                    $_POST['vorname'],
                    $_POST['nachname'],
                    $_POST['anrede'],
                    $_POST['geburtsdatum'],
                    $_POST['land'],
                    $_POST['plz'],
                    $_POST['ort'],
                    $_POST['strasse'],
                    $_POST['strassenr'],
                    $_POST['adresszusatz']
                );
                echo $response;
            } else {
                echo ("<script type='text/javascript' language='Javascript'>alert('Passwörter stimmen nicht überein')</script>"); # TODO: An Alert von Product Page anpassen (addToShoppingCart.js)
            }
        }
        ?>


        <div class="container">
            <form action="register.php" id="register-form" method="post">
                <div class="row">
                    <div class="col-6">
                        <h2>Registrieren</h2>
                        <div class="trennlinie"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <h3>Persönliche Daten</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3"><label for="">Anrede</label></div>
                    <div class="col-3"><label for="">Titel</label></div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <select name="anrede" form="register-form" required>
                            <?php if (isset($_POST['anrede'])) echo "<option value='{$_POST['anrede']}'>{$_POST['anrede']}</option>"; ?>
                            <option value="Herr">Herr</option>
                            <option value="Frau">Frau</option>
                            <option value="Divers">Divers</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <select name="titel" form="register-form">
                            <?php if (isset($_POST['titel'])) echo "<option value='{$_POST['titel']}'>{$_POST['titel']}</option>"; ?>
                            <option value=""></option>
                            <option value="Doktor">Doktor</option>
                            <option value="Professor">Professor</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3"><label for="vorname">Vorname</label></div>
                    <div class="col-3"><label for="nachname">Nachname</label></div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <input type="text" name="vorname" required value="<?php if (isset($_POST['vorname'])) echo $_POST['vorname']; ?>">
                    </div>
                    <div class="col-3">
                        <input type="text" name="nachname" required value="<?php if (isset($_POST['nachname'])) echo $_POST['nachname']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-3"><label for="geburtsdatum">Geburtsdatum</label></div>
                    <div class="col-3"><label for="email">E-Mail Adresse</label></div>
                </div>
                <div class="row">
                    <div class="col-3"> <input type="text" name="email" required value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
                    </div>
                    <div class="col-3"> 
                        <input type="date" id="geburtsdatum" name="geburtsdatum" required value="<?php if (isset($_POST['geburtsdatum'])) echo $_POST['geburtsdatum']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="register-country">Land</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <select name="land" id="register-country" form="register-form" required>
                            <?php if (isset($_POST['land'])) echo "<option value='{$_POST['land']}'>{$_POST['land']}</option>"; ?>
                            <option value="Afghanistan">Afghanistan</option>
                            <option value="Aland">Aland</option>
                            <option value="Albania">Albanien</option>
                            <option value="Algeria">Algerien</option>
                            <option value="Andorra">Andorra</option>
                            <option value="Angola">Angola</option>
                            <option value="Anguilla">Anguilla</option>
                            <option value="Antigua and Barbuda">Antigua und Barbuda</option>
                            <option value="Argentina">Argentinien</option>
                            <option value="Armenia">Armenien</option>
                            <option value="Aruba">Aruba</option>
                            <option value="Australia">Australien</option>
                            <option value="Austria">Österreich</option>
                            <option value="Azerbaijan">Aserbaidschan</option>
                            <option value="Bahamas">Bahamas</option>
                            <option value="Bahrain">Bahrain</option>
                            <option value="Bangladesh">Bangladesch</option>
                            <option value="Barbados">Barbados</option>
                            <option value="Belarus">Belarus</option>
                            <option value="Belgium">Belgien</option>
                            <option value="Belize">Belize</option>
                            <option value="Benin">Benin</option>
                            <option value="Bermuda">Bermuda</option>
                            <option value="Bhutan">Bhutan</option>
                            <option value="Bolivia">Bolivien</option>
                            <option value="Bosnia and Herzegovina">Bosnien und Herzegowina</option>
                            <option value="Botswana">Botswana</option>
                            <option value="Brazil">Brasilien</option>
                            <option value="British Indian Ocean Territory">Britisches Territorium des Indischen Ozeans</option>
                            <option value="Brunei Darussalam">Brunei Darussalam</option>
                            <option value="Bulgaria">Bulgarien</option>
                            <option value="Burkina Faso">Burkina Faso</option>
                            <option value="Burundi">Burundi</option>
                            <option value="Cambodia">Kambodscha</option>
                            <option value="Cameroon">Kamerun</option>
                            <option value="Canada">Kanada</option>
                            <option value="Cape Verde">Kap Verde</option>
                            <option value="Cayman Islands">Cayman Inseln</option>
                            <option value="Central African Republic">Zentralafrikanische Republik</option>
                            <option value="Chad">Tschad</option>
                            <option value="Chile">Chile</option>
                            <option value="China">China</option>
                            <option value="Christmas Island">Weihnachtsinsel</option>
                            <option value="Colombia">Kolumbien</option>
                            <option value="Comoros">Komoren</option>
                            <option value="Congo">Kongo</option>
                            <option value="Democratic Republic of the Congo">Demokratische Republik Kongo</option>
                            <option value="Cook Islands">Cookinseln</option>
                            <option value="Costa Rica">Costa Rica</option>
                            <option value="Cote D'Ivoire">Elfenbeinküste</option>
                            <option value="Croatia">Kroatien</option>
                            <option value="Cuba">Kuba</option>
                            <option value="Curacao">Curacao</option>
                            <option value="Cyprus">Zypern</option>
                            <option value="Czech Republic">Tschechien</option>
                            <option value="Denmark">Dänemark</option>
                            <option value="Djibouti">Dschibuti</option>
                            <option value="Dominica">Dominica</option>
                            <option value="Dominican Republic">Dominikanische Republik</option>
                            <option value="Ecuador">Ecuador</option>
                            <option value="Egypt">Ägypten</option>
                            <option value="El Salvador">El Salvador</option>
                            <option value="Equatorial Guinea">Äquatorialguinea</option>
                            <option value="Eritrea">Eritrea</option>
                            <option value="Estonia">Estland</option>
                            <option value="Ethiopia">Äthiopien</option>
                            <option value="Falkland Islands (Malvinas)">Falklandinseln (Malvinas)</option>
                            <option value="Faroe Islands">Färöer Inseln</option>
                            <option value="Fiji">Fidschi</option>
                            <option value="Finland">Finnland</option>
                            <option value="France">Frankreich</option>
                            <option value="Gabon">Gabun</option>
                            <option value="Gambia">Gambia</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Germany">Deutschland</option>
                            <option value="Ghana">Ghana</option>
                            <option value="Gibraltar">Gibraltar</option>
                            <option value="Greece">Griechenland</option>
                            <option value="Greenland">Grönland</option>
                            <option value="Grenada">Grenada</option>
                            <option value="Guatemala">Guatemala</option>
                            <option value="Guernsey">Guernsey</option>
                            <option value="Guinea">Guinea</option>
                            <option value="Guinea-Bissau">Guinea-Bissau</option>
                            <option value="Guyana">Guyana</option>
                            <option value="Haiti">Haiti</option>
                            <option value="Vatican City">Vatikanstadt</option>
                            <option value="Honduras">Honduras</option>
                            <option value="Hong Kong">Hongkong</option>
                            <option value="Hungary">Ungarn</option>
                            <option value="Iceland">Island</option>
                            <option value="India">Indien</option>
                            <option value="Indonesia">Indonesien</option>
                            <option value="Iran">Iran</option>
                            <option value="Iraq">Irak</option>
                            <option value="Ireland">Irland</option>
                            <option value="Isle of Man">Isle of Man</option>
                            <option value="Israel">Israel</option>
                            <option value="Italy">Italien</option>
                            <option value="Jamaica">Jamaika</option>
                            <option value="Japan">Japan</option>
                            <option value="Jersey">Jersey</option>
                            <option value="Jordan">Jordanien</option>
                            <option value="Kazakhstan">Kasachstan</option>
                            <option value="Kenya">Kenia</option>
                            <option value="Kiribati">Kiribati</option>
                            <option value="North Korea">Nordkorea</option>
                            <option value="South Korea">Südkorea</option>
                            <option value="Kosovo">Kosovo</option>
                            <option value="Kuwait">Kuwait</option>
                            <option value="Kyrgyzstan">Kirgisistan</option>
                            <option value="Lao People's Democratic Republic">Demokratische Volksrepublik Laos
                            </option>
                            <option value="Latvia">Lettland</option>
                            <option value="Lebanon">Libanon</option>
                            <option value="Lesotho">Lesotho</option>
                            <option value="Liberia">Liberia</option>
                            <option value="Libyan Arab Jamahiriya">Libyscher arabischer Jamahiriya</option>
                            <option value="Liechtenstein">Liechtenstein</option>
                            <option value="Lithuania">Litauen</option>
                            <option value="Luxembourg">Luxemburg</option>
                            <option value="Macao">Macao</option>
                            <option value="Macedonia">Mazedonien</option>
                            <option value="Madagascar">Madagaskar</option>
                            <option value="Malawi">Malawi</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="Maldives">Malediven</option>
                            <option value="Mali">Mali</option>
                            <option value="Malta">Malta</option>
                            <option value="Marshall Islands">Marshallinseln</option>
                            <option value="Mauritania">Mauretanien</option>
                            <option value="Mauritius">Mauritius</option>
                            <option value="Mexico">Mexiko</option>
                            <option value="Micronesia, Federated States of">Mikronesien, Föderierte Staaten von
                            </option>
                            <option value="Moldova, Republic of">Moldawien, Republik</option>
                            <option value="Monaco">Monaco</option>
                            <option value="Mongolia">Mongolei</option>
                            <option value="Montenegro">Montenegro</option>
                            <option value="Morocco">Marokko</option>
                            <option value="Mozambique">Mosambik</option>
                            <option value="Myanmar">Myanmar</option>
                            <option value="Namibia">Namibia</option>
                            <option value="Nauru">Nauru</option>
                            <option value="Nepal">Nepal</option>
                            <option value="Netherlands">Niederlande</option>
                            <option value="Netherlands Antilles">Niederländische Antillen</option>
                            <option value="New Zealand">Neuseeland</option>
                            <option value="Nicaragua">Nicaragua</option>
                            <option value="Niger">Niger</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Niue">Niue</option>
                            <option value="Norway">Norwegen</option>
                            <option value="Oman">Oman</option>
                            <option value="Pakistan">Pakistan</option>
                            <option value="Palau">Palau</option>
                            <option value="Panama">Panama</option>
                            <option value="Papua New Guinea">Papua Neu-Guinea</option>
                            <option value="Paraguay">Paraguay</option>
                            <option value="Peru">Peru</option>
                            <option value="Philippines">Philippinen</option>
                            <option value="Poland">Polen</option>
                            <option value="Portugal">Portugal</option>
                            <option value="Puerto Rico">Puerto Rico</option>
                            <option value="Qatar">Katar</option>
                            <option value="Romania">Rumänien</option>
                            <option value="Russian Federation">Russische Föderation</option>
                            <option value="Rwanda">Ruanda</option>
                            <option value="Saint Barthelemy">Heiliger Barthelemy</option>
                            <option value="Saint Helena">Heilige Helena</option>
                            <option value="Saint Kitts and Nevis">St. Kitts und Nevis</option>
                            <option value="Saint Lucia">St. Lucia</option>
                            <option value="Saint Martin">Sankt Martin</option>
                            <option value="Saint Pierre and Miquelon">Saint Pierre und Miquelon</option>
                            <option value="Saint Vincent and the Grenadines">St. Vincent und die Grenadinen</option>
                            <option value="Samoa">Samoa</option>
                            <option value="San Marino">San Marino</option>
                            <option value="Sao Tome and Principe">Sao Tome und Principe</option>
                            <option value="Saudi Arabia">Saudi-Arabien</option>
                            <option value="Senegal">Senegal</option>
                            <option value="Serbia">Serbien</option>
                            <option value="Serbia and Montenegro">Serbien und Montenegro</option>
                            <option value="Seychelles">Seychellen</option>
                            <option value="Sierra Leone">Sierra Leone</option>
                            <option value="Singapore">Singapur</option>
                            <option value="Sint Maarten">St. Martin</option>
                            <option value="Slovakia">Slowakei</option>
                            <option value="Slovenia">Slowenien</option>
                            <option value="Solomon Islands">Salomon-Inseln</option>
                            <option value="Somalia">Somalia</option>
                            <option value="South Africa">Südafrika</option>
                            <option value="South Georgia and the South Sandwich Islands">Süd-Georgien und die südlichen Sandwich-Inseln</option>
                            <option value="South Sudan">Südsudan</option>
                            <option value="Spain">Spanien</option>
                            <option value="Sri Lanka">Sri Lanka</option>
                            <option value="Sudan">Sudan</option>
                            <option value="Suriname">Suriname</option>
                            <option value="Svalbard and Jan Mayen">Spitzbergen und Jan Mayen</option>
                            <option value="Swaziland">Swasiland</option>
                            <option value="Sweden">Schweden</option>
                            <option value="Switzerland">Schweiz</option>
                            <option value="Syrian Arab Republic">Syrische Arabische Republik</option>
                            <option value="Taiwan, Province of China">Taiwan, Provinz Chinas</option>
                            <option value="Tajikistan">Tadschikistan</option>
                            <option value="Tanzania, United Republic of">Tansania, Vereinigte Republik</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Timor-Leste">Timor-Leste</option>
                            <option value="Togo">Togo</option>
                            <option value="Tokelau">Tokelau</option>
                            <option value="Tonga">Tonga</option>
                            <option value="Trinidad and Tobago">Trinidad und Tobago</option>
                            <option value="Tunisia">Tunesien</option>
                            <option value="Turkey">Türkei</option>
                            <option value="Turkmenistan">Turkmenistan</option>
                            <option value="Turks and Caicos Islands">Turks- und Caicosinseln</option>
                            <option value="Tuvalu">Tuvalu</option>
                            <option value="Uganda">Uganda</option>
                            <option value="Ukraine">Ukraine</option>
                            <option value="United Arab Emirates">Vereinigte Arabische Emirate</option>
                            <option value="United Kingdom">Vereinigtes Königreich</option>
                            <option value="United States">Vereinigte Staaten</option>
                            <option value="Uruguay">Uruguay</option>
                            <option value="Uzbekistan">Usbekistan</option>
                            <option value="Vanuatu">Vanuatu</option>
                            <option value="Venezuela">Venezuela</option>
                            <option value="Viet Nam">Vietnam</option>
                            <option value="Wallis and Futuna">Wallis und Futuna</option>
                            <option value="Yemen">Jemen</option>
                            <option value="Zambia">Sambia</option>
                            <option value="Zimbabwe">Zimbabwe</option>
                        </select>

                    </div>
                </div>
                <div class="row">
                    <div class="col-3"><label for="plz">Postleitzahl</label></div>
                    <div class="col-3"> <label for="ort">Ort</label></div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <input type="text" name="plz" required value="<?php if (isset($_POST['plz'])) echo $_POST['plz']; ?>">
                    </div>
                    <div class="col-3">
                        <input type="text" name="ort" required value="<?php if (isset($_POST['ort'])) echo $_POST['ort']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-3"><label for="strasse">Straße</label></div>
                    <div class="col-3"><label for="strassenr">Nr</label></div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <input type="text" name="strasse" required value="<?php if (isset($_POST['strasse'])) echo $_POST['strasse']; ?>">
                    </div>
                    <div class="col-3">
                        <input type="text" name="strassenr" required value="<?php if (isset($_POST['strassenr'])) echo $_POST['strassenr']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="adresszusatz">Adresszusatz</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <input type="text" name="adresszusatz" value="<?php if (isset($_POST['adresszusatz'])) echo $_POST['adresszusatz']; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-3"><label for="pw">Passwort</label></div>
                    <div class="col-3"><label for="pwWiederholen">Passwort wiederholen</label></div>
                </div>
                <div class="row">
                    <div class="col-3">

                        <input type="password" name="pw" title="Minimum 8 characters including 1 upper and lower case character + 1 special character or number" required pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*[@$!%*?&\d]).{8,128}$">
                    </div>
                    <div class="col-3">
                        <input type="password" name="pwWiederholen" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label for="agb">Hiermit aktzeptieren Sie unsere Nutzungsbedingungen sowie die AGB´s</label>
                        <input type="checkbox" name="agb" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <button type="submit" class="login-button">Registrieren</button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php require "footer.php"; ?>

</body>

</html>