<?php
require '../backend/StripeConnect.php';
// Retrieve transaction data from session
$transaction_data = $_SESSION['transaction_data'] ?? null;

// if transaction data not found direct eror, opt del
if (!$transaction_data) {
    die('Transaction data not found in session.');
    //back to topup page, 
    header("Location: '../main/topUp.html");
}

// Insert into database
try {
    $sql = "INSERT INTO transactions (amount, processing_fee, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddsss",
        $transaction_data['amount'],
        $transaction_data['processing_fee'],
        $transaction_data['total_amount'],
        $transaction_data['status'],
        $transaction_data['created_at']
    );
    $stmt->execute();
    sleep(3); //simulate processing delay
    header("Location: ../main/topUp.html");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
// Clear session data after use
unset($_SESSION['transaction_data']);
//clost statement and conne
$stmt->close();
$conn->close();
?>