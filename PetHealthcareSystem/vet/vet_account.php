<?php 
include 'vet_header.php';

// Start output buffering to avoid "headers already sent" error
ob_start();

// Fetch vet details from the session
$vet_id = $_SESSION['vet_id'];
$query = "SELECT * FROM veterinary WHERE vet_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vet_id);
$stmt->execute();
$result = $stmt->get_result();
$vet = $result->fetch_assoc();

// Handle form submission for updating profile picture
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile_picture'])) {
    // Handle file upload
    $profile_picture = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $profile_picture = 'uploads/' . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);

        // Update the profile picture in the database
        $update_query = "UPDATE veterinary SET profile_picture = ? WHERE vet_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $profile_picture, $vet_id);
        $stmt->execute();

        // Redirect to avoid form resubmission
        echo "<script>alert('Profile picture updated successfully.'); window.location.href = 'vet_account.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error uploading the profile picture. Please try again.');</script>";
    }
}

// End output buffering and flush output
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vet Account</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
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

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
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
    </style>
</head>
<body>
    <div class="account-box">
        <h1>Vet Account</h1>
        
        <!-- Display profile picture -->
        <img src="<?php echo htmlspecialchars($vet['profile_picture'] ?? 'uploads/default_profile_pic.png'); ?>" alt="Profile Picture" class="profile-pic">

        <!-- Form to update profile picture -->
        <form method="POST" enctype="multipart/form-data">
            <div class="input">
                <input type="file" name="profile_picture" accept="image/*" required>
            </div>
            <input type="submit" name="update_profile_picture" value="Update Profile Picture" class="btn">
        </form>
        <br/><br/>

        <!-- Display vet details -->
        <div class="details">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($vet['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($vet['email']); ?></p>
        </div>
    </div>
</body>
</html>
