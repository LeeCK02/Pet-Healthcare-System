<!DOCTYPE html>
<?php 
include 'config/config.php';
error_reporting(0);

if (isset($_GET['email']) && isset($_POST['submit'])) {
    $email = $_GET['email'];
    $reset_code = $_POST['reset_code'];

    $sql = "SELECT * FROM user WHERE email='$email' AND reset_code='$reset_code' AND reset_code_expiry > NOW()";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        header("Location: reset_password.php?email=$email");
    } else {
        echo "<script>alert('Invalid or expired reset code.')</script>";
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
        <title>Verify Code</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <div class="login-box">
            <h1>Verify Reset Code</h1>
            <form class="login-form" method="POST">
                <div class="input">
                    <input type="text" name="reset_code" placeholder="Enter your reset code" required>
                </div>
                <button type="submit" name="submit" class="btn">Verify Code</button>
            </form>
        </div>
    </body>
</html>
