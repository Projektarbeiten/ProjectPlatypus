<?php
require("Classes.php");
function getAccountInformation($uid, $conn)
{
    try {
        $stmt_prep = $conn->prepare(
            'Select
                u.titel
                ,u.vorname
                ,u.nachname
                ,u.anrede
                ,u.email
                ,u.geburtsdatum
                ,u.land
                ,u.plz
                ,u.ort
                ,u.strasse
                ,u.hausnr
                ,u.adresszusatz
                ,zi.banknamen
                ,zi.land
                ,zi.bic
                ,zi.bezeichnung
                ,zi.iban
            from user u
            join zahlungsmethodexuser zxu
                on u.u_id = zxu.u_id_ref
            join zahlungsinformationen zi
                on zxu.zi_id_ref = zi.zi_id
            where u.u_id = :uid
            '
        );

        $stmt_prep->bindParam(':uid', $uid);
        $stmt_prep->execute();
        $result_set = $stmt_prep->setFetchMode(PDO::FETCH_ASSOC); // TODO: Falsch muss ersetzt werden durch
    } catch (PDOException $e) {
        die("ERROR: Could not able to execute $stmt_prep. " . $e->getMessage());
    }
    return $result_set;
}

/* ##---- login ----
        Zweck:
        Diese Funktion stellt fest ob ein User in der Datenbank exisitert um dies zurückzumelden.

        Returns:
        (Row Count <= 0)
        [True]:
        Gibt Passwort und User Id des Users innerhalb eines Arrays zurück

        [False]:
        Gibt das Array zurück, jedoch sind beide Indexe mit 0 zurück
        */
function login($email, $conn)
{
    try {
        $stmt_prep = $conn->prepare(
            'Select
                u.u_id
                ,pw.pw
            FROM
                user u
            JOIN passwort pw
                on u.pw_id_ref = pw.pw_id
            WHERE
                u.email = :email'
        );
        $stmt_prep->bindParam(':email', $email);
        $stmt_prep->execute();

        if (!$stmt_prep->rowCount() > 0) {
            $returns = array(0, 0);
        } else {
            $row = $stmt_prep->fetch();
            $returns = array($row['pw'], $row['u_id']);
        }
    } catch (PDOException $e) {
        die("ERROR: Could not able to execute $stmt_prep. " . $e->getMessage());
    }
    return $returns;
}


/* ---- getProduktInfos ----
    Gibt der productPage.php alle Produktinformationen um die Seite zu bauen
*/
function getProduktInfos($produktID, $conn) {
    try {
        $stmt_prep = $conn->prepare("
        SELECT
            p.bezeichnung
            ,p.eigenschaft_1
            ,p.eigenschaft_2
            ,p.eigenschaft_3
            ,p.eigenschaft_4
            ,p.eigenschaft_5
            ,p.eigenschaft_6
            ,p.details
            ,p.menge
            ,p.akt_preis
            ,h.bezeichnung as oem_bezeichnung
        FROM
            produkt p
        LEFT JOIN produktbild pb
            on p.p_b_id_ref = pb.p_b_id
        LEFT JOIN hersteller h
            on p.oem_id_ref = h.oem_id
        WHERE p.p_id = :produktID ;
		");
        $stmt_prep->bindParam(':produktID', $produktID);
        $stmt_prep->execute();
        #var_dump($result_set = $stmt_prep->fetchAll());

        // Sollte unter der ProduktID kein Eintrag gefunden werder, wird ein "Error" zurückgegeben um den User auf eine Errorpage umzuleiten.
        #print ("Type of:".gettype($result_set));
        try{
            if (!$stmt_prep->rowCount() > 0) {
            $returns = "ERROR";
        } else {
            $row = $stmt_prep->fetch();
            $returns = array($row['bezeichnung'], $row['eigenschaft_1'], $row['eigenschaft_2'], $row['eigenschaft_3'], $row['eigenschaft_4'], $row['eigenschaft_5'], $row['eigenschaft_6'], $row['details'], $row['menge'], $row['akt_preis'], $row['oem_bezeichnung']);
        }
        }catch (Exception $e){
            echo ("ERROR:". $e->getMessage());
        }
    }
    catch (PDOException $e) {
        die("ERROR: Could nto able to execute $stmt_prep. " . $e->getMessage());
    }
    return $returns;
}

/**
 * Summary of getUserAdresse
 * @param mixed $userID
 * @param mixed $conn
 * @return array
 */
function getUserAdresse($userID, $conn) {
        try {
        $stmt_prep = $conn->prepare("
        Select
            land
            ,plz
            ,ort
            ,strasse
            ,hausnr
            ,adresszusatz
        FROM
            user
        WHERE
            u_id = :userID
        ");
        $stmt_prep->bindParam(':userID', $userID);
        $stmt_prep->execute();
        $row = $stmt_prep->fetch();

        return array($row['land'], $row['plz'], $row['ort'], $row['strasse'], $row['hausnr'], $row['adresszusatz']);
        } catch (PDOException $e) {
            die("ERROR: Could not able to execute $stmt_prep. " . $e->getMessage());
        }
    }

	/**
	 * Summary of getProductImageData
	 * @param mixed $produktNr
	 * @param mixed $conn
	 * @return mixed
	 */
	function getProductImageData($produktNr, $conn) {
        // Statement for receiving Produkt - Produktbild shortcut
		try {
        $stmt_prep_produkt = $conn->prepare(
            "Select
                p_b_id_ref
            from
                produkt
            where
                p_id = :produktNr ;"
        );
        $stmt_prep_produkt->bindParam(':produktNr', $produktNr);
        $stmt_prep_produkt->execute();
        $row = $stmt_prep_produkt->fetch();
        $rowCount = $stmt_prep_produkt->rowCount();
        if($rowCount>0){
			try{ // Statement for receiving Image Data
						$stmt_prep_image = $conn->prepare(
							"Select
								image
							from
								produktbild
							where
								p_b_id = :id"
						);
						$stmt_prep_image->bindParam(':id', $row['p_b_id_ref']);
						$stmt_prep_image->execute();
						$row = $stmt_prep_image->fetch();
						$rowCount = $stmt_prep_produkt->rowCount();
					}catch(PDOException $e) {
						die("ERROR: Could not able to execute $stmt_prep_produkt. " . $e->getMessage());
				}
				if($rowCount>0){
					return $binaryImage = $row['image'];
				}else{
					return getDefaultImage($conn);
				}
		}
		else{
			return getDefaultImage($conn);
		}
		} catch(PDOException $e) {
			die("ERROR: Could not able to execute $stmt_prep_produkt. " . $e->getMessage());
		}
    }

	function getDefaultImage($conn){
		$stmt_prep = $conn->prepare("
		Select
			image
		from
			produktbild
		where
			p_b_id = 100000");
		$stmt_prep->execute();
		$row = $stmt_prep->fetch();
		return $row['image'];
	}

    function searchPageGenerator($db)
    {
        if (isset($_GET['search']) || isset($_POST['search'])) {
            $search = "";
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
            } else {
                $search = $_POST['search'];
            }
            $stmt =
                "SELECT p_id, bezeichnung, akt_preis, eigenschaft_1, eigenschaft_2, eigenschaft_3, eigenschaft_4, eigenschaft_5, eigenschaft_6
                FROM produkt
                WHERE (bezeichnung LIKE '%{$search}%'
                OR eigenschaft_1 LIKE '%{$search}%' OR eigenschaft_2 LIKE '%{$search}%' OR eigenschaft_3 LIKE '%{$search}%' OR eigenschaft_4 LIKE '%{$search}%' OR eigenschaft_5 LIKE '%{$search}%'OR eigenschaft_6 LIKE '%{$search}%')";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $selectedMinPrice = $_POST["minPrice"];
                $selectedMaxPrice = $_POST["maxPrice"];
                $stmt = $stmt . " AND akt_preis BETWEEN $selectedMinPrice AND $selectedMaxPrice";
            }
            $maxminstmt =
                "SELECT MAX(akt_preis) AS Maximal, MIN(akt_preis) AS Minimal
            FROM produkt
            WHERE bezeichnung LIKE '%{$search}%'
            OR eigenschaft_1 LIKE '%{$search}%' OR eigenschaft_2 LIKE '%{$search}%' OR eigenschaft_3 LIKE '%{$search}%' OR eigenschaft_4 LIKE '%{$search}%' OR eigenschaft_5 LIKE '%{$search}%'OR eigenschaft_6 LIKE '%{$search}%'";
            $preparedstmt = $db->prepare($stmt);
            $preparedmaxmin = $db->prepare($maxminstmt);
            $preparedmaxmin->execute();
            $max = "";
            $min = "";
            while ($row = $preparedmaxmin->fetch()) {
                $max = $row["Maximal"];
                $min = $row["Minimal"];
            }
            $selectedMinPrice = $min;
            $selectedMaxPrice = $max;
            echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
            echo '    <div class="slider-container">';
            echo '        <h4>Minimumpreis</h4>';
            echo '        <input type="range" name="minPrice" min="' . $min . '" max="' . $max . '" value="' . $selectedMinPrice . '" onchange="updateSliderValue(this.value, \'mincurrent\')">';
            echo '        <div id="min-price-range">Min: ' . $selectedMinPrice . ' Max: ' . $selectedMaxPrice . '</div>';
            echo '        <div id ="mincurrent">Aktuell: </div>';
            echo '    </div>';
            echo '    <div class="slider-container">';
            echo '        <h4>Maximumpreis</h4>';
            echo '        <input type="range" name="maxPrice" min="' . $min . '" max="' . $max . '" value="' . $selectedMaxPrice . '" onchange="updateSliderValue(this.value, \'maxcurrent\')">';
            echo '        <div id="max-price-range">Min: ' . $selectedMinPrice . ' Max: ' . $selectedMaxPrice . '</div>';
            echo '        <div id ="maxcurrent">Aktuell: </div>';
            echo '    </div>';
            echo '<input type="hidden" name="search" value="' . $search . '" id="search">';
            echo '    <button type="submit">Filter anwenden</button>';
            echo '</form>';
            $counter = 0;
            $preparedstmt->execute();
            while ($row = $preparedstmt->fetch()) {
                if ($counter == 0) {
                    echo "<div class=row>";
                }
                // $products = array_push($row);
                //echo "
                // <script>
                // singleProduct = []
                // singleProductProperty = []
                // singleProduct.push('{$row['p_id']}')
                // singleProduct.push('{$row['bezeichnung']}')
                // singleProduct.push('{$row['akt_preis']}')
                // singleProduct.push('{$row['eigenschaft_1']}')
                //  singleProduct.push('{$row['eigenschaft_2']}')
                // singleProduct.push('{$row['eigenschaft_3']}')
                //singleProduct.push('{$row['eigenschaft_4']}')
                // singleProduct.push('{$row['eigenschaft_5']}')
                // products.push(singleProduct)
                // </script>";
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
            }
    }