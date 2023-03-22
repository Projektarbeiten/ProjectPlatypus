<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>Product Page</title> <!-- Name will be changed from product name -->
</head>

<body>
    <!-- Header wird von Kevin Junker implementiert (php import) -->
    <header>
        <nav>

        </nav>
        <img src="" alt="">
        <nav>

        </nav>
    </header>
    <main id="product-seite">
        <div class="container">
            <div class="row">
                <div class="col-2">
                    <img id="produkt-bild" src="./img/testBild.png" alt=""> <!-- Src wird dynamisch nach Produkt ausgewählt-->
                </div>
                <div class="col-2" id="produkt-eigenschaften">
                    <p style="font-weight: bold">Produkteigenschaften</p>
                    <table id="eigenschafts-tabelle">
                        <!-- Eigenschaften werden dynamisch hinzugefügt -->
                        <tr class="eigenschaft-row">
                            <td>Eigenschaft 1</td>
                            <td>Wert 1</td>
                        </tr>
                        <tr class="eigenschaft-row">
                            <td>Eigenschaft 2</td>
                            <td>Wert 2</td>
                        </tr>
                        <tr class="eigenschaft-row">
                            <td>Eigenschaft 3</td>
                            <td>Wert 3</td>
                        </tr>
                    </table>
                </div>
                <div class="col-1-5" style="float:right">
                    <div id="bestell-section">
                        <div id="preis-section">
                            <p>Preiskarte</p>
                            <!-- Preis wird dynamisch hinzugefügt -->
                            <p>UVP: <strong>20.99€</strong></p>
                            <p>Unser Preis: <strong>14.99€</strong></p>
                        </div>
                        <div class="trennlinie"></div>
                        <form action="" method="post" id="bestell-form">
                            <!-- Lieferadresse wird nur angezeigt, wenn der User eingeloggt ist. Menge und Inventar wird dynamisch angezeigt. -->
                            <label for="lieferadresse"></label>
                            <a href="">
                                <p>Ihre Lieferadresse: 74523 Schwäbisch Hall</p>
                            </a>
                            <a href="">
                                <p>Es sind noch 4 Stück übrig</p>
                            </a>
                            <label for="mengenauswahl">Menge:</label>
                            <select style="width:50px" name="mengenauswahl" id="mengenauswahl">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                            <button id="cart-button" type="submit">In den Einkaufswagen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h1>Details</h1>
                    <article>
                        <!-- Beschreibung wird dynamisch eingefügt -->
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore officia iure nihil
                            distinctio eligendi impedit vel veniam hic quam explicabo ex ipsa rerum iste illo esse ipsam autem, aliquam possimus!
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore officia iure nihil distinctio eligendi impedit vel v
                            eniam hic quam explicabo ex ipsa rerum iste illo esse ipsam autem, aliquam possimus!
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Inventore officia iure nihil
                            distinctio eligendi impedit vel veniam hic quam explicabo ex ipsa rerum iste illo esse ipsam autem, aliquam possimus!
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </main>

    <?php
    require_once("footer.php");
    ?>

</body>
</html>