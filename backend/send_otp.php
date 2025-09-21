<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Generate OTP
    $otp = rand(100000, 999999);  // Random 6-digit OTP

    // Store OTP in session (or database in production)
    session_start();
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    // Include PHPMailer files
    require '../PHPMailer-6.9.3/src/PHPMailer.php';
    require '../PHPMailer-6.9.3/src/SMTP.php';
    require '../PHPMailer-6.9.3/src/Exception.php';

    // Configuration file for Auth_Email and Auth_Password
    require '../config.php';

    // Set timezone to Manila
    $timezone = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $date = date('M d Y ') . $timezone->format('h:i:sa');

    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = Auth_Email;
        $mail->Password   = Auth_Password;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender and receiver
        $mail->setFrom(Auth_Email, 'Developer Team');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your One-Time Password (OTP)';
        $mail->Body    = "
            <p>Hello,</p>
            <p>Your OTP is: <strong>$otp</strong></p>
            <p>This is a system-generated email. No signature is required.</p>
            <p><small>Sent on: $date</small></p>
        ";

        $mail->send();
        // Redirect to OTP verification page
        header("Location: ../checkOTP.html");
        exit;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
