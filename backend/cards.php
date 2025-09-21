<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create database connection
    include_once '../backend/db_connect.php';

    // Get form data and sanitize
    $cardUID = $_POST['cardUID'];
    // Generate a unique transaction 
    $cardID = date('y') . rand(1000, 9999) .date('m'). rand(1000, 9999);
    /*$date = date('ym');
    $rand = rand(1000,9999);
    $rand1 = rand(1000,9999);
    $time = date('s');
    $txn = 'LD'. $date. $rand.$rand1.$time;*/
    // Card satus
    $cardStatus = 'unregistered';
    // Set timezone to Manila
    $cardIssueDate = date('Y-m-d');
    //$cardExpiredDate = date('Y-m-d', strtotime($cardIssueDate . ' +5 years'));

    if ($cardUID === '' || $cardUID === null) {
        echo 'Card UID cannot be empty';
        //header("Location: ../Admin/admin.html");
        exit();
    }

    // Check if card_uid already exists
    $check_sql = "SELECT card_uid FROM cards WHERE card_uid = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $cardUID);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Error: Card UID already exists";
    } else {
        $sql = "INSERT into cards (card_uid, card_number, card_status, issue_date) values (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $cardUID, $cardID, $cardStatus, $cardIssueDate);
        
        if ($stmt->execute()) {
            //echo "Card inserted successfully";
            header("Location: ../Admin/admin.html");
        } else {
            echo "Error executing statement: {$stmt->error}";
        }
    }
    $check_stmt->close();
    $conn->close();
    //echo $cardUID. $cardID . $cardIssueDate. $cardStatus ;
}

// get total cards
function totalCards() {
    include '../backend/db_connect.php';

    $sql = "SELECT COUNT(card_uid) as total FROM cards";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

//
function activeCards() {
    include '../backend/db_connect.php';

    $sql = "SELECT COUNT(card_status) as total FROM cards WHERE card_status = 'ACTIVE'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function expiredCards() {
    include '../backend/db_connect.php';

    $sql = "SELECT COUNT(card_status) as total FROM cards WHERE card_status = 'EXPIRED'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}
?>