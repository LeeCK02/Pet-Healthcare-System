<?php
include 'header.php'; 
include 'config/config.php'; // database connection

// Fetch the product ID from the query string
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the selected product details from the database
$productQuery = mysqli_query($conn, "SELECT * FROM product WHERE product_id = $product_id");
$product = mysqli_fetch_assoc($productQuery);

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Details - Pet Healthcare</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0047ab;
            text-align: center;
            margin-bottom: 10px;
        }
        .line {
            width: 80%;
            height: 2px;
            background-color: #0047ab;
            margin: 0 auto 20px;
        }
        .line2 {
            margin-top: 30px;
            margin-bottom: 30px;
            width: 100%;
            height: 1px;
            background-color: #dbdbdb;
        }
        .product-details {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product-details img {
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }
        .details {
            flex: 1;
        }
        .details h1 {
            font-size: 28px;
            color: #333;
            margin-top: 0;
        }
        .details p {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        .details .price,
        .details .quantity {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }
        .details .quantity {
            margin-top: 10px;
        }
        .details .quantity input {
            width: 60px;
            text-align: center;
            padding: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .add-to-cart-btn {
            background-color: #198c8c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.2s;
        }
        .add-to-cart-btn:hover {
            background-color: #146f6f;
        }
        .add-to-cart-btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="title">Product Details</div>
        <div class="line"></div>
        <div class="product-details">
            <img src="admin/uploaded_img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <div class="details">
                <h1><?php echo $product['name']; ?></h1>
                <p><strong>Type:</strong> <?php echo $product['type']; ?></p>
                <p><strong>Description:</strong> <?php echo $product['description']; ?></p>
                <p class="price"><strong>Price:</strong> RM<?php echo number_format($product['price'], 2); ?></p>
                <p><strong>Stock:</strong> <?php echo $product['quantity']; ?></p>
                <div class="line2"></div>
                <p class="quantity">
                    <strong>Quantity:</strong> 
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>">
                </p>
                <button id="addToCartBtn" class="add-to-cart-btn" onclick="addToCart(<?php echo $product['product_id']; ?>, '<?php echo $product['name']; ?>', <?php echo $product['price']; ?>, '<?php echo $product['image']; ?>')">
                    <i class="fas fa-cart-plus"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>

    <script>
        function addToCart(productId, productName, price, image) {
            // Get the quantity from the input field
            const quantity = document.getElementById('quantity').value;

            // Check if user is logged in
            const isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>;

            if (!isLoggedIn) {
                // Show an alert if the user is not logged in
                alert("You need to be logged in to add items to your cart.");
                return;
            }

            // Proceed to add to cart
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    name: productName,
                    price: price,
                    image: image,
                    quantity: quantity,
                    user_id: <?php echo json_encode($_SESSION['user_id'] ?? null); ?>
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Item added to cart!");
                } else {
                    alert("Item is already in your cart.");
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

</body>
</html>
