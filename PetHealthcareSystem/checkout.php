<?php
include 'header.php'; 

$user_id = $_SESSION['user_id'];

// Get cart items for the order
$cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
$products = [];
$price_total = 0;

if (mysqli_num_rows($cart_query) > 0) {
    while ($product_item = mysqli_fetch_assoc($cart_query)) {
        $products[] = $product_item;
        $price_total += $product_item['price'] * $product_item['quantity'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $street_address = mysqli_real_escape_string($conn, $_POST['street_address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $zip_code = mysqli_real_escape_string($conn, $_POST['zip_code']);
    $hp_number = mysqli_real_escape_string($conn, $_POST['hp_number']);
    
    // Combine the address
    $address = "$street_address, $city, $state, $zip_code, $country";
    $total_products = implode(', ', array_map(fn($item) => $item['name'] . ' (Qty: ' . $item['quantity'] . ')', $products));
    $status = 'Processing';

    // Check product quantities
    $can_place_order = true;
    foreach ($products as $product) {
        $product_id = $product['product_id'];
        $ordered_quantity = $product['quantity'];

        // Get the current product quantity from the database
        $product_query = mysqli_query($conn, "SELECT quantity FROM product WHERE product_id = '$product_id'");
        $product_data = mysqli_fetch_assoc($product_query);

        if ($product_data['quantity'] < $ordered_quantity) {
            $can_place_order = false;
            break; 
        }
    }

    if ($can_place_order) {
        // Insert the order into the order table
        $sql = "INSERT INTO `order` (user_id, name, hp_number, address, products, price, status, order_date) VALUES ('$user_id', '$name', '$hp_number', '$address', '$total_products', '$price_total', '$status', CURDATE())";

        
        if (mysqli_query($conn, $sql)) {
            // Reduce product quantities
            foreach ($products as $product) {
                $product_id = $product['product_id'];
                $ordered_quantity = $product['quantity'];
                mysqli_query($conn, "UPDATE product SET quantity = quantity - $ordered_quantity WHERE product_id = '$product_id'");
            }
            // Clear the cart after successful order
            mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");
            echo "<script>alert('Order placed successfully!'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Error placing order.');</script>";
        }
    } else {
        echo "<script>alert('Insufficient product quantity for one or more items.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        .checkout-container {
            display: flex;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background: #f9f9f9;
        }

        .billing-address {
            flex: 1;
            margin-right: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fff;
        }

        .right-section {
            flex: 1;
            margin-left: 20px;
            display: flex;
            flex-direction: column;
        }

        .order-summary, .payment-method {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fff;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            display: block;
        }

        input[type="text"], input[type="tel"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .cart-items {
            margin: 20px 0;
        }

        .cart-items h3 {
            color: #ff0066;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .cart-item img {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }

        .total-price {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            text-align: right;
            margin-top: 10px;
        }

        .checkout-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 20px;
        }

        .checkout-btn:hover {
            background-color: #218838;
        }

        .credit-card-image {
            text-align: center;
            margin: 20px 0;
        }

        .credit-card-image img {
            max-width: 100%; 
            height: auto; 
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="billing-address">
            <h2>Billing Address</h2>
            <form method="post" action="checkout.php" id="checkout-form">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label for="country">Country:</label>
                    <input type="text" name="country" id="country" required>
                </div>
                <div class="form-group">
                    <label for="state">State:</label>
                    <input type="text" name="state" id="state" required>
                </div>
                <div class="form-group">
                    <label for="street_address">Street Address:</label>
                    <input type="text" name="street_address" id="street_address" required>
                </div>
                <div class="form-group">
                    <label for="city">Town/City:</label>
                    <input type="text" name="city" id="city" required>
                </div>
                <div class="form-group">
                    <label for="zip_code">Zip Code:</label>
                    <input type="text" name="zip_code" id="zip_code" required>
                </div>
                <div class="form-group">
                    <label for="hp_number">Phone Number:</label>
                    <input type="tel" name="hp_number" id="hp_number" required>
                </div>
        </div>

        <div class="right-section">
            <div class="order-summary">
                <h2>Your Order</h2>
                <div class="cart-items">
                    <ul>
                        <?php
                        // Display cart items
                        if (!empty($products)) {
                            foreach ($products as $product) {
                                echo '<li class="cart-item">';
                                echo '<img src="admin/uploaded_img/' . $product['image'] . '" alt="' . $product['name'] . '">';
                                echo '<span>' . $product['name'] . ' (Qty: ' . $product['quantity'] . ')</span>';
                                echo '<span>RM' . number_format($product['price'], 2) . '</span>';
                                echo '</li>';
                            }
                        } else {
                            echo '<li>No items in the cart.</li>';
                        }
                        ?>
                    </ul>
                    <div class="total-price">
                        Total Price: RM<?php echo number_format($price_total, 2); ?>
                    </div>
                </div>
            </div>

            <div class="payment-method">
                <h2>Payment</h2>
                <div class="credit-card-image">
                    <img src="img/cd_card.png" alt="Credit Card">
                </div>
                <p>Please enter your credit card information:</p>
                <div class="form-group">
                        <label for="card_number">Card Number:</label>
                        <input type="tel" name="card_number" id="card_number" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="19" placeholder="xxxx xxxx xxxx xxxx" required>
                    </div>
                    <div class="form-group">
                        <label for="expiry_date">Expiry Date:</label>
                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV:</label>
                        <input type="text" name="cvv" id="cvv" required>
                    </div>
                <button type="button" class="checkout-btn" onclick="confirmOrder()">Confirm Order</button>
                <input type="submit" value="Place Order" style="display: none;">
            </form>
            </div>
        </div>
    </div>

    <script>
        function confirmOrder() {
        // Collect all the form fields
        const name = document.getElementById('name').value.trim();
        const country = document.getElementById('country').value.trim();
        const state = document.getElementById('state').value.trim();
        const streetAddress = document.getElementById('street_address').value.trim();
        const city = document.getElementById('city').value.trim();
        const zipCode = document.getElementById('zip_code').value.trim();
        const hpNumber = document.getElementById('hp_number').value.trim();
        const cardNumber = document.getElementById('card_number').value.trim();
        const expiryDate = document.getElementById('expiry_date').value.trim();
        const cvv = document.getElementById('cvv').value.trim();

        // Check if any of the fields are empty
        if (!name || !country || !state || !streetAddress || !city || !zipCode || !hpNumber || !cardNumber || !expiryDate || !cvv) {
            alert("Please fill in all the fields before placing your order.");
            return;
        }

        // If all fields are filled, confirm the order
        const confirmation = confirm("Are you sure you want to place this order?");
        if (confirmation) {
            document.getElementById('checkout-form').submit(); // Submit the form
        }
    }
    </script>

</body>
</html>
