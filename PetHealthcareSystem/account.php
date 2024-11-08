<?php
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT username, email, profile_pic FROM user WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_password'])) {
        // Update password
        $new_password = md5($_POST['new_password']);
        $confirm_password = md5($_POST['confirm_password']);

        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            echo "<script>alert('Passwords do not match.');</script>";
        } else {
            $update_query = "UPDATE user SET password = '$new_password' WHERE user_id = '$user_id'";
            if (mysqli_query($conn, $update_query)) {
                echo "<script>alert('Password updated successfully.'); window.location.href = window.location.href;</script>";
                exit();
            } else {
                echo "<script>alert('Error updating the password. Please try again.');</script>";
            }
        }
    } elseif (isset($_POST['update_profile_pic'])) {
        // Update profile picture
        $profile_pic = $_FILES['profile_pic'];
        $profile_pic_path = $user['profile_pic']; // Keep the old profile pic by default

        if ($profile_pic['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploaded_img/'; // Set the upload directory
            $file_name = basename($profile_pic['name']);
            $unique_file_name = uniqid() . '-' . $file_name;
            $profile_pic_path = $upload_dir . $unique_file_name;

            // Move the uploaded file to the designated directory
            if (move_uploaded_file($profile_pic['tmp_name'], $profile_pic_path)) {
                $update_query = "UPDATE user SET profile_pic = '$profile_pic_path' WHERE user_id = '$user_id'";
                if (mysqli_query($conn, $update_query)) {
                    echo "<script>
                            alert('Profile picture updated successfully.');
                            window.location.href = window.location.href; // Refresh the page
                          </script>";
                    exit();
                } else {
                    echo "<script>alert('Error updating the profile picture. Please try again.');</script>";
                }
            } else {
                echo "<script>alert('Error uploading the profile picture.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Account</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .account-box {
            margin: 0 auto;
            margin-top: 50px;
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .account-box h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #0047ab;
        }

        .input {
            margin-bottom: 20px;
        }

        .input input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }

        .input input[type="file"] {
            border: none;
        }

        .btn {
            background-color: #0047ab;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        .btn:hover {
            background-color: #00327a;
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function validation() {
            const password = document.getElementById("new_password").value;
            const confirmPassword = document.getElementById("confirm_password").value;
            
            // Regular expression to check if password meets the requirements
            const passwordPattern = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

            if (!passwordPattern.test(password)) {
                alert("Password must be at least 8 characters long, contain at least one uppercase letter, and include at least one number.");
                return false; // Prevent form submission
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false; // Prevent form submission
            }

            // Ask for confirmation before updating the password
            return confirm("Are you sure you want to update your password?");
        }

        function previewProfilePic(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-pic-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        function confirmProfilePicUpdate() {
            return confirm("Are you sure you want to update your profile picture?");
        }
    </script>
</head>
<body>
    <div class="account-box">
        <h1>Edit Account</h1>
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php elseif (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Display the profile picture -->
        <img id="profile-pic-preview" src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture" class="profile-pic">

        <!-- Display the username and email -->
        <div class="input">
            <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
        </div>
        <div class="input">
            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
        </div>

        <!-- Form to update profile picture -->
        <form method="POST" enctype="multipart/form-data" onsubmit="return confirmProfilePicUpdate()">
            <div class="input">
                <input type="file" name="profile_pic" accept="image/*" onchange="previewProfilePic(event)">
            </div>
            <input type="submit" name="update_profile_pic" value="Update Profile Picture" class="btn">
        </form>
        <br/><br/>
        <!-- Form to update password -->
        <form method="POST" onsubmit="return validation()">
            <div class="input">
                <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
            </div>
            <div class="input">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <input type="submit" name="update_password" value="Update Password" class="btn">
        </form>
    </div>
</body>
</html>
