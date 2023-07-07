<?php
session_start();
require(dirname(__FILE__) . "/phpFunctions/databaseConnection.php");
require(dirname(__FILE__) . "/phpFunctions/util.php");
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
    header("Location: index");
}
$verification = false;
$conn = buildConnection();
if (isset($_GET["verificationCode"])) {
    $verification = checkVerification($_GET['verificationCode'], $conn);
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email verify</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <div class="main">
        <div class="container" id="verify-page">
            <div class="row">
                <div class="col-6">
                    <h1>Email Verifikation</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <?php
                    if ($verification) {
                        echo "<p id='verify-message'>Ihre E-Mail wurde Erfolgreich bei Platyweb.de verifiziert</p>";
                        deleteVerificationeCode($_GET['verificationCode'], $conn);
                    } else {
                        echo "<p id='verify-message'>Ihre E-Mail konnte nicht bestätigt werden.</p></div></div>";
                        echo "
                        
                        <div class='row'>
                            <div class='col-6'>
                                <label  class='text-center center' for='email'>Enter your Email</label>
                                <input class='center email' type='email' name='email' id='email-input'>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-6'>
                                <a href='index'></a><button class='button' id='verify-button' value=''>Verifizierungs Email erneut anfordern</button></a>
                            </div>
                        </div>
                        
                        ";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div id="login-footer">
        <?php
        require("footer.php");
        ?>
    </div>
    <script src="./javascript/jquery-3.6.1.min.js"></script>
    <script src="./javascript/emailVerifiy.js"></script>
</body>

</html>