<?php
//get the fare

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "test";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data and sanitize
    $entry = $_POST['entry-distance'] ?? '';
    $exit = $_POST['exit-distance'] ?? '';


    echo $entry;
    echo $exit;
}
?>