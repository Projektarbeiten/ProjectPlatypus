<?php
session_start();
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
                <div class="row">
                    <div class="col-2 kategorie">
                        <h2 class="kategorie-name">Kategorie 4</h2>
                        <a href="">
                            <img class="kategorie-bild" src="./img/testBild.png" alt="Undefined picture">
                        </a>
                        <p class="kategorie-text">Kategorie Text 4</p>
                    </div>
                    <div class="col-2 kategorie">
                        <h2 class="kategorie-name">Kategorie 5</h2>
                        <a href="">
                            <img class="kategorie-bild" src="./img/testBild.png" alt="Undefined picture">
                        </a>
                        <p class="kategorie-text">Kategorie Text 5</p>
                    </div>
                    <div class="col-2 kategorie">
                        <h2 class="kategorie-name">Kategorie 6</h2>
                        <a href="">
                            <img class="kategorie-bild" src="./img/testBild.png" alt="Undefined picture">
                        </a>
                        <p class="kategorie-text">Kategorie Text 6</p>
                    </div>
                </div>
            </div>

        </div>
        <div id="produkt-bestseller">
            <div class="container">
                <div class="row">
                    <h1 id="bestseller-headline" class="col-6">Bestseller Shit</h1>
                </div>

                <!-- Hier werden die Produkte durch php eingefügt (Beispiel später entfernen)-->

                    <div class="row">
                        <div class="col-2 produkt">
                            <h2 class="Produkt-name">Produkt 1</h2>
                            <a href="http://localhost/productPage?produkt_id=2">
                                <img class="Produkt-bild" src="./img/testBild.png" alt="Undefined picture">
                            </a>
                            <p class="Produkt-text">Produkt Text 1</p>
                        </div>
                        <div class="col-2 produkt">
                            <h2 class="Produkt-name">Produkt 2</h2>
                            <a href="">
                                <img class="Produkt-bild" src="./img/testBild.png" alt="Undefined picture">
                            </a>
                            <p class="Produkt-text">Produkt Text 2</p>
                        </div>
                        <div class="col-2 produkt">
                            <h2 class="Produkt-name">Produkt 3</h2>
                            <a href="">
                                <img class="Produkt-bild" src="./img/testBild.png" alt="Undefined picture">
                            </a>
                            <p class="Produkt-text">Produkt Text 3</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>

    <!-- Footer -->
    <?php
    require("footer.php");
    ?>
    
</body>

</html>