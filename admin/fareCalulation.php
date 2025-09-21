<?php

use LDAP\Result;
require '../backend/get_transactions.php';
include '../backend/db_connect.php';

$baseFare = 15.00; //basefare for first 4 km
$ratePerKm = 2.00; //km exceed 4
$pointDiscount = 0.02; // 2% discount for every ride

//Set the manila timezone
$timezone = new DateTime('now', new DateTimeZone('Asia/Manila'));
$date = date('Y-m-d') . " " . $timezone->format('h:i:s'); //date format

//unique transaction ID
//(year)25(month)12(day)31random 111111
//250424
$year = date('ym');
$randomNumber = rand(1000,9999);
//$randomNumber2 = rand(1000,9999);
$time = date('s');
$transactionID = 'TXN'. $year . $randomNumber . $time;

//fetch customer name and Email
$customer = '259472192033';
$email = customerEmail();

//fetch card uid
//$cardid = getCardUid();

// Add this function after the existing decimalToPercent function and before $location array
function calculateFare($distance, $discountType, $baseFare, $ratePerKm) {
    if ($distance <= 4) {
        $fare = $baseFare;
        $discountAmount = $baseFare * $discountType;
    } else {
        $fare = $baseFare + ($distance - 4) * $ratePerKm;
        $discountAmount = $discountType * $fare;
    }
    
    return [
        'fare' => $fare,
        'discountAmount' => number_format($discountAmount, 2),
        'finalFare' => $fare - $discountAmount,
    ];
}

//function points
function earnPoints($result, $pointDiscount){
    $point = number_format($result, 2) * $pointDiscount;
    

    return [
        'earnpoints' => number_format($point,2),
        //'not' => $not,

    ];

}

function sendFareEmail($start, $end, $date, $discountAmount,$discount, $result, $transactionID, $customer, $earnPoints) {
    try {
        require_once(__DIR__ . '/email.php');
        
        if (!isset($mail) || !($mail instanceof PHPMailer\PHPMailer\PHPMailer)) {
            throw new Exception('Email configuration not properly loaded');
        }

        $mail->Body = "<div style='
        background: #eeeeee;
        max-width: 350px;
        margin: 20px auto;
        font-family: \"Courier New\", monospace;
        background: #cecece;
        border: 1px solid #ddd;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        padding: 0 10px;
        color: #333;
        '>

        <div style='
            margin: 20px auto;
            font-family: \"Courier New\", monospace;
            background: #ffffff;
            border: 1px solid #ddd;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
            border-radius: 10px;
            color: #333;
            '>

            <h2 style='margin-top: 0; text-align: center;'>PayMobi RECEIPT</h2>
            <hr style='border-top: 2px dashed #ccc;'>
            <p>Transaction ID: <span style='float: right;'>$transactionID</span></p>
            <p>Date: <span style='float: right;'>$date</span></p>
            <p><strong>Customer:</strong> <span style='float: right;'>$customer</span></p><!--name of customer-->
            <p><strong>Origin:</strong> <span style='float: right;'>$start</span></p>
            <p><strong>Destination:</strong> <span style='float: right;'>$end</span></p>
            <p><strong>Discount:</strong> <span style='float: right;'>$discountAmount [$discount]</span></p>
            <p><strong>Points Earned:</strong> <span style='float: right;'>$earnPoints</span></p>
            <h2 style='font-size: 20px; color:rgb(0, 0, 0);'>Amount: <span style='float: right;'>PHP " . number_format($result, 2) . "</span></h2>
            <hr style='border-top: 2px dashed #ccc; margin: 30px 0;'>
            <p style='font-size: 12px; color: #999; text-align: center;'><span
                    style='font-weight: bolder; color: #3a3a3a;'>This served as your  E-Receipt. Stay safe on your
                    journey, Thank You!.</span></p>
        </div>

        <div style='text-align: center;'>
            <p>
                <hr>This is a system-generated. Please Do Not Reply!. <br>This is a test email sent from a PHPMailer using Gmail SMTP. 
            </p>
            <p>
                @ PayMobi  " . date('Y');  "
            </p>
        </div>
    </div>";

        return $mail->send();
    } catch (Exception $e) {
        error_log("Failed to send email: " . $e->getMessage());
        return false;
    }
}

$location = [
    "Meycauayan" => 0.0,
    "SM Marilao" =>2.3,
    "Fortune Market Marilao" => 2.7,
    "HyperMarket Marilao" => 3.7,
    "WalterMart Guiguinto" => 15.0,
    "STI Balagtas" => 12.0,
    "Puregold Bocaue" => 6.8,
    "JILCF" => 6.1,
    "McdoDonald's Lolomboy" => 5.4,
    "Pambayang Dalubhasan ng Marilao" => 4.1,
    "DYCI-Wakas" => 7.6,
    "Bocaue Crossing" => 7.4,
    "Robinson Balagtas" => 11.0,
    "Calumpit" => 34.0,
    "Bulsu Malolos" => 26.0,
];

$discountType = [
    'None' => 0.0,
    'Student' => 0.2,
    'Senior' =>  0.2,
    'PWD' => 0.2,
];

//
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start = $_POST['start'] ?? '';
    $end = $_POST['end'] ?? '';
    $discount = $_POST['discount'] ?? '';

    //echo $start . ' ' . $end;
    if ($start == $end) {
        $params = http_build_query([
            'error' => 'Start and End point cannot be the same.',
            
        ]);
        echo "Start and End point cannot be the same. <br> <a href='trip.html'>Try Again</a>";
        header("Location: ride.html?=". $params);
        exit;
    }


    if (isset($location[$start]) && isset($location[$end]) && isset($discountType[$discount])) {
        //header('Location: ./fareCalulation.php?=accept-'. $transactionID);
        $distance = abs($location[$end] - $location[$start]);
        $decimal = $discountType[$discount];
        //$percent = number_format($decimal * 100, 0) . "%";
        
        $fareDetails = calculateFare($distance, $decimal, $baseFare, $ratePerKm);
        $fare = $fareDetails['fare'];
        $discountAmount = $fareDetails['discountAmount'];
        $result = $fareDetails['finalFare'];
        //option to remove
        //$amount = number_format($result, 2);
        
        //points
        $result1 = $result;
        $points = earnPoints($result1, $pointDiscount);
        $earnPoints = $points['earnpoints'];
        //$not = $points['not'];
        
        echo "<h3>Fare Result</h3>";
        echo "Date: $date<br>";
        echo "Transaction: $transactionID<br>";
        echo "Customer: $customer<br>";
        echo "Email: $email<br>";
        echo "Start point: $start<br>";
        echo "End point: $end<br>";
        echo "Distance: " .  $distance . "Km<br>";
        echo "Sub-Anount : $fare <br>";
        echo "Discount Amount: $discountAmount<br>";
        //echo "Discount Percent: " . getFareTransactionID()."<br>";
        echo "Points:  $earnPoints<br>";
        echo "Discount:  $discount<br>";
        echo "Fare: PHP $result<br>";
        //echo "points: $earnPoints <br>";
        //echo "not points: $not <br>";
        
        /*// Add this after calculating the fare
        if (!isUserRegistered($customer)) { // Add this function to check if user has registered card
            $params = [
                'receipt' => $transactionID,
                'start' => $start,
                'end' => $end,
                'distance' => $distance,
                'discount' => $discount,
                'discountAmount' => $discountAmount,
                'fare' => $result,
                'date' => $date
            ];
            header("Location: receipt.html?" . http_);
            //header("Location: receipt.html" . pa);
            exit;
        }*/
        
        // Add email sending
        if (sendFareEmail($start, $end, $date, $discountAmount,$discount, $result, $transactionID, $customer, $earnPoints)) {
            echo "<br>Receipt has been sent to your email.";
            
            //insert to database 
            $description = "Fare payment from $start to $end"; // Define description
            $transactionType = "Fare"; // Define transaction type

            $sql1 = "INSERT into transactions (card, transaction_id, description, transaction_type, discount, amount, transaction_date) values (?, ?, ?, ?, ?, ?, ?)";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("ssssdds",$customer, $transactionID, $description, $transactionType, $discountAmount, $result, $date);

            // update card amount
            $sqlUpdate = "UPDATE cards SET card_amount = card_amount - ? WHERE card_number = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ds", $result, $customer);
            $stmtUpdate->execute();
            $stmtUpdate->close();

            if ($stmt1->execute()) {
                echo "<br>Transaction added successfully.";
                //header("Location: trip.html?");
                exit;
            } else {
                echo "<br>Failed to add transaction: " . $stmt->error;
            }
            //close the connection and statement
            $stmt1->close();
            $conn->close();
        } else {
            echo "<br>Could not send receipt to email.";
            
            //insert to database 
            $description = "Fare payment from $start to $end"; // Define description
            $transactionType = "Fare"; // Define transaction type

            $sql2 = "INSERT into transactions (card, transaction_id, description, transaction_type, discount, amount, transaction_date) values (?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("ssssdds",$customer, $transactionID, $description, $transactionType, $discountAmount, $result, $date);

            // update card amount
            $sqlUpdate1 = "UPDATE cards SET card_amount = card_amount - ? WHERE card_number = ?";
            $stmtUpdate1 = $conn->prepare($sqlUpdate);
            $stmtUpdate1->bind_param("ds", $result, $customer);
            $stmtUpdate1->execute();
            $stmtUpdate1->close();

            if ($stmt2->execute()) {
                echo "<br>Transaction added successfully.";
                //header("Location: trip.html?");
                exit;
            } else {
                echo "<br>Failed to add transaction: " . $stmt->error;
            }
            //close the connection and statement
            $stmt2->close();
            $conn->close();
        }
    } else {
        //if location variable binary not match the option value
        echo "invalid Start or end point. (the Input and array not match) ";
    }
} else {
    //accesed without post method or method
    echo 'Access Denied! Please use the form to submit your request.';
    //header("location: ride.html");
    //exit;
}
