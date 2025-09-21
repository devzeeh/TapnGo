<?php
// db_connect.php
//include_once '../config.php';
$servername = 'localhost';
$dbusername = "root";
$dbpassword = "";
$dbname = 'cashless';
// Check connection
try{
    $conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);
    //die("Connected Successfully");
}catch (mysqli_sql_exception $e) {
    //die("Message: ". $e->getMessage());
}
?>
