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
        error_log(date("Y-m-d H:i:s", time()) . "Setzten der Zahlungsbedingung ist gescheitert - setZahlungsmittel() - sqlInserts.php \n"
            . "SQL Fehler: \n " . $e . "\n", 3, "my-errors-phpFuctions.log");
        return 3;
    }
}
