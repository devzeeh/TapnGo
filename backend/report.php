<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../backend/db_connect.php';
    include '../backend/get_transactions.php';

    // Require Composer's autoloader
    require '../PHPMailer-6.9.3/src/PHPMailer.php';
    require '../PHPMailer-6.9.3/src/SMTP.php';
    require '../PHPMailer-6.9.3/src/Exception.php';
    //calling the configurationn

    //get report data
    $report = $_POST['reportType'];
    $dateIncident = $_POST['incident'];
    $description = $_POST['description'];


    $name1 = getLoggedInUserName();

    $sq = "SELECT email, name FROM users WHERE name = ?";
    $stmt = $conn->prepare($sq);
    $stmt->bind_param("s", $name1);
    $stmt->execute();
    // result of the query
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();
    $email = $row['email'];
    $name = $row['name'];

    //Set the manila timezone
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
        $mail->SMTPSecure = 'TLS';// Encryption (TLS)
        $mail->Port       = 587;// TCP port to connect to
    
        // Sender and receiver settings
        $mail->setFrom($email, $name);// sender
        $mail->addAddress(Auth_Email, 'Developer Team');// reciever
    
        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'PayMobi Report';
        $mail->Body    ="date of incident: " . $dateIncident ."<br>". $description.
        "<br> This is a system-generated receipt. No signature is required. 
            This is a test email sent from a PHP  using Gmail SMTP. Please Do Not Reply! ". $date;
    
        $mail->send();
        echo 'Message has been sent successfully';
        header("Location: ../main/report.html");
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    $stmt->close();
    $conn->close();
}
?>