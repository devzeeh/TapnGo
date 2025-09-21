<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../backend/db_connect.php'; // adjust path if needed

    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo 'taken';
    } else {
        echo 'available';
    }

    $stmt->close();
    $conn->close();
}
?>
