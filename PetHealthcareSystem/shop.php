<?php
include 'header.php'; 
include 'config/config.php'; // database connection

// Fetch products from the database
$products = mysqli_query($conn, "SELECT * FROM product ORDER BY product_id");

// Fetch unique types for the filter dropdown
$types = mysqli_query($conn, "SELECT DISTINCT type FROM product ORDER BY type");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shop - Pet Healthcare</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <style>
        h2 {
            color: #0047ab;
            text-align: center;
            font-weight: bold;
            margin-top: 50px;
        }
        .shop-container {
            display: flex;
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            gap: 20px;
        }
        .filter-container {
            width: 25%;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .filter-container h2 {
            margin-top: 10px;
            font-size: 24px;
            color: #198c8c;
        }
        .filter-container input,
        .filter-container select {
            width: 100%;
            padding: 10px;
            margin-top: 35px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .price-range {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .price-range input {
            width: 48%;
            box-sizing: border-box;
        }
        .product-container {
            width: 75%;
            height: 450px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
            margin-bottom: 50px;
        }
        .product-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 200px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            box-sizing: border-box;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column; 
            justify-content: space-between; 
            height: 100%; 
        }
        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product-card h3 {
            font-size: 18px;
            color: #333;
            margin: 10px 0;
            min-height: 60px; /* Set a fixed height for the title */
            overflow: hidden;
            text-align: center;
        }
        .product-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        .add-to-cart-btn {
            background-color: #198c8c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: auto; /* Push the button to the bottom */
            display: block;
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

    <div>
        <h2>Shop</h2>
    </div>

    <div class="shop-container">
        <div class="filter-container">
            <h2>Filter Products</h2>
            <input type="text" id="searchInput" placeholder="Search products..." oninput="filterProducts()">
            <select id="typeFilter" onchange="filterProducts()">
                <option value="">All Types</option>
                <?php while ($row = mysqli_fetch_assoc($types)) { ?>
                    <option value="<?php echo $row['type']; ?>"><?php echo $row['type']; ?></option>
                <?php } ?>
            </select>
            <div class="price-range">
                <input type="number" id="minPrice" placeholder="Min Price" oninput="filterProducts()" min="0">
                <input type="number" id="maxPrice" placeholder="Max Price" oninput="filterProducts()" min="0">
            </div>
        </div>
        
        <div id="productContainer" class="product-container">
            <?php while ($row = mysqli_fetch_assoc($products)) { ?>
                <div class="product-card" data-name="<?php echo strtolower($row['name']); ?>" data-type="<?php echo strtolower($row['type']); ?>" data-price="<?php echo $row['price']; ?>" onclick="window.location.href='product_details.php?id=<?php echo $row['product_id']; ?>'">
                    <img src="admin/uploaded_img/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>Type: <?php echo $row['type']; ?></p>
                    <p>Price: RM<?php echo number_format($row['price'], 2); ?></p>
                    <p>Stock: <?php echo $row['quantity']; ?></p>
                    <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart(<?php echo $row['product_id']; ?>, '<?php echo $row['name']; ?>', <?php echo $row['price']; ?>, '<?php echo $row['image']; ?>')">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function filterProducts() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
            const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
            const productContainer = document.getElementById('productContainer');
            const products = productContainer.getElementsByClassName('product-card');

            for (let i = 0; i < products.length; i++) {
                const product = products[i];
                const name = product.getAttribute('data-name');
                const type = product.getAttribute('data-type');
                const price = parseFloat(product.getAttribute('data-price'));

                const isNameMatch = name.includes(searchInput) || type.includes(searchInput) || searchInput === '';
                const isTypeMatch = typeFilter === '' || type === typeFilter;
                const isPriceMatch = price >= minPrice && price <= maxPrice;

                if (isNameMatch && isTypeMatch && isPriceMatch) {
                    product.style.display = '';
                } else {
                    product.style.display = 'none';
                }
            }
        }

        function addToCart(productId, productName, price, image) {
            // Check if the user is logged in
            const isLoggedIn = <?php echo json_encode(isset($_SESSION['user_id'])); ?>; 

            if (!isLoggedIn) {
                // Show an alert if the user is not logged in
                alert("You need to be logged in to add items to your cart.");
                return;
            }

            // Proceed to add to cart (implement AJAX or form submission here)
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
                    user_id: <?php echo json_encode($_SESSION['user_id'] ?? null); ?> // Pass user_id to the server
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

        // Initialize filters
        document.addEventListener('DOMContentLoaded', () => {
            filterProducts();A
        });
    </script>
</body>
</html>
