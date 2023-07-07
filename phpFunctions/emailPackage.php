<?php
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;
use \PHPMailer\PHPMailer\SMTP;

require_once dirname(__FILE__, 2) . '/phpFunctions/util.php';
require_once dirname(__FILE__, 2) . '/phpFunctions/sqlInserts.php';
require dirname(__FILE__, 2) . '/phpClasses/Exception.php';
require dirname(__FILE__, 2) . '/phpClasses/PHPMailer.php';
require dirname(__FILE__, 2) . '/phpClasses/SMTP.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function CreateVerificationEmail($email, $conn)
{
    $bool = getVerifiedStatus(conn: $conn, email: $email);
    if ($bool) {
        header("Location: index");
    }
    $token = createVerificationToken();
    updateVerificationCode(updateValue: $token, email: $email, conn: $conn);
    $uid = getUidBasedOnEmail($conn, $email);
    $returns = getAccountInformation($uid, $conn);
    //TODO: HTML fehlt
    $html = "<!DOCTYPE html>
    <html lang='de'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Ihre Test-E-Mail</title>
        <style>
            /* Styles hier einfügen */
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                background-color: #f7f7f7;
                margin: 0;
                padding: 0;
            }
    
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #ffffff;
            }
    
            h1 {
                color: #333333;
            }
    
            p {
                color: #555555;
            }
    
            .button {
                display: inline-block;
                background-color: #007bff;
                color: #ffffff;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }
    
            .footer {
                margin-top: 40px;
                text-align: center;
                color: #999999;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Registrierung Efolgreich</h1>
            <p>Sehr geehrte/r " . $returns['anrede'] . " " . $returns['nachname'] . "</p>
            <p>Dies ist eine Test-E-Mail-Vorlage. Sie können den Text und das Styling nach Ihren Bedürfnissen anpassen und neue Elemente hinzufügen.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec hendrerit velit at felis tincidunt tincidunt. Ut sit amet purus euismod, fringilla tellus non, consequat sapien. Nullam ut suscipit elit, eu tincidunt mauris.</p>
            <a class='button' href='localhost/emailVerify?verificationCode={$token}'>Klicken Sie hier</a>
            <p>Vielen Dank!</p>
            <div class='footer'>
                <p>Fragen? Kontaktieren Sie uns unter support@example.com.</p>
            </div>
        </div>
    </body>
    </html>";
    return $html;
}

function createThanksForOrderEmail($conn,$email)
{
    // TODO: Verified in die Session einbauen.
    $uid = getUidBasedOnEmail($conn, $email);
    $returns = getAccountInformation($uid, $conn);

    $html = "";//TODO: HTML fehlt
    return $html;

}

function startOfEmailProcess($type, $sendTo, $conn)
{
    $sendTo = rawurldecode($sendTo);
    switch ($type) {
        case 'verificationEmail':
            $html = CreateVerificationEmail($sendTo, $conn);//TODO: HTML fehlt
            $emailInfos = array(
                'VonEmail' => 'info@platyweb.de',
                'VonName' => 'Platyweb Service-Team',
                'An' => $sendTo,
                'CC' => null,
                'Betreff' => 'Aktivierung der Registrierung auf Platyweb.de',
                'AntwortenAnEmail' => 'info@platyweb.de',
                'AntwortenAnName' => 'Platyweb Service-Team',
                'Body' => $html

            );
            sendMail($emailInfos);
            break;
        case 'thanksForOrder':
            $html = createThanksForOrderEmail(); //TODO: HTML fehlt
            $emailInfos = array(
                'VonEmail' => 'info@platyweb.de',
                'VonName' => 'Platyweb Service-Team',
                'An' => $sendTo,
                'CC' => null,
                'Betreff' => 'Vielen Dank für Ihre Bestellung',
                'AntwortenAnEmail' => 'info@platyweb.de',
                'AntwortenAnName' => 'Platyweb Service-Team',
                'Body' => $html

            );
            sendMail($emailInfos);
       case 'requestUserInformation':
            $html = createRequestUserInformationEmail(); //TODO: HTML fehlt
            $emailInfos = array(
                'VonEmail' => 'info@platyweb.de',
                'VonName' => 'Platyweb Service-Team',
                'An' => $sendTo,
                'CC' => null,
                'Betreff' => 'Ihre Userdaten',
                'AntwortenAnEmail' => 'info@platyweb.de',
                'AntwortenAnName' => 'Platyweb Service-Team',
                'Body' => $html

            );
    }
}
function sendMail($emailInfos)
{
    try {
        $inipath = (dirname(__FILE__, 2) . "/config/app.ini");
        $ini = parse_ini_file($inipath);
        $host = $ini['smtp_host'];
        $username = $ini['smtp_username'];
        $password = $ini['smtp_password'];
        $port = $ini['smtp_port'];
        $secure = $ini['smtp_secure'];
        // SERVER SETTINGS
        $mail = new PHPMailer(true);
        $mail->CharSet = 'utf-8';
        $mail->Timeout = 30;
        /*SMTP Settings */
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = $host; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = $username; //SMTP username
        $mail->Password = $password; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = $port;
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Debugoutput = 'error_log';
        $mail->SMTPOptions = [
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        ];
        //-------------------------------------------
        // Creates the the Email
        $mail->setFrom($emailInfos['VonEmail'], $emailInfos['VonName']);
        $mail->addAddress($emailInfos['An']);
        $mail->Subject = $emailInfos['Betreff'];
        $mail->addReplyTo($emailInfos['AntwortenAnEmail'], $emailInfos['AntwortenAnName']);
        $mail->isHTML(true);
        $mail->Body = $emailInfos['Body'];


        if (!$mail->send()) {
            echo "mail error: " . $mail->ErrorInfo;
            error_log(date("Y-m-d H:i:s", time()) . "Email Fehler - sendEmail() " . $mail->ErrorInfo . "\n ", 3, "my-error-mail.log");
        }
    } catch (Exception $e) {
        error_log(date("Y-m-d H:i:s", time()) . "Email Fehler - sendEmail() " . $mail->ErrorInfo . "\n ", 3, "my-error-mail.log");
    } catch (\Exception $e) {
        error_log(date("Y-m-d H:i:s", time()) . "Email Fehler - sendEmail() " . $e->getMessage() . "\n ", 3, "my-error-mail.log");
    }
}
?>