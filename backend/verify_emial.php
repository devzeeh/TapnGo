<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../backend/db_connect.php';

    // Get form data and sanitize
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        //echo "Email already registered. ";
        echo "email accepted. ";
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt2 = $conn->prepare("UPDATE users set password = ? where email = ?");
        $stmt2->bind_param('ss', $hashedPassword, $email);
        $stmt2->execute();
        echo "<br> update";
        //if email is verify
        //$_SESSION['error2'] = "User found";
        //header("Location: ../fotgetPassword.html?error2=Reset_Password");
        //echo "<a href='../register.html'>back to registration</a>";
        exit;
    } else {
        echo "email not found";
        //$_SESSION['error'] = "User not found";
        header("Location: ../forgetPassword.html?error=ResetPassword");
        exit;
    }
    $conn->close();
    $stmt->close();
}


/*function resetPassword() {
    include '../backend/db_connect.php';



}*/
?>