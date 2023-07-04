<?php

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
                ,u.land as userland
                ,u.plz
                ,u.ort
                ,u.strasse
                ,u.hausnr
                ,u.adresszusatz
                ,zi.banknamen
                ,zi.land as zahlland
                ,zi.bic
                ,zi.bezeichnung
                ,zi.iban
            from user u
            left join zahlungsmethodexuser zxu
                on u.u_id = zxu.u_id_ref
            left join zahlungsinformationen zi
                on zxu.zi_id_ref = zi.zi_id
            where u.u_id = :uid ;
            '
        );

        $stmt_prep->bindParam(':uid', $uid);
        $stmt_prep->execute();
        if ($stmt_prep->rowCount() > 0) {
            $row = $stmt_prep->fetch();
            return $row;
        }
    } catch (PDOException $e) {
        error_log(date("Y-m-d H:i:s", time()) . "Datenbezug failed - getAccountInformation \n", 3, "my-errors.log");
        die("ERROR: Could not able to execute $stmt_prep. " . $e->getMessage());
    }
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
function getProduktInfos($produktID, $conn)
{
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
        try {
            if (!$stmt_prep->rowCount() > 0) {
                $returns = "ERROR";
            } else {
                $row = $stmt_prep->fetch();
                $returns = array($row['bezeichnung'], $row['eigenschaft_1'], $row['eigenschaft_2'], $row['eigenschaft_3'], $row['eigenschaft_4'], $row['eigenschaft_5'], $row['eigenschaft_6'], $row['details'], $row['menge'], $row['akt_preis'], $row['oem_bezeichnung']);
            }
        } catch (Exception $e) {
            echo ("ERROR:" . $e->getMessage());
        }
    } catch (PDOException $e) {
        die("ERROR: Could nto able to execute $stmt_prep. " . $e->getMessage());
    }
    return $returns;
}


function getBestseller($conn)
{
    $i = 0;
    $bestseller = array();
    try {
        $stmt_prep = $conn->prepare("
        SELECT p_id_ref, Sum(menge)
FROM `bestellposition`
Group BY p_id_ref
ORDER BY Sum(menge) DESC
LIMIT 6
");

        $stmt_prep->execute();
        while ($i < 6 && $row = $stmt_prep->fetch()) {
            array_push(
                $bestseller,
                $row["p_id_ref"]
            );
        }

        return $bestseller;
    } catch (PDOException $e) {
        die("ERROR: Could not able to execute $stmt_prep. " . $e->getMessage());
    }
}


/**
 * Summary of getUserAdresse
 * @param mixed $userID
 * @param mixed $conn
 * @return array
 */
function getUserAdresse($userID, $conn)
{
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



function getCode($conn, $code)
{
    $stmt_prep = $conn->prepare(
        '
			Select
				valid,
				insertDate,
                value
			from
				codes
			where
				code = :code;
			'
    );
    $stmt_prep->bindParam(':code', $code);
    $stmt_prep->execute();
    $row = $stmt_prep->fetch(PDO::FETCH_ASSOC);
    $rowCount = $stmt_prep->rowCount();
    if ($rowCount > 0) {
        return $row;
    } else {
        $rowErsatz = array('valid' => false);
        return $rowErsatz;
    }
}
/**
 * Summary of getProductImageData
 * @param mixed $produktNr
 * @param mixed $conn
 * @return mixed
 */
function getProductImageData($produktNr, $conn)
{
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
        if ($rowCount > 0) {
            try { // Statement for receiving Image Data
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
            } catch (PDOException $e) {
                die("ERROR: Could not able to execute $stmt_prep_produkt. " . $e->getMessage());
            }
            if ($rowCount > 0) {
                return $binaryImage = $row['image'];
            } else {
                return getDefaultImage($conn);
            }
        } else {
            return getDefaultImage($conn);
        }
    } catch (PDOException $e) {
        die("ERROR: Could not able to execute $stmt_prep_produkt. " . $e->getMessage());
    }
}

function getPrice($conn, $produkt_id)
{
    $stmt_prep = $conn->prepare(
        '
            select
                akt_preis
            from
                produkt
            where
                p_id = :id
            ;'
    );
    $stmt_prep->bindParam(':id', $produkt_id);
    $stmt_prep->execute();
    $row = $stmt_prep->fetch();
    return $row['akt_preis'];
}

function getDefaultImage($conn)
{
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

function getZahlungsmittel($conn,$uid) : array {
    $stmt_prep = $conn->prepare(
        'select
            zi_id
            ,zi.banknamen
            ,zi.land
            ,zi.bic
            ,zi.bezeichnung
            ,zi.iban
        from
            zahlungsinformationen as zi
        join zahlungsmethodexuser as zix
            on zix.zi_id_ref = zi.zi_id
        where
            zix.u_id_ref = :uid
        ;');
        $stmt_prep->bindParam(':uid', $uid);
        $stmt_prep->execute();
        if ($stmt_prep->rowCount() > 0) {
            $row = $stmt_prep->fetch(PDO::FETCH_ASSOC);
            return $row;
        }else{
            return array('error'); # #INFO: Kann zu überprüfen genutzt werde (Also ob ZI hinterlegt wurde oder nicht)
        }
	}

function getOrderHistory($conn,$u_id,$timespan = null){
    try {
        $i=0;
        $stmt_prep_select = $conn->prepare(
            "
            select
                b.b_id
                ,b.gesamtkosten
                ,b.bestell_datum
                ,b.anzahl_bestellpos
                ,b.lieferdatum
                ,b.geliefert
            from
                bestellung b
            where
                b.u_id_ref = :uid;
            "); //TODO: Datums anpassung, wenn Filter umgsetzt
        $stmt_prep_select->bindParam(':uid', $u_id);
        $stmt_prep_select->execute();
        if ($stmt_prep_select->rowCount() > 0) {
            $results = $stmt_prep_select->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as &$array){
                $bestellpositionen = getBestellposition($conn ,$array['b_id']);
                foreach($bestellpositionen as &$bestPosArray){
                    $produktArray = getProduktInfos($bestPosArray['p_id_ref'],$conn);
                    $bestPosArray['bezeichnung'] = $produktArray[0];
                    $bestPosArray['details'] = $produktArray[7];
                }
                $array['Bestellpositionen']= $bestellpositionen;
                $i++;
            }
            return $results;
        }
        } catch (PDOException $e) {
            die("ERROR: Could not able to execute $stmt_prep_select. " . $e->getMessage());
        }
}

function getBestellposition($conn,$b_id){
    try {
        $stmt_prep_select = $conn->prepare("
        select
            p_id_ref
            ,pos
            ,menge
            ,akt_preis
        from
            bestellposition
        where
            b_id_ref = :bid;");
        $stmt_prep_select->bindParam(':bid',$b_id);
        $stmt_prep_select->execute();
        if ($stmt_prep_select->rowCount() > 0) {
            $results = $stmt_prep_select->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
    } catch (PDOException $e) {
        die("ERROR: Could not able to execute $stmt_prep_select. " . $e->getMessage());
    }
}

function getVerifiedStatus($conn,$uid) {
    try {
        $stmt_prep_select = $conn->prepare("
            select
                verified
            from
                user
            where
            u_id = :uid;
        ");
        $stmt_prep_select->bindValue(":uid",$uid);
        $stmt_prep_select->execute();
        $row = $stmt_prep_select->fetch();
        if($row['verified'] == 1) {
            return true;
        }else {
            return false;
        }
    } catch (\Throwable $th) {
        error_log(date("Y-m-d H:i:s", time()) . "getVerifiedStatus - getVerifiedStatus \n", 3, "my-errors.log");
        return false;
    }
}

?>
