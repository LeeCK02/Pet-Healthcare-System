<?php 
session_start();
include 'config/config.php'; // Database connection

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to fetch the veterinary account
    $query = mysqli_query($conn, "SELECT * FROM veterinary WHERE email = '$email'");
    $row = mysqli_fetch_assoc($query);

    // Verify password
    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['vet_id'] = $row['vet_id'];
        $_SESSION['vet_name'] = $row['name'];
        header("Location: vet_index.php"); // Redirect to index after login
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Veterinary Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container" style="padding: 100px;">
    <h2 class="text-center">Veterinary Login</h2>
    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger text-center">
            <?php echo $error_message; ?>
        </div>
    <?php } ?>
    <form action="" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
    </form>
    <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a>.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
