<?php
//require '../backend/getUserEmail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Correct the paths to PHPMailer files
require '../PHPMailer-6.10.0/src/PHPMailer.php';
require '../PHPMailer-6.10.0/src/SMTP.php';
require '../PHPMailer-6.10.0/src/Exception.php';

// Fix the config.php path
require '../config.php';
require_once '../backend/get_transactions.php';;

$email = customerEmail();
$name = getLoggedInUserName();
$Sender = Auth_Email;
//Set the manila timezone
//$timezone = new DateTime('now', new DateTimeZone('Asia/Manila'));
$transactionDate = date('M-d-Y');

$mail = new PHPMailer(true);

try {
    // SMTP server configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = Auth_Email;
    $mail->Password = Auth_Password;
    $mail->SMTPSecure = 'TLS';// Encryption (TLS)
    $mail->Port = 587;// TCP port to connect to

    // Sender and receiver settings
    $mail->setFrom(Auth_Email, 'Developer Team');// sender
    $mail->addAddress($email);// reciever

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Fare E-reciept for ' . $transactionDate;
    $mail->Body = sendFareEmail($start, $end, $date, $discountAmount,$discount, $result, $transactionID, $customer, $earnPoints);

    $mail->send();
    echo 'Message has been sent successfully';
} catch (Exception $e) {
    error_log("Mail configuration error: " . $e->getMessage());
}
?>
