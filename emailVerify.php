<?php
session_start();
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
    header("Location: index");
}
$verification = false;
/*if(!isset($_GET["emailVerification"])) {
    header("Location: index");
}*/
require(dirname(__FILE__) . "/phpFunctions/databaseConnection.php");
require(dirname(__FILE__) . "/phpFunctions/util.php");
$conn = buildConnection();

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
                        echo "<p id='verify-message'>Ihre E-Mail wurde Erfolgreich auf Platyweb.de verifiziert</p>";
                    } else {
                        echo "<p id='verify-message'>Ihre E-Mail konnte nicht bestätigt werden.</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <a href="index"></a><button class="button" id="verify-button">Email Bestätigung</button></a>
                </div>
            </div>
            </form>

        </div>
    </div>
    <div id="login-footer">
        <?php
        require("footer.php");
        ?>
    </div>
</body>

</html>