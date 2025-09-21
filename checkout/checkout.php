<?php
session_start();
require_once '../stripe-php-16.6.0/init.php';
require '../config.php';
require '../backend/StripeConnect.php';

function Email(){
    include '../backend/db_connect.php';
    $name = getLoggedInUserName();

    $sql = "SELECT email from users where name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
    }

    return $email;
    $conn->close();
    $stmt->close();
}

$stripe = new \Stripe\StripeClient(STRIPE_KEY);

// Get amount from POST request
$price = isset($_POST['card-amount']) ? (float)$_POST['card-amount'] : 0;

// Validate amount
if ($price < 50 || $price > 20000) {
    header("Location: ../main/topup.html?error=invalid_amount");
    exit();
}
// Calculate processing fee
$process = 10.00; // Fixed processing fee
$total_amount = $price + $process;

// Prepare transaction data
$transaction_data = [
    'amount' => $price,
    'processing_fee' => $process,
    'total_amount' => $total_amount,
    'status' => 'Success',
    'created_at' => date('Y-m-d H:i:s')
];
// Save it to session
$_SESSION['transaction_data'] = $transaction_data;

//prepare shopping cart data for stripe
$lineItems = [
    [
        'price_data' => [
            'currency' => 'php',
            'product_data' => [
                'name' => 'CARDS',
                'description' => 'Card Reloading Payment: ₱' . number_format($price, 2),// convert to 2 decimal places
                'images' => ['https://img.freepik.com/free-vector/paper-money-dollar-bills-blue-credit-card-3d-illustration-cartoon-drawing-payment-options-3d-style-white-background-payment-finances-shopping-banking-commerce-concept_778687-724.jpg?t=st=1742172544~exp=1742176144~hmac=928dbca129fbf676bf9fdc49187f4553c41f4978c895fdbc5b83d569ce013999&w=740'],
            ],
            'unit_amount' => $price * 100,
        ],
        'quantity' => 1,
    ],

    //processing fee
    [
        'price_data' => [
            'currency' => 'php',
            'product_data' => [
                'name' => 'Processing Fee',
                'description' => 'Payment Processing Fee',
            ],
            'unit_amount' => 10 * 100,//Convert to cents example 1000 = 10.00
        ],
        'quantity' => 1,
    ],
];//end of lineItems
//create Stripe Checkout session
$checkout_session = $stripe->checkout->sessions->create([
    'payment_method_types' => ['card'],
    'line_items' => $lineItems,
    'mode' => 'payment',
    'customer_email' => 'test@test.com',
    'success_url' => 'http://localhost/technopreneurship/checkout/StripeSuccess.php',
    'cancel_url' => 'http://localhost/technopreneurship//main/topup.html'  
]);



//retrieve provider_session_id. store it in database.
$checkout_session->id;
//send user to stripe
header('Content-Type: application/json');
header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
exit();

?>
