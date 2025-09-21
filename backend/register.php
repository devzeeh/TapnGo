<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include '../backend/db_connect.php';

    // Get form data and sanitize
    $card = $_POST['card'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $user = 'User';
    $amount = 50.00;
    //Set date
    $date = date('Y-m-d');
    $cardExpiredDate = date('Y-m-d', strtotime($date . ' +5 years'));
    //$user = 'Users';
    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Email already registered. ";
        //echo "<a href='../register.html'>back to registration</a>";
        exit;
    }

    //check card status
    $stmt = $conn->prepare("SELECT card_status FROM cards WHERE card_number = ?");
    $stmt->bind_param("s", $card);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $status = $row['card_status'];

        $deniedStatuses = ['STOLEN', 'LOST', 'DAMAGE', 'EXPIRED', 'ACTIVE'];


        if (in_array($status, $deniedStatuses)) {
            echo 'Access denied';
            echo '<br>';
            echo 'Card can\'t be used. (card already in circulation)';
        } elseif ($status === 'UNREGISTERED') {
            echo 'pwede yan';
            echo 'Access granted. ';
            //update cards status
            $stmt2 = $conn->prepare("UPDATE cards set users = ?, card_status = 'ACTIVE',card_amount = ?,  expired_date = ? WHERE card_number = ?");
            $stmt2->bind_param("sdss",$name, $amount, $cardExpiredDate, $card);
            $stmt2->execute();
            echo 'update successful. ';

            //insert user data
            // Hash password
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Insert user
            $stmt3 = $conn->prepare("INSERT INTO users (name, email, password, create_date, user_type) VALUES (?, ?, ?, ?, ?)");
            $stmt3->bind_param("sssss", $name, $email, $hashedPassword, $date, $user);

            if ($stmt3->execute()) {
                echo "Registration successful. ";
                //header("Location: ../login.html"); // or wherever you redirect after registration
            } else {
                echo "Failed to register user. ";
            }
        } else {
            //if card is not on list (optional)
            echo 'unknown card status';
        }
    } else {
    
        //header('Location: ../register.html');
        echo 'card not found';
    }
    exit;
    //close statement and connection
    $stmt->close();
    $conn->close();
}
?>