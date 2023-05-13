<?php
    session_start();
    require("./phpFunctions/util.php");
    require("./phpFunctions/databaseConnection.php");
    
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
        <!-- /Header -->
        <div class="container">
            <div style="display: flex;">
                <div class="boxlinks" >
                    <h3>
                        Warenkorb
                    </h3>
                </div>
                <div class="sc-boxrechts" style="margin-left: -39.5px;">
                    <h3>
                        Kasse
                    </h3>
                </div>
            </div>
            <hr>
            <div class='col-6'>
            <?php
               
                if(!empty($_Session['produkt_array'])){
                    echo "
                            <p>Kein Produkt im Warenkorb</p>"
                       ;
                }else{
                    loadShoppingCartInformation();
                }
                # Produktkarte
                ?>
            </div>"
        </div>
    </body>
</html>