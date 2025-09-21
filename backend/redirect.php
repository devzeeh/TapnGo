<?php
session_start();// 
session_unset();// Clear session variables
header("Location: ../login.html");
exit();
?>