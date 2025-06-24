<?php

session_start();
require_once("db.php");
require __DIR__ . '/vendor/autoload.php'; // adjust if checklogin.php is in root
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST)) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password = base64_encode(strrev(md5($password)));

    $sql = "SELECT id_user, firstname, lastname, email, active FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            if ($row['active'] == '0') {
                $_SESSION['loginActiveError'] = "Your Account Is Not Active. Check Your Email.";
                header("Location: login-candidates.php");
                exit();
            } else if ($row['active'] == '1') {

                // ✅ Generate OTP and send via email
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['otp_email'] = $row['email'];
                $_SESSION['otp_user_id'] = $row['id_user'];
                $_SESSION['otp_name'] = $row['firstname'] . " " . $row['lastname'];

                // Send OTP Email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kansallovish471@gmail.com'; // your Gmail
                    $mail->Password = 'tdqtplgkbgniuwed';    // your App Password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('your_email@gmail.com', 'Placement Portal');
                    $mail->addAddress($row['email'], $row['firstname']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Your OTP for Placement Portal';
                    $mail->Body    = "Hello <b>{$row['firstname']}</b>,<br><br>Your OTP is: <b>$otp</b><br><br>Use this to verify your login.";

                    $mail->send();

                    // Redirect to OTP Verification page
                    header("Location: otp_verification.php");
                    exit();

                } catch (Exception $e) {
                    $_SESSION['loginError'] = "Email sending failed. Try again.";
                    header("Location: login-candidates.php");
                    exit();
                }

            } else if ($row['active'] == '2') {
                $_SESSION['loginActiveError'] = "Your Account Is Deactivated. Contact Admin.";
                header("Location: login-candidates.php");
                exit();
            }
        }
    } else {
        $_SESSION['loginError'] = "Invalid email or password.";
        header("Location: login-candidates.php");
        exit();
    }

    $conn->close();
} else {
    header("Location: login-candidates.php");
    exit();
}
