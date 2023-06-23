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
</script>
<body>
    <!-- Header -->
    <?php
    require("header.php"); ?>
    <main class="searchpage">
        <div class="container">
            <?php
            if (isset($_GET['search']))
                ; {
                $search = $_GET['search'];
                $stmt =
                    "SELECT p_id, bezeichnung, akt_preis, eigenschaft_1, eigenschaft_2, eigenschaft_3, eigenschaft_4, eigenschaft_5, eigenschaft_6
                    FROM produkt
                    WHERE bezeichnung LIKE '%{$search}%'
                    OR eigenschaft_1 LIKE '%{$search}%' OR eigenschaft_2 LIKE '%{$search}%' OR eigenschaft_3 LIKE '%{$search}%' OR eigenschaft_4 LIKE '%{$search}%' OR eigenschaft_5 LIKE '%{$search}%'OR eigenschaft_6 LIKE '%{$search}%'";
                $preparedstmt = $db->prepare($stmt);
                //$preparedstmt->bindParam(':search',$search);
                
                $counter = 0;
                $result = $preparedstmt->execute();
                while ($row = $preparedstmt->fetch()) {
                    if ($counter == 0) {
                        echo "<div class=row>";
                    }
                    echo"
                    <script>
                    singleProduct = []
                    singleProduct.push('{$row['p_id']}')
                    singleProduct.push('{$row['bezeichnung']}')
                    singleProduct.push('{$row['akt_preis']}')
                    singleProduct.push('{$row['eigenschaft_1']}')
                    singleProduct.push('{$row['eigenschaft_2']}')
                    singleProduct.push('{$row['eigenschaft_3']}')
                    singleProduct.push('{$row['eigenschaft_4']}')
                    singleProduct.push('{$row['eigenschaft_5']}')
                    singleProduct.push('{$row['eigenschaft_6']}')
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
                echo "<script> console.log(products)</script>";

            }
            ?>
        </div>
    </main>
</body>
<script>
    </script>
<!-- Footer -->
<?php
require("footer.php");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

</html>