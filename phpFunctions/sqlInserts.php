<?php

function setZahlungsmittel($uid, $infoArray, $conn)
{
    $lastRowID = 0;
    try {
        $stmt_prep_select = $conn->prepare(
            'select
                z_id_ref
            from
                user
            where
                u_id = :u_id'
        );
        $stmt_prep_select->bindParam(':u_id', $uid);
        $stmt_prep_select->execute();
        $row = $stmt_prep_select->fetch();
        if (empty($row['z_id_ref'])) {
            # Zahlungsmethoden Statement
            $stmt_prep_insert_zi = $conn->prepare(
                'insert into zahlungsinformationen
                    (
                        banknamen,
                        land,
                        bic,
                        iban
                    )
                values (
                        :banknamen,
                        :land,
                        :bic,
                        :iban
                );'
            );
            $stmt_prep_insert_zi->bindParam(':iban', $infoArray[0]);
            $stmt_prep_insert_zi->bindParam(':land', $infoArray[1]);
            $stmt_prep_insert_zi->bindParam(':bic', $infoArray[2]);
            $stmt_prep_insert_zi->bindParam(':banknamen', $infoArray[3]);
            $stmt_prep_insert_zi->execute();
            $lastRowID = $conn->lastInsertId();
            # ZahlungsmethodexUser Statement
            $stmt_prep_insert_zxu = $conn->prepare(
                'insert into zahlungsmethodexuser
                    (
                        u_id_ref
                        ,zi_id_ref
                    )
                values (
                        :u_id,
                        :lasRowId
                );'
            );
            $stmt_prep_insert_zxu->bindParam(':u_id', $uid);
            $stmt_prep_insert_zxu->bindParam(':lasRowId', $lastRowID);
            $stmt_prep_insert_zxu->execute();
            $lastRowID = $conn->lastInsertId();
            # Update User
            $stmt_prep_insert_update_user = $conn->prepare(
                'update user
                set
                    z_id_ref = :lastRowId
                where
                    u_id = :u_id
                ;'
            );
            $stmt_prep_insert_update_user->bindParam(':lastRowId', $lastRowID);
            $stmt_prep_insert_update_user->bindParam(':u_id', $uid);
            $stmt_prep_insert_update_user->execute();
            return 1;
        } else {
            $stmt_prep_update = $conn->prepare(
                'update
                    zahlungsinformationen as zi
                inner join zahlungsmethodexuser as zxu
                on zi.zi_id = zxu.zi_id_ref
                set
                    zi.banknamen =  :banknamen,
                    zi.land = :land,
                    zi.bic = :bic,
                    zi.iban = :iban
                where
                    zxu.z_id = :z_id
                    ;'
            );
            $stmt_prep_update->bindParam(':z_id', $row['z_id_ref']);
            $stmt_prep_update->bindParam(':iban', $infoArray[0]);
            $stmt_prep_update->bindParam(':land', $infoArray[1]);
            $stmt_prep_update->bindParam(':bic', $infoArray[2]);
            $stmt_prep_update->bindParam(':banknamen', $infoArray[3]);
            $stmt_prep_update->execute();
            return 2;
        }
    } catch (PDOException $e) {
        error_log(date("Y-m-d H:i:s", time()) . " Setzten der Zahlungsbedingung ist gescheitert - setZahlungsmittel() - sqlInserts.php \n"
            . "SQL Fehler: \n " . $e . "\n", 3, "my-errors-phpFuctions.log");
        return 3;
    }
}

function updateUserEntry($uid, $target, $value, $conn)
{
    try {
        $stmt_prep = $conn->prepare("
    UPDATE
        `user`
    SET $target = :value
    WHERE u_id = :uid;
    ");
        $stmt_prep->bindParam(':value', $value);
        $stmt_prep->bindParam(':uid', $uid);
        $stmt_prep->execute();
        echo "$target erfolgreich gespeichert";
    } catch (PDOException $e) {
        error_log(date("Y-m-d H:i:s", time()) . " Updaten von Wert $target gescheitert - updateUserEntry() - sqlInserts.php \n
        SQL Fehler: \n $e \n", 3, "my-errors-phpFuctions.log");
    }
}

function updateUserPassword($uid, $password, $conn)
{
    try {
        $stmt_prep = $conn->prepare("
        UPDATE
            passwort
        LEFT JOIN user
            on passwort.pw_id = user.pw_id_ref
        SET
            pw = :newPassword
        WHERE
            passwort.pw_id = :uid
        ");
        $stmt_prep->bindValue(':newPassword', $password);
        $stmt_prep->bindValue(':uid', $uid);
        $stmt_prep->execute();
        return true;
    } catch (PDOException $e) {
        error_log(date("Y-m-d H:i:s", time()) . " Updaten von Passwort gescheitert - updateUserPassword() - sqlInserts.php \n
        SQL Fehler: \n $e \n", 3, "my-errors-phpFuctions.log");
        return false;
    }
}

function insertOrder($conn,$bestArray): int {
    try {
        $stmt_prep = $conn->prepare(
            '
            Insert into bestellung (u_id_ref, gesamtkosten, zi_id_ref, bestell_datum, anzahl_bestellpos, lieferdatum)
            values (:uid, :gesKosten , :zi_id, :bestell_datum, :anzahl_bestellpos, :lieferdatum)
            ;'
        );
        $stmt_prep->bindValue(':uid', intval($bestArray['uid']));
        $stmt_prep->bindValue(':gesKosten', floatval($bestArray['gesamtkosten']));
        $stmt_prep->bindValue(':zi_id', intval($bestArray['zi_id']));
        $stmt_prep->bindValue(':anzahl_bestellpos', intval($bestArray['anzahlBestellpositionen']));
        $stmt_prep->bindValue(':bestell_datum', $bestArray['bestellDatum']);
        $stmt_prep->bindValue(':lieferdatum', $bestArray['lieferdatum']);
        $stmt_prep->execute();
        $lastRowID = $conn->lastInsertId();
        return $lastRowID;
    } catch (PDOException $e) {
        error_log(date("Y-m-d H:i:s", time()) . " Anlegen der Bestellung ist gescheitert - insertOrder() - sqlInserts.php \n
        SQL Fehler: \n $e \n", 3, "my-errors-phpFuctions.log");
        return -1;
    };
}

function insertOrderPos($conn, $bestArray, $bestellId): bool {

    try{
        $counter = 1;
        foreach ($bestArray['bestellPositionen'] as $bestellposArray) {
            $stmt_prep = $conn->prepare(
                '
                Insert into bestellposition (b_id_ref, p_id_ref, pos, menge, akt_preis)
                values (:b_id_ref, :p_id_ref , :pos, :menge, :akt_preis)
                ;'
            );
            $stmt_prep->bindValue(':b_id_ref', $bestellId);
            $stmt_prep->bindValue(':p_id_ref', $bestellposArray['produkt']);
            $stmt_prep->bindValue(':pos', $counter);
            $stmt_prep->bindValue(':menge', $bestellposArray['menge']);
            $stmt_prep->bindValue(':akt_preis', $bestellposArray['akt_preis']);
            $stmt_prep->execute();
            $counter += 1;
        }
        return true;
    }catch (PDOException $e) {
        error_log(date("Y-m-d H:i:s", time()) . " Anlegen der Bestellungspositionen ist gescheitert - insertOrderPos() - sqlInserts.php \n
        SQL Fehler: \n $e \n", 3, "my-errors-phpFuctions.log");
        return false;
    };
}

function updateProduktMenge($conn,$bestArray): bool {
    try{
        foreach ($bestArray['bestellPositionen'] as $bestellposArray) {
            $stmt_prep = $conn->prepare(
                '
                    update produkt
                    set
                        menge = :menge
                    where
                        p_id = :pid
                ;');
                $stmt_prep->bindValue(':menge', intval($bestellposArray['lagermenge']) - intval($bestellposArray['menge']));
                $stmt_prep->bindValue(':pid', $bestellposArray['produkt']);
                $stmt_prep->execute();
        }
        return true;
    }catch (PDOException $e) {
        error_log(date("Y-m-d H:i:s", time()) . " Updaten der Warenbestände ist gescheitert - updateWarehouseData() - sqlInserts.php \n
        SQL Fehler: \n $e \n", 3, "my-errors-phpFuctions.log");
        return false;
    };
}

?>
function registerUser($conn, $email, $passwort, $titel, $vorname,
$nachname, $anrede, $bday, $land, $plz, $ort, $strasse, $hausnr, $adresszusatz)
{
    $checkMailStmt = "SELECT u_id FROM user WHERE email = :mail";
    $preparedMailCheck = $conn->prepare($checkMailStmt);
    $preparedMailCheck->bindParam(':mail', $email);
    $preparedMailCheck->execute();
    if ($preparedMailCheck->rowCount() > 0) {
        return "<script type='text/javascript'>alert('E-Mail ist bereits vorhanden')</script>";
    } else {
        $hashpw = password_hash($passwort, PASSWORD_DEFAULT);
        $SQL = "INSERT INTO passwort(pw) VALUES(:hashpw)";
        $stmt = $conn->prepare($SQL);
        $stmt->bindParam(':hashpw', $hashpw);
        echo ($stmt->queryString);
        $stmt->execute();
        $preparedid = $conn->lastInsertId();
        $insertuser = "INSERT INTO user(titel,vorname,nachname,anrede,pw_id_ref,email,geburtsdatum,land,plz,ort,strasse,hausnr,adresszusatz)
            VALUES(:titel, :vorname, :nachname,:anrede,:pwref,:email,:bday,:land,:plz,:ort,:strasse,:hausnr,:adresszusatz)";
        $preparedinsert = $conn->prepare($insertuser);
        $preparedinsert->bindParam(':titel', $titel);
        $preparedinsert->bindParam(':vorname', $vorname);
        $preparedinsert->bindParam(':nachname', $nachname);
        $preparedinsert->bindParam(':anrede', $anrede);
        $preparedinsert->bindParam(':pwref', $preparedid);
        $preparedinsert->bindParam(':email', $email);
        $preparedinsert->bindParam(':bday', $bday);
        $preparedinsert->bindParam(':land', $land);
        $preparedinsert->bindParam(':plz', $plz);
        $preparedinsert->bindParam(':ort', $ort);
        $preparedinsert->bindParam(':strasse', $strasse);
        $preparedinsert->bindParam(':hausnr', $hausnr);
        $preparedinsert->bindParam(':adresszusatz', $adresszusatz);
        $resultprepuser = $preparedinsert->execute();
        if ($resultprepuser) {
            echo "<p style='text-align: center; color: ForestGreen'>Erfolgreich abgespeichert</p>";
            header("Location: login.php");
        } else {
            return "<p style='text-align: center; color: red'>Es sind Fehler entstanden</p>";
        }
    }
}
