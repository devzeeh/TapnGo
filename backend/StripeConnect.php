<?php
// Stripeconnect.php
include_once '../config.php'; // config connection file

// Create connection
try{
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_STRIPE);
    //die("Connected Successfully");
}catch (mysqli_sql_exception $e) {
    die("Message: ". $e->getMessage());
}
?>