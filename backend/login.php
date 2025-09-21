<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create database connection
    include_once '../backend/db_connect.php';

    // Get user input
    $name = $conn->real_escape_string($_POST['name']);
    $password = $_POST['password']; // Don't escape password before verification

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT name, password FROM users WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify the password against stored hash
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['name'] = $user['name'];

            // Redirect to dashboard
            header("Location: ../main/index.html");
            exit();
        } 
        else {
            $_SESSION['error'] = "User not found";
            header("Location: ../login.html?error=invalid_password");
            exit();
        }
    } else {
        $_SESSION['error2'] = "Invalid password";
        header("Location: ../login.html?error2=invalid");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>