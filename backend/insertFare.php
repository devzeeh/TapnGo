<?php
//Get a unique fare transaction ID
function getFareTransactionID (){
    include '../backend/db_connect.php';

    //unique transaction ID
    //(year)25(month)12(day)31random 111111
    //250424
    $year = date('ym');
    $randomNumber = "-" . rand(1000,9999) . "-";
    //$randomNumber2 = rand(1000,9999);
    $time = date('s');
    $transactionID = 'TXN'. $year . $randomNumber . $time;
    
    // Check if the transaction ID already exists in the database
    $sql = "SELECT * FROM fare_transaction WHERE transactionID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $txn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the transaction ID already exists, generate a new one
        return getFareTransactionID();
    } else {
        // Return the unique transaction ID without inserting it
        return $transactionID;
    }
}

//insert fare to DB
function insertTodatabase(){
    return getFareTransactionID();
    include '../backend/db_connect.php';

    $sql = "INSERT into transaction (transactionID, customer_name, customer_email, start_point, end_point, distance, discount, earned_points, fare_amount) values (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiii", );
    $stmt->execute();
    $result = $stmt->get_result();
}
?>