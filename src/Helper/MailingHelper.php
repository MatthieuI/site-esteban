<?php

namespace App\Helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

final class MailingHelper
{
    public static function sendMail($name, $address, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();     
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Host       = 'smtp.gmail.com';               // Set the SMTP server to send through
            $mail->SMTPAuth   = true;
            $mail->Username   = '*****@gmail.com';
            $mail->Password   = '*****';                             // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($address, $name);
            $mail->addAddress('matthieuidjellidaine@gmail.com', 'Joe User');
            $mail->addReplyTo($address, $name);

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = '<p>Nouveau message de : '.$name.'</p><p>'.$body.'</p>';
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // public static function handleForm($contactForm)
    // {
    //     # code...
    // }
}
