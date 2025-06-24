<?php
session_start();

if (!isset($_SESSION['otp']) || !isset($_POST['entered_otp'])) {
    echo "OTP not set or invalid access.";
    exit();
}

$entered_otp = $_POST['entered_otp'];
$session_otp = $_SESSION['otp'];

if ($entered_otp == $session_otp) {
    $_SESSION['otp_verified'] = true;
    echo "success";
} else {
    echo "Invalid OTP. Please try again.";
}
?>
