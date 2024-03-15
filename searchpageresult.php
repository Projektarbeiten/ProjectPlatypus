<?php
session_start();
require_once "phpFunctions/databaseConnection.php";
require "phpFunctions/util.php";
$db = buildConnection();
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

<body>
    <!-- Header -->
    <?php
    require("header.php"); ?>
    <main class="searchpage">
        <div class="container">
            <div class="filter-container">
                <h3> Filteroptionen: </h3>
                <div id="filter-options"></div>
            </div>
            <?php
            require_once("./phpFunctions/sqlQueries.php");
            searchPageGenerator($db);
            ?>
            <script>
                function updateSliderValue(value, sliderId) {
                    var sliderElement = document.getElementById(sliderId);
                    var values = sliderElement.textContent.split(" ");
                    values[1] = value;
                    sliderElement.textContent = values.join(" ");
                }
                window.addEventListener('DOMContentLoaded', (event) => {
                    var minSliderValue = document.querySelector('input[name="minPrice"]').value;
                    var maxSliderValue = document.querySelector('input[name="maxPrice"]').value;
                    document.getElementById('min-price-range').textContent = 'Min: ' + minSliderValue + ' Max: ' + maxSliderValue;
                    document.getElementById('max-price-range').textContent = 'Min: ' + minSliderValue + ' Max: ' + maxSliderValue;
                });
            </script>
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
            if (firstone == true) {

            } else {}
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
<script src="./javascript/jquery-3.6.1.min.js"></script>

</html>