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
    $baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://$_SERVER[HTTP_HOST]";
    $bool = getVerifiedStatus(conn: $conn, email: $email);
    if ($bool) {
        header("Location: index");
    }
    $token = createVerificationToken();
    updateVerificationCode(updateValue: $token, email: $email, conn: $conn);
    $uid = getUidBasedOnEmail($conn, $email);
    $returns = getAccountInformation($uid, $conn);
    $html = "<!DOCTYPE html>
    <html lang='de'>
      <head>
        <meta charset='UTF-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <title>Platyweb Verifizierung</title>
        <style>
          /* Styles hier einfügen */
          body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #fff;
            margin: 0;
            padding: 0;
          }
    
          .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fefdfb;
          }
    
          h1 {
            color: #333333;
          }
    
          p {
            color: #610e61;
          }
    
          button {
            display: inline-block;
            background-color: #960099;
            color: #ffffff;
            padding: 5px 10px;
            height: 40px;
            width: 200px;
            border-radius: 5px;
            margin-top: 20px;
            transition: .2s ease-in-out;
            font-weight: bold;
            cursor: pointer;
          }
          button:hover {
            background-color: #fcf4f4;
            border: 1px solid black;
            color: black;
    
          }
          .footer {
            margin-top: 40px;
            text-align: center;
          }
          img {
            float: right;
            margin: -20px;
            transition: .2s ease-in-out;
          }
          img:hover {
            scale: 1.1;
          }
        </style>
      </head>
      <body>
        <div class='container'>
          <h1 style='display: inline-block;'>Registrierung Efolgreich</h1>
          <a href='https://platyweb.de'><img
              src='http://localhost/img/platyweb.svg'
              alt='Logo'
              width='150px'
            /></a>
          <p>
            Sehr geehrte/r " . $returns['anrede'] . " " . $returns['nachname'] . "
          </p>
          <p>
            Vielen Dank das du dich bei Platyweb angemeldet hast. Um deine E-Mail zu bestätigen, klicke auf den Button.
          </p>
          <p>
            Mit freundlichen Grüßen, <br> Ihr Platyweb Team
          </p>
          <a href='$baseURL/emailVerify?verificationCode={$token}'><button>Klicke zum Verifizieren</button></a
          >
          <p>Vielen Dank!</p>
          <div class='footer'>
            <p>Fragen? Kontaktieren Sie uns unter support@platyweb.com</p>
          </div>
        </div>
      </body>
    </html>
    ";
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