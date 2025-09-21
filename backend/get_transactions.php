<?php
session_start();
// Get User
function getLoggedInUserName() {
    return isset($_SESSION['name']) ? $_SESSION['name'] : null;
}

function getCardNumber() {
    include '../backend/db_connect.php';
    $name = getLoggedInUserName();

    $sql = "SELECT card_number FROM cards WHERE users = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['card_number'];
    } else {
        return null;
    }
    $stmt->close();
    $conn->close();
}

// get_transactions.php limit 4
function displayTransactions($limit = null) {
    include '../backend/db_connect.php';
    $name = getCardNumber();

    $sql = "SELECT transaction_id, description, transaction_type, amount, discount, transaction_date FROM transactions WHERE card= ? ORDER BY id DESC";
    if ($limit) {
        $sql .= " LIMIT ?";
    }

    $stmt = $conn->prepare($sql);
    if ($limit) {
        $stmt->bind_param("si", $name, $limit);
    } else {
        $stmt->bind_param("s", $name);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table>";
    echo "<tr> 
            <th>Transaction ID</th>
            <th>Description</th>
            <th>Transaction Type</th>
            <th>Discount</th>
            <th>Amount</th>
            <th>Date</th>
           </tr>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['transaction_id']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['transaction_type']}</td>
                    <td>{$row['discount']}</td>
                    <td>$" . number_format($row['amount'], 2) . "</td>
                    <td>{$row['transaction_date']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='text-align: center;'><h2>You have no transaction at the moment</h2></td></tr>";
    }

    echo "</table>";
    $stmt->close();
    $conn->close();
}


// Total amount
function GetTotalAMount(){
    include '../backend/db_connect.php';

    $name1 = getLoggedInUserName();

    $sql = "SELECT SUM(card_amount) as amount FROM cards where users = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name1);
    $stmt->execute();
    $result = $stmt->get_result();

    $total = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['amount'];
    }

    return $total;
    $conn->close();
    $stmt->close();
}

// get total spend
function totalSpend(){
    include '../backend/db_connect.php';

    $name1 = getCardNumber();

    $sql = "SELECT SUM(amount) as amount FROM transactions where card = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name1);
    $stmt->execute();
    $result = $stmt->get_result();

    $total = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['amount'];
    }

    return $total;
    $conn->close();
    $stmt->close();
}

// for total earned points
function UserEarned(){
    include '../backend/db_connect.php';

    $name1 = getCardNumber();

    $sql = "SELECT SUM(earn_points) as earn_points FROM transactions WHERE card = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name1);
    $stmt->execute();
    $result = $stmt->get_result();

    $total = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['earn_points'];
    }

    return $total;
    $conn->close();
    $stmt->close();
}


// for card validity
function CardValidity(){
    include '../backend/db_connect.php';

    $name1 = getLoggedInUserName();

    $sql = "SELECT expired_date FROM cards WHERE users = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name1);
    $stmt->execute();
    $result = $stmt->get_result();

    $validity = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $validity = $row['expired_date'];
    }
    
    return $validity;
    $conn->close();
    $stmt->close();
}

// Opt to place to other
function customerEmail(){
include '../backend/db_connect.php';
    $name = getCardNumber();

    $sql = "SELECT email from users where card_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
    } else {
        $email = null; // or handle the case where no email is found
    }

    return $email;
    $conn->close();
    $stmt->close();
}
?>
