<?php
    class getData{
        public function getAccountInformation($conn,$uid){
                try{
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
                    ');

                $stmt_prep->bindParam(':uid',$uid);
                $stmt_prep->execute();
                $result_set = $stmt_prep->setFetchMode(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e){
                die("ERROR: Could not able to execute $stmt_prep. " . $e->getMessage());
            }
            return $result_set
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
        public function login($email,$conn){
            try{
                $stmt_prep = $conn->prepare(
                    'Select
                        u.u_id
                        ,pw.pw
                    from
                        user u
                    join passwort pw
                        on u.pw_id_ref = pw.pw_id
                    where
                        u.email = :email'
                    );
                    $stmt_prep->bindParam(':email',$email);
                    $stmt_prep->execute();

                    $result_set = $stmt_prep->setFetchMode(PDO::FETCH_ASSOC);

                    if(!$result_set->rowCount() > 0){
                        $returns = array(0,0);
                    }else{
                        $row = $result_set->fetch()
                        $returns = array($row['pw'],$row['u_id']);
                    }
                }
                catch(PDOException $e){
                    die("ERROR: Could not able to execute $stmt_prep. " . $e->getMessage());
                }
            return $returns
        }
    ##
    }
?>