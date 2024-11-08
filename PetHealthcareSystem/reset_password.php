<!DOCTYPE html>
<?php 
include 'config/config.php';
error_reporting(0);

if (isset($_GET['email']) && isset($_POST['submit'])) {
    $email = $_GET['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['confirm-password']);

    if ($password === $cpassword) {
        $sql = "UPDATE user SET password='$password', reset_code=NULL, reset_code_expiry=NULL WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>
                    alert('Password has been reset successfully.');
                    window.location.href = 'login.php'; // Redirect to login page after alert
                  </script>";
        } else {
            echo "<script>
                    alert('Something went wrong. Please try again.');
                    window.location.href = 'reset-password.php?email=" . urlencode($email) . "'; // Redirect back to the reset page
                  </script>";
        }
    } else {
        echo "<script>
                alert('Passwords do not match.');
                window.location.href = 'reset-password.php?email=" . urlencode($email) . "'; // Redirect back to the reset page
              </script>";
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
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="js/main.js"></script>
</head>
<body>
    <div class="login-box">
        <h1>Reset Password</h1>
        <form class="login-form" method="POST" onsubmit="return validation()">
            <div class="input">
                <input type="password" name="password" id="password" placeholder="Enter new password" required>
            </div>
            <div class="input">
                <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm new password" required>
            </div>
            <button type="submit" name="submit" class="btn">Reset Password</button>
        </form>
        <div class="txt">
            <a>Back to Login? &nbsp;&nbsp;<a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
