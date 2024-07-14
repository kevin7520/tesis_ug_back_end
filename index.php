<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
//require 'vendor/autoload.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
   //Server settings
   $mail->SMTPDebug = 0;                      //Enable verbose debug output
   $mail->isSMTP();                                            //Send using SMTP
   $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
   $mail->SMTPAuth = true;                                   //Enable SMTP authentication
   $mail->Username = 'serious.game.ug@gmail.com';                     //SMTP username
   $mail->Password = 'dkvakqmywddzgjnb';                               //SMTP password
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
   $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

   //Recipients
   $mail->setFrom('serious.game.ug@gmail.com', 'ADMINISTRADOR');
   $mail->addAddress('kaas7520@gmail.com');     //Add a recipient
   //Content
   $mail->isHTML(true);                                  //Set email format to HTML
   $mail->Subject = 'Restablecer contraseña';
   $mail->Body = 'This is the HTML message body <b>in bold!</b>';
   $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

   $mail->send();
   echo 'Message has been sent';
} catch (Exception $e) {
   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>