<!DOCTYPE html>
<?php 
include 'config/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/PHPMailer-6.8.0/src/Exception.php';
require 'C:/xampp/htdocs/PHPMailer-6.8.0/src/PHPMailer.php';
require 'C:/xampp/htdocs/PHPMailer-6.8.0/src/SMTP.php';

error_reporting(0);

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM user WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        $user = mysqli_fetch_assoc($result);
        $reset_code = rand(100000, 999999);

        $sql = "UPDATE user SET reset_code='$reset_code', reset_code_expiry=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'ivanlee0224@gmail.com'; 
                $mail->Password   = 'anmm gfxk clax juda';        
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('ivanlee0224@gmail.com', 'Pet Healthcare'); 
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Code';
                $mail->Body    = 'Your password reset code is: <b>' . $reset_code . '</b>. This code is valid for 1 hour.';
                $mail->AltBody = 'Your password reset code is: ' . $reset_code . '. This code is valid for 1 hour.';

                $mail->send();
                echo "<script>alert('Password reset code has been sent to your email.')</script>";
                header("Location: verify_code.php?email=$email");
            } catch (Exception $e) {
                echo "<script>alert('Failed to send the reset code. Mailer Error: {$mail->ErrorInfo}')</script>";
            }
        } else {
            echo "<script>alert('Something went wrong. Please try again.')</script>";
        }
    } else {
        echo "<script>alert('Email does not exist.')</script>";
    }
}
?>

<style>
    
body {
    width: 100%;
    min-height: 100vh;
    background-image: linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.5)), url(img/vet_cat.jpg);
    background-position: center;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
}
    
</style>

<html>
    <head>
        <title>Forgot Password</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <div class="login-box">
            <h1>Forgot Password</h1>
            <form class="login-form" method="POST">
                <div class="input">
                    <input type="email" name="email" placeholder="Enter your registered email" required>
                </div>
                <button type="submit" name="submit" class="btn">Send Reset Code</button>
            </form>
            <div class="txt">
                <a>Back to Login? &nbsp;&nbsp;<a href="login.php">Login</a>
            </div>
        </div>
    </body>
</html>
