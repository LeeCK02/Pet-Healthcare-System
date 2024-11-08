<!DOCTYPE html>
<?php 
include 'config/config.php';

error_reporting(0);

session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['confirm-password']);
    $defaultProfilePic = 'uploaded_img/default_profile_pic.png'; // Default profile picture path

    if ($password === $cpassword) {
        $sql = "SELECT * FROM user WHERE email='$email' OR username = '$username'";
        $result = mysqli_query($conn, $sql);
        
        if ($result->num_rows == 0) {
            $sql = "INSERT INTO user (username, email, password, profile_pic)
                    VALUES ('$username', '$email', '$password', '$defaultProfilePic')";
            $result = mysqli_query($conn, $sql);
            
            if ($result) {
                echo "<script>
                        alert('User Registration Successfully Completed.');
                        window.location.href = 'login.php'; // Redirect after alert
                      </script>";
            } else {
                echo "<script>
                        alert('Something went wrong. Please try again.');
                        window.location.href = 'register.php'; // Redirect back to register page
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Email or Username already exists.');
                    window.location.href = 'register.php'; // Redirect back to register page
                  </script>";
        }
        
    } else {
        echo "<script>
                alert('Passwords do not match.');
                window.location.href = 'register.php'; // Redirect back to register page
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
        <title>Register</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <script src="js/main.js"></script>
    </head>
    <body>
        <!-- Registration Form -->
        <div class="register-box">
            <h1>Register</h1>
            <form name="register-form" method="POST" onsubmit="return validation()">
              <div class="input">
                <input type="text" name="username" id="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required><br>
              </div>
              <div class="input">
                <input type="email" name="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required><br>
              </div>
              <div class="input">
                <input type="password" name="password" id="password" placeholder="Password" value="<?php echo htmlspecialchars($_POST['password']); ?>" required><br>
              </div>
              <div class="input">
                  <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" value="<?php echo htmlspecialchars($_POST['confirm-password']); ?>" required><br>
              </div>
              <input type="submit" name="submit" value="Register" class="btn">
            </form>
            <div class="txt">
                <a>Already own an account? &nbsp;&nbsp;&nbsp; <a href="login.php">Login Here!</a>
            </div>
        </div>
    </body>
</html>
