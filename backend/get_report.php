<?php
session_start();
function GetReportData(){
    include '../backend/db_connect.php';

    $sql = "SELECT * FROM report";
    $result = $conn->query($sql);

    
    echo "<table>";
    echo "<tr><th>User ID</th><th>Report type</th><th>Status</th><th>Report date</th><th>Resolved daste</th></tr>";

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['user_id']  . "</td>";
            echo "<td>" . $row['report_type'] . "</td>";   
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
            //$data[] = $row; // <-- add each row to $data array
        }
    }

    $conn->close();
}



?>