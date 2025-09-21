<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];

    if ($user_otp == $_SESSION['otp']) {
        echo "OTP verified successfully!. you can now login after 5 seconds.";
        sleep(5); // Simulate a delay for demonstration purposes
        header("Location: ./login.html"); // Redirect to registration page
        exit();
        // Proceed with further actions like login or access
    } else {
        echo "Invalid OTP. Please try again.";
        header("Location: ../checkOTP.html"); // Redirect back to OTP page
        exit();
    }
}
?>

