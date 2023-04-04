<?php
function getAccountInformation($conn, $uid)
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
        $result_set = $stmt_prep->setFetchMode(PDO::FETCH_ASSOC);
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
    try {
        $stmt_prep = $conn->prepare("
        Select
            p.bezeichnung
            pb.image_name
            p.eigenschaft_1
            p.eigenschaft_2
            p.eigenschaft_3
            p.eigenschaft_4
            p.eigenschaft_5
            p.eigenschaft_6
            p.details
            p.menge
            p.akt_preis
            h.bezeichnung as oem_bezeichnung
        FROM 
            produkt p
        JOIN produktbild pb
            on p.p_b_id_ref = pb.p_b_id
        JOIN hersteller h
            on p.oem_id_ref = h.oem_id
        WHERE :produktID = p.p_id
          ");
        $stmt_prep->bindParam(':produktID', $produktID);
        $stmt_prep->execute();
        $result_set = $stmt_prep->setFetchMode(PDO::FETCH_ASSOC);

        // Sollte unter der ProduktID kein Eintrag gefunden werder, wird ein "Error" zurückgegeben um den User auf eine Errorpage umzuleiten. 
        if (!$result_set->rowCount() > 0) {
            $returns = "ERROR";
        } else {
            $row = $result_set->fetch();
            if($row['image_name'] === "" ) {
                $stmt_prep = $conn->query("
                Select
                    pb.image_name
                from 
                    produktbild pb
                where p.p_b_id = 1 
                  ");
                $rowImg = $stmt_prep->fetch();
                $row['image_name'] = $rowImg['image_name'];
                
            }
            $returns = array($row['bezeichnung'], $row['image_name'], $row['eigenschaft_1'], $row['eigenschaft_2'], $row['eigenschaft_3'], $row['eigenschaft_4'], $row['eigenschaft_5'], $row['eigenschaft_6'], $row['details'], $row['menge'], $row['akt_preis'], $row['oem_bezeichnung']);
        }
    }
    catch (PDOException $e) {
        die("ERROR: Could nto able to execute $stmt_prep. " . $e->getMessage());
    }
    return $returns;
}

function getUserAdresse($userID, $conn) {
    try {
    $stmt_prep = $conn->prepare("
    Select 
        land
        plz
        ort
        strasse
        hausnr
        adresszusatz
    FROM 
        user
    WHERE 
        u_id = :userID
    ");
    $stmt_prep->bindParam(':userID', $userID);
    $stmt_prep->execute();
    $result_set = $stmt_prep->setFetchMode(PDO::FETCH_ASSOC);
    $row = $result_set->fetch();
    
    return array($row['land'], $row['plz'], $row['ort'], $row['strasse'], $row['hausnr'], $row['adresszusatz']);
    } catch (PDOException $e) {
        die("ERROR: Could not able to execute $stmt_prep. " . $e->getMessage());
    }
}