<?php
include 'header.php'; 
include 'config/config.php'; // Database connection

$user_id = $_SESSION['user_id'];

// Handle item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $cart_id = $_POST['cart_id'];
    
    // Prepare a SQL statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
    $stmt->bind_param("i", $cart_id);
    
    if ($stmt->execute()) {
        // Item successfully removed
        echo "<script>alert('Item removed from cart.');</script>";
    } else {
        echo "<script>alert('Error removing item from cart.');</script>";
    }
}

// Fetch cart items
$cart_items = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
    /* Cart Page Styles */
    .cart-container {
        max-width: 800px; 
        margin: 20px auto;
        padding: 20px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        background: #fff;
        padding: 15px; 
        margin-bottom: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .cart-item img {
        width: 120px; 
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
    }

    .cart-details {
        width: 70%; 
        display: flex;
        flex-direction: column;
        justify-content: center; /* Centered vertically */
    }

    .cart-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px; /* Add margin for spacing */
    }

    .quantity-input {
        width: 40px; 
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-align: center;
    }

    .remove-btn {
        background-color: #e74c3c;
        color: white;
        padding: 5px 8px; 
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .remove-btn:hover {
        background-color: #c0392b;
    }

    .checkout-btn {
        background-color: #198c8c;
        color: white;
        border: none;
        padding: 12px; 
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.2s;
        display: block;
        margin: 20px auto 0; 
        text-align: center;
        text-decoration: none; /* Remove underline */
    }

    .checkout-btn:hover {
        background-color: #146f6f;
    }
    </style>
</head>
<body>
    <div class="cart-container">
        <h2>Your Cart</h2>

        <?php if (mysqli_num_rows($cart_items) > 0) { ?>
            <?php while ($item = mysqli_fetch_assoc($cart_items)) { ?>
                <div class="cart-item">
                    <img src="admin/uploaded_img/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <div class="cart-details">
                        <h3><?php echo $item['name']; ?></h3>
                        <p>Price: RM<?php echo number_format($item['price'], 2); ?></p>
                        <div class="cart-actions">
                            <input type="number" class="quantity-input" style="width:80px;" value="<?php echo $item['quantity']; ?>" min="1" onchange="updateQuantity(<?php echo $item['cart_id']; ?>, this.value)">
                            
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                <input type="hidden" name="remove" value="1">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        <?php } else { ?>
            <p>Your cart is empty.</p>
        <?php } ?>
    </div>

    <script>
        function updateQuantity(cartId, quantity) {
            // Implement AJAX call to update quantity in the database
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cart_id: cartId, quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Optionally, update UI or show success message
                    console.log('Quantity updated successfully.');
                } else {
                    alert('Failed to update quantity.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
