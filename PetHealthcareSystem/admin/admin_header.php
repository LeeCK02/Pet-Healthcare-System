<?php 
include 'config/config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="admin_css/main.css">
</head>

<style>
    .nav-links .logout-btn {
        margin-left: 100%; /* Pushes the logout link to the right */
    }
</style>

<body>
    <nav class="navbar">
        <div class="logo">
            <h1>Admin Panel</h1>
        </div>
        <ul class="nav-links">
            <li><a href="admin_index.php">Home</a></li>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="admin_manage_user.php">User</a></li>
            <li><a href="admin_manage_veterinary.php">Veterinary</a></li>
            <li><a href="admin_manage_vaccine.php">Vaccine</a></li>
            <li><a href="admin_manage_product.php">Product</a></li>
            <li><a href="admin_manage_hospitalization.php">Hospitalization</a></li>
            <li><a href="admin_manage_order.php">Order</a></li>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
