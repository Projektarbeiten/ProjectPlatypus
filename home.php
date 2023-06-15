<?php
session_start();
if (!isset($_SESSION['access_token']) || $_SESSION['access_token'] != true) {
    header("Location: index");
}
require("./phpFunctions/databaseConnection.php");
require("./phpFunctions/util.php");
$conn = buildConnection(".");
$bestseller = getBestseller($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>Home</title>
</head>

<body>
    <!-- Header -->
    <?php
    require("header.php");
    ?>
    <main id="kategorien-und-produkte">
        <div id="kategorien">
            <div class="container">
                <div class="row">
                    <div class="col-2 kategorie">
                        <h2 class="kategorie-name">Kategorie 1</h2>
                        <a href="">
                            <img class="kategorie-bild" src="./img/testBild.png" alt="Undefined picture">
                        </a>
                        <p class="kategorie-text">Kategorie Text 1</p>
                    </div>
                    <div class="col-2 kategorie">
                        <h2 class="kategorie-name">Kategorie 2</h2>
                        <a href="">
                            <img class="kategorie-bild" src="./img/testBild.png" alt="Undefined picture">
                        </a>
                        <p class="kategorie-text">Kategorie Text 2</p>
                    </div>
                    <div class="col-2 kategorie">
                        <h2 class="kategorie-name">Kategorie 3</h2>
                        <a href="">
                            <img class="kategorie-bild" src="./img/testBild.png" alt="Undefined picture">
                        </a>
                        <p class="kategorie-text">Kategorie Text 3</p>
                    </div>
                </div>
            </div>

        </div>
        <div id="produkt-bestseller">
            <div class="container">
                <div class="row">
                    <h1 id="bestseller-headline" class="col-6">Bestseller Shit</h1>
                </div>
                <?php
                $counter = 0;
                foreach ($bestseller as $product) {
                    $info = getProduktInfos($product, $conn);
                    $picture = getImage($product, $conn);
                    $produktName = $info[0];
                    $produktPreis = $info[9];
                    if ($counter == 0) {
                        echo "<div class='row'>";
                    }
                    echo ("<div class='col-2 produkt'>
                        <h2 class='Produkt-name' style='font-size: 1.2rem'>$produktName</h2>
                        <a href='http://localhost/productPage?produkt_id=$product'>
                            <img class='Produkt-bild' src='$picture' alt='Undefined picture'>
                        </a>
                        <p class='Produkt-text'><strong>{$produktPreis}€</strong></p>
                    </div>");
                    ++$counter;
                    if ($counter == 3) {
                        echo "</div>";
                        $counter = 0;
                    }
                }
                ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php
    require("footer.php");
    ?>

</body>

</html>