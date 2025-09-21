<?php
//get user name


//get time
function getTime() {
    date_default_timezone_set('Asia/Manila'); // Set your timezone

    $hour = date('H'); // 24-hour format (00 to 23)
    $minute = date('i'); // minutes

    $currentTime = (int)($hour . $minute); // Combine hour and minute as an integer

    if ($currentTime >= 0 && $currentTime <= 1159) {
        echo "Good Morning";
    } elseif ($currentTime >= 1200 && $currentTime <= 1759) {
        echo "Good Afternoon";
    } else {
        echo "Good Evening";
    }
}
?>
