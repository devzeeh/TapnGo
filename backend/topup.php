<?php
//Get user
include '../backend/get_transactions.php';

/*$TXN = "SMPL" . rand(10000, 1000);
 echo ($TXN);
 echo "<br>";
 $randomNumber = rand(1000000000, 9999999999);
echo $randomNumber;
echo "<br>";
 $randomNumber = mt_rand(1, 10);
echo $randomNumber;
echo "<br>";
$randomNumber = random_int(100000, 999999);
echo $randomNumber;
echo"<br>";
//(year)25(month)12(day)31random 111111
//250424
$date = date('ym');
$rand = rand(1000,9999);
$rand1 = rand(1000,9999);
$time = date('s');
echo 'klk '. $date . "-" . $rand . "-" . $rand1;*/

//Get the transaction id, cant be call outside
function GetTransactionID(){
    include '../backend/db_connect.php';
    // Get user input
    $name1 = getLoggedInUserName();

    // Generate a unique transaction ID
    $date = date('ym');
    $rand = rand(1000,9999);
    $rand1 = rand(1000,9999);
    $time = date('s');
    $txn = 'LD'. $date. $rand.$rand1.$time;

    // Check if the transaction ID already exists in the database
    $sql = "SELECT * FROM transactions WHERE transaction_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $txn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the transaction ID already exists, generate a new one
        return GetTransactionID();
    } else {
        // if it doesn't exist, insert it into the database
        // Return the unique transaction ID without inserting it
        return $txn; 
    }

    $stmt->close();
    $conn->close();
}

//insert transaction
function InsertTransction() {
    include '../backend/db_connect.php';
    // Get user input
    $name1 = getLoggedInUserName();

    // Set timezone and date
    $timezone = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $date = date('y M d') . $timezone->format('h:i:sa');

    // Set variables
    $user = $name1;
    $amount = $_POST['amount'];
    $transaction = GetTransactionID();
    $description = 'Card Top Up';
    $earn_points = '';
    $transaction_date = $date;

    // Prepare and bind
    $sql = "INSERT INTO transactions (users, transaction_id, description, amount, earn_points, transaction_date) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiss", $user, $transaction, $description, $amount, $earn_points, $transaction_date);

    // Execute once
    if ($stmt->execute()) {
        echo "Transaction added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close everything
    $stmt->close();
    $conn->close();
}
?>

