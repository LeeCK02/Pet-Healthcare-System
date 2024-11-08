<?php
include 'header.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Show an alert and redirect using JavaScript
    echo "<script>
            alert('You must be logged in to access this page.');
            window.location.href = 'login.php'; // Redirect to the login page
          </script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's order history
$order_query = mysqli_query($conn, "SELECT * FROM `order` WHERE user_id = '$user_id' ORDER BY order_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        .order-history-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background: #f9f9f9;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .order {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .order:last-child {
            border-bottom: none;
        }

        .order-info {
            display: flex;
            justify-content: space-between;
        }

        .order-details {
            flex: 1;
        }

        .order-status {
            font-weight: bold;
            color: #28a745;
        }

        .order-total {
            font-weight: bold;
            color: #333;
        }

        .order-date {
            color: #999;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="order-history-container">
        <h2>Your Order History</h2>
        <?php
        if (mysqli_num_rows($order_query) > 0) {
            while ($order = mysqli_fetch_assoc($order_query)) {
                echo '<div class="order">';
                echo '<div class="order-info">';
                echo '<div class="order-details">';
                echo '<span>Order ID: ' . $order['order_id'] . '</span><br>';
                echo '<span class="order-status">Status: ' . $order['status'] . '</span>';
                echo '</div>';
                echo '<div class="order-total">Total: RM' . number_format($order['price'], 2) . '</div>';
                echo '</div>';
                echo '<p>Products: ' . $order['products'] . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No orders found.</p>';
        }
        ?>
    </div>
</body>
</html>
