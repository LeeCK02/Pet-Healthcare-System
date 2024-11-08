<?php
include 'config/config.php';

$id = $_GET['edit'];

if (isset($_POST['update_vet'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check for duplicate name or email
    $check_query = "SELECT * FROM veterinary WHERE (name = '$name' OR email = '$email') AND vet_id != '$id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // If a duplicate is found, show an error message
        echo "<script>alert('Error: The name or email is already in use. Please try a different one.');</script>";
    } else {
        // Proceed with the update if there are no duplicates
        $update_data = "UPDATE veterinary SET name='$name', email='$email', password='$password' WHERE vet_id = '$id'";
        $upload = mysqli_query($conn, $update_data);

        if ($upload) {
            // Use a JavaScript alert for success and then redirect
            echo "<script>
                    alert('Veterinary account updated successfully!');
                    window.location.href = 'admin_manage_veterinary.php';
                  </script>";
        } else {
            echo "<script>alert('Update failed. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pet Healthcare &bull; Update Veterinary</title>
    <meta charset="UTF-8">
    <link rel="icon" href="img/honda-icon.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_css/main.css">
</head>
<body>
    <div style="padding: 1px; margin: 0 auto; text-align:center; margin-top: 150px;">
        <?php
        $select = mysqli_query($conn, "SELECT * FROM veterinary WHERE vet_id = '$id'");
        $row = mysqli_fetch_assoc($select); // Fetch a single row
        ?>

        <div id="add-box" class="add-box" style="padding: 10px 10px; border: 1px solid; margin-top: 30px; margin-bottom: 60px; width: 50%; margin: 0 auto;">
            <h2>Update Veterinary Account</h2>
            <form action="" method="post" onsubmit="return confirmUpdate()">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $row['name'] ?>" required>
                <br>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $row['email'] ?>" required>
                <br>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <br>
                <br>
                <input type="submit" name="update_vet" value="Update Veterinary">
                <a href="admin_manage_veterinary.php" class="back-btn">Back</a>
            </form>
        </div>
    </div>

    <script>
        // Function to show a confirmation alert before form submission
        function confirmUpdate() {
            return confirm("Are you sure you want to update this veterinary account?");
        }
    </script>
</body>
</html>
