<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

//Load Composer's autoloader
require 'vendor/autoload.php';

$mail = new PHPMailer();

// Settings
$mail->IsSMTP();
$mail->CharSet = 'UTF-8';

$mail->Host       = "";    // SMTP server example
$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Port       = 2525;                    // set the SMTP port for the GMAIL server
$mail->Username   = "";            // SMTP account username example
$mail->Password   = "";            // SMTP account password example

// Content
$mail->setFrom('no-reply@omegapass.de', 'OmegaPass');
$mail->addAddress('reciever');

$mail->isHTML(true);                       // Set email format to HTML
$mail->Subject = 'Here is the subject';
# set the body of the message as the content of email-inlined.html
$mail->Body    = file_get_contents('mail-templates/email-inlined.html');
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$mail->send();