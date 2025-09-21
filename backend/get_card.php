<?php
session_start();
// Get User
function getUser() {
    return isset($_SESSION['name']) ? $_SESSION['name'] : null;
}
// get the card details of the logged in user
function CardDetails() {
    include '../backend/db_connect.php';
    //  include '../backend/get_transactions.php';
    $name = getUser();

    $sql = "SELECT card_number FROM cards WHERE users = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['card_number'];
    } else {
        return false;
    }   
    return $email;
    $conn->close();
    $stmt->close();
}

// get card status
function CardStatus() {
    include '../backend/db_connect.php';
    $cardStatus = CardDetails();

    $sql = "SELECT card_status FROM cards WHERE card_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cardStatus);
    $stmt->execute();
    $result = $stmt->get_result();

    $card = $result->fetch_assoc();
    $cardStatus = $card['card_status'];

    return ucfirst($cardStatus);
    $conn->close();
    $stmt->close();
}

//get email
function getUserEmail() {
    include '../backend/db_connect.php';
    $name = getUser();
    
    $sql = "SELECT email FROM users WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    $email = $result->fetch_assoc();
    $userEmail = $email['email'];

    return $userEmail;
    $conn->close();
    $stmt->close();
}
?>