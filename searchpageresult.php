<?php
require_once("phpFunctions/databaseConnection.php");
require("phpFunctions/util.php");
require("phpFunctions/sqlQueries.php");
$db = buildConnection(".");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>Suchergebnis</title>
</head>
<script>
let products = [];
let singleProduct = [];
let singleProductProperty = [];
</script>
<body>
    <!-- Header -->
    <?php
    require("header.php"); ?>
    <main class="searchpage">
        <div class="container">
        <div class="filter-container">
        <label class="filter-label">Filteroptionen:</label>
        <div id="filter-options"></div>
        <button onclick="applyFilters()">Anwenden</button>
    </div>
            <?php
            if (isset($_GET['search']))
                ; {
                $search = $_GET['search'];
                $stmt =
                    "SELECT p_id, bezeichnung, akt_preis, eigenschaft_1, eigenschaft_2, eigenschaft_3, eigenschaft_4, eigenschaft_5, eigenschaft_6
                    FROM produkt
                    WHERE bezeichnung LIKE '%{$search}%'
                    OR eigenschaft_1 LIKE '%{$search}%' OR eigenschaft_2 LIKE '%{$search}%' OR eigenschaft_3 LIKE '%{$search}%' OR eigenschaft_4 LIKE '%{$search}%' OR eigenschaft_5 LIKE '%{$search}%'OR eigenschaft_6 LIKE '%{$search}%'";
                if (!empty($kategorie)) {
                   // Kategorie die bei der Filterung ausgewählt wurde
                }
                $preparedstmt = $db->prepare($stmt);
                //$preparedstmt->bindParam(':search',$search);
                $counter = 0;
                $preparedstmt->execute();
                $eigenschaften = array();
                $products = array();

                if ($preparedstmt->rowCount() > 0)
                {
                    while ($row = $preparedstmt->fetch())
                    {
                        $eigenschaften[] = $row['eigenschaft_1'];
                        $eigenschaften[] = $row['eigenschaft_2'];
                        $eigenschaften[] = $row['eigenschaft_3'];
                        $eigenschaften[] = $row['eigenschaft_4'];
                        $eigenschaften[] = $row['eigenschaft_5'];
                        $products[] = $row;
                    }

                }
                var_dump($products);
                var_dump($eigenschaften);
                while ($row = $preparedstmt->fetch()) {
                    if ($counter == 0) {
                        echo "<div class=row>";
                    }
                    $products = array_push($row);
                    echo"
                    <script>
                    singleProduct = []
                    singleProductProperty = []
                    singleProduct.push('{$row['p_id']}')
                    singleProduct.push('{$row['bezeichnung']}')
                    singleProduct.push('{$row['akt_preis']}')
                    singleProduct.push('{$row['eigenschaft_1']}')
                    singleProduct.push('{$row['eigenschaft_2']}')
                    singleProduct.push('{$row['eigenschaft_3']}')
                    singleProduct.push('{$row['eigenschaft_4']}')
                    singleProduct.push('{$row['eigenschaft_5']}')
                    products.push(singleProduct)
                    </script>";
                    $p_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/productpage?produkt_id={$row["p_id"]}";
                    $p_b = getImage($row['p_id'], $db);
                    echo "<div class='col-1-2 .produkt'>
                        <h2 class='Produkt-name' style='font-size: 1.2rem'>{$row["bezeichnung"]}</h2>
                        <a href='$p_url'>
                        <img class='Produkt-bild' src={$p_b} style='width:85%;height:25vh' alt='Undefined picture'>
                        </a>
                        <p class='Produkt-text'>{$row['akt_preis']}€</p>
                        </div>";
                    $counter++;
                    if ($counter >= 4) {
                        echo "</div>";
                        $counter = 0;
                    }
                }
                $filterOptionen = array_unique($eigenschaften);
                // Anzeigen der Filteroptionen
                echo "<h2>Filter:</h2>";
                echo "<form action='suchergebnisse.php' method='GET'>";
                echo "<label for='options'>Eigenschaften</label>";
                echo "<select id='options' name='options>'";
                foreach ($filterOptionen as $option) {
                    echo "<option value='$option'> $option</option>";
                }
                echo "</select>";
                echo "<input type='submit' value='Filtern'>";
                    echo "</form>";
                var_dump($products);
                echo "<script> console.log(products)</script>";
            }
            ?>
        </div>
    </main>
</body>
<script>
    function applyFilters() {
        var selectedOptions = document.getElementsByClassName('filter-option');
        var filters = {};

        for (var i = 0; i < selectedOptions.length; i++) {
            var attribute = selectedOptions[i].getAttribute('data-attribute');
            var value = selectedOptions[i].value;
        if (value !== '') {
            filters[attribute] = value;
        }
        }

      // Hier kannst du die Filterlogik implementieren und die Suchergebnisse aktualisieren
        console.log('Angewendete Filter: ', filters);
    }

    function generateFilterOptions() {
        var filterOptions = document.getElementById('filter-options');

      var attributes = getUniqueAttributes(products); // Funktion zum Extrahieren eindeutiger Attribute
        let firstone = true;
        for (var attr in attributes) {
            if(firstone ==true)
            {

            }
            else
            {}
            var select = document.createElement('select');
            select.className = 'filter-option';
            select.setAttribute('data-attribute', attr);

            var defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = '-- Alle ' + attr + ' --';
            select.appendChild(defaultOption);

        for (var i = 0; i < attributes[attr].length; i++) {
            var option = document.createElement('option');
            option.value = attributes[attr][i];
            option.text = attributes[attr][i];
            select.appendChild(option);
        }

            filterOptions.appendChild(select);
        }
    }

    function getUniqueAttributes(products) {
    var attributes = {};

        for (var i = 0; i < products.length; i++) {
            var product = products[i];
        for (var attr in product) {
            if (product.hasOwnProperty(attr)) {
                if (!attributes[attr]) {
                attributes[attr] = [];
            }
            if (!attributes[attr].includes(product[attr])) {
                attributes[attr].push(product[attr]);
            }
        }
    }
}

    return attributes;
    }
    //window.onload = function() {
      //  generateFilterOptions();
    //};
    </script>
<!-- Footer -->
<?php
require("footer.php");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</html>