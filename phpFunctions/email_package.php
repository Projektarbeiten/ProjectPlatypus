<?php
session_start();
/* Namespace alias. */
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

/* If you installed PHPMailer without Composer do this instead: */

require dirname(__FILE__, 2) . '\phpClasses\Exception.php';
require dirname(__FILE__, 2) . '\phpClasses\PHPMailer.php';
require dirname(__FILE__, 2) . '\phpClasses\SMTP.php';

function sendMail($processedHTML)
{

    $mail = new PHPMailer(true);

    try {
        $mail->setFrom('info@platyweb.de', 'Platyweb Service-Team');

        /* Send to */
        $mail->addAddress(''); // TODO: Dynamisch gestalten später

        $mail->Subject ='Test Message';
        $mail->Body =$processedHTML;

        /*SMTP Settings */
        $inipath = (dirname(__FILE__, 2) . "/config/app.ini");
        $ini = parse_ini_file($inipath);
        $host = $ini['smtp_host'];
        $username = $ini['smtp_username'];
        $password = $ini['smtp_password'];
        $port = $ini['smtp_port'];
        $secure = $ini['smtp_secure'];

        /* Tells PHPMailer to use SMTP. */
        $mail->isSMTP();

        /* SMTP server address. */
        $mail->Host = $host;
        /* Use SMTP authentication. */
        $mail->SMTPAuth = TRUE;

        /* Set the encryption system. */
        $mail->SMTPSecure = $secure;

        /* SMTP authentication username. */
        $mail->Username = $username;

        /* SMTP authentication password. */
        $mail->Password = $password;

        /* Set the SMTP port. */
        $mail->Port = $port;

        $mail->send();
    } catch (\Throwable $th) {
        //throw $th;
    }
}
?>