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
                ,zi.bankname
                ,zi.land
                ,zi.bic
                ,zi.bezeichnung
                ,zi.iban
            from user u
            join zahlungsmethodenxuser zxu
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

        $result_set = $stmt_prep->setFetchMode(PDO::FETCH_ASSOC);

        if (!$result_set->rowCount() > 0) {
            $returns = array(0, 0);
        } else {
            $row = $result_set->fetch();
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
    try {	// Todo: Image_name kann entfernt werden, da Image Name nicht benötigt wird und somit Obsolete ist!
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
        $row = $stmt_prep_produkt->fetch(PDO::FETCH_ASSOC);
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
						$row = $stmt_prep_image->fetch(PDO::FETCH_ASSOC);
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