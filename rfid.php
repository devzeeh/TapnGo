<?php
/*require './config.php';
$uid = $_GET['uid'];
//$uid = 'F3BF44EC';
$timezone = new DateTime('now', new DateTimeZone('Asia/Manila'));
$timestamp = date('Y-m-d ') . $timezone->format('h:i:s');
// DB connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
  die("Connection failed: {$conn->connect_error}");
}

// Check if card exists in database
$stmt1 = $conn->prepare("SELECT card_uid, card_amount FROM cards WHERE card_uid = ?");
$stmt1->bind_param("s", $uid);
$stmt1->execute();
$result = $stmt1->get_result();

if ($result->num_rows > 0) {
  $card = $result->fetch_assoc();
  $fare = 15;
  // Get fare from farecalculation.php
  //require_once './farecalculation.php';

  // Check if card has enough balance
  if ($card['card_amount'] >= $fare) {
    // Card exists and has enough balance, log the tap
    $stmt2 = $conn->prepare("INSERT INTO rfid_tap (card_uid, date_time) VALUES (?, ?)");
    $stmt2->bind_param("ss", $uid, $timestamp);
    $stmt2->execute();

     Deduct fare from card_amount
    $newAmount = $card['card_amount'] - $fare;
    $stmt4 = $conn->prepare("UPDATE cards SET card_amount = ? WHERE card_uid = ?");
    $stmt4->bind_param("ds", $newAmount, $uid);
    $stmt4->execute();

    echo "Card tap logged successfully.";
    

    // Show the logged tap
    // Get the last tap time and card UID
    $stmt3 = $conn->prepare("SELECT card_uid, date_time FROM rfid_tap where date_time = ?");
    $stmt3->bind_param("s", $timestamp);
    $stmt3->execute();
    $tapResult = $stmt3->get_result();

    $stmt2->close();
    $stmt3->close();


    if ($row = $tapResult->fetch_assoc()) {
      //put fare calculation here
      
        //echo "<br>Remaining balance: {$newAmount}";
        header(('Location: ./Admin/fareCalulation.php'));
        exit(); // Add exit to stop further execution
      //header('Location: ./Admin/ride.html');
      //exit(); // Add exit to stop further execution
      //header("Location: index.php?uid={$row['card_uid']}");
      //echo "<br>Last tap - UID: {$row['card_uid']} Time: {$row['date_time']}";
      
    }
  } else {
    echo "Error: Insufficient balance";
  }
} else {
  echo "Error: Card not registered in database";
}

$conn->close();
$stmt1->close();*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  session_start();
    if (isset($_SESSION['start']) && isset($_SESSION['end']) && isset($_SESSION['discount'])) {
        $start = $_SESSION['start'];
        $end = $_SESSION['end'];
        $discount = $_SESSION['discount'];
        
        // Clear the session variables after using them
        unset($_SESSION['start']);
        unset($_SESSION['end']);
        unset($_SESSION['discount']);
    } 
    $uid = $_GET['uid'];
    //$uid = 'F3BF44EC';
    $timezone = new DateTime('now', new DateTimeZone('Asia/Manila'));
    $timestamp = date('Y-m-d ') . $timezone->format('h:i:s');
    // DB connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
      die("Connection failed: {$conn->connect_error}");
    }

    header('Location: ../Admin/fareCalulation.php');

    $stmt->close();
    $conn->close();
}

?>