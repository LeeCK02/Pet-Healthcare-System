<?php
session_start();
include 'config/config.php'; // Database connection
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pet Healthcare</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Include Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <style>
        .dropdown-toggle::after {
            display: none; 
        }
        .dropdown-toggle {
            font-size: 1.5rem; 
            padding: 0; 
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #000; 
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%; /* Positions dropdown below the icon */
            left: 0;
            margin-top: 0.5rem; 
            z-index: 1000;
            background: #fff;
            border-radius: 0.375rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            min-width: 160px; 
        }
        .dropdown-menu.show {
            display: block;
        }
        .dropdown-menu a {
            color: #000;
            text-decoration: none;
            padding: 0.5rem 1rem;
            display: block;
            font-size: 1rem; 
        }
        .dropdown-menu a:hover, .dropdown-menu a:focus {
            font-size: 1rem;
            background-color: transparent;
            color: blue;
        }
        .icon-container {
            display: flex;
            align-items: center;
            margin-left: 1rem;
        }
        .icon-container .dropdown {
            position: relative;
            margin-right: 10px; 
        }
    </style>
</head>
<body>
    <div class="top_header">
        <img src="img/pet_hospital_icon.jpg" alt="icon" style="height:40px; width:40px;"/>&nbsp;&nbsp;&nbsp;
        <h3 style="color:#ff0066; text-align: left;">Pet Healthcare</h3>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="appointment.php">Appointment</a>
            <a href="user_chat_list.php">Consultation</a>
            <a href="shop.php">Shop</a>
            <a href="order.php">Order</a>
        </div>
        <div class="acc_navbar" style="margin-right: 30px;">
            <?php
            if (isset($_SESSION['username'])) {
                echo '<div class="icon-container">';
                // Shopping Cart Dropdown
                echo '<div class="dropdown" style="margin-right: 10px;">';
                echo '    <a class="dropdown-toggle" href="cart.php" role="button" id="cartDropdown" data-bs-toggle="dropdown" aria-expanded="false">';
                echo '        <i class="fa fa-shopping-cart" aria-hidden="true" style="font-size: 1.5rem;"></i><span class="icon-label" style="margin-left: 5px;">Cart</span>';
                echo '    </a>';
                echo '    <ul class="dropdown-menu" aria-labelledby="cartDropdown">';
                
                // Fetch cart items from the database
                $user_id = $_SESSION['user_id'];
                $cart_items_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
                
                if ($cart_items_query) {
                    while ($item = mysqli_fetch_assoc($cart_items_query)) {
                        echo '<li class="dropdown-item">';
                        echo '<img src="admin/uploaded_img/' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['name']) . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"> ';
                        echo htmlspecialchars($item['name']) . ' (x' . htmlspecialchars($item['quantity']) . ')';
                        echo '</li>';
                    }
                    
                    if (mysqli_num_rows($cart_items_query) == 0) {
                        echo '<li class="dropdown-item">No items in cart</li>';
                    }
                } else {
                    echo '<li class="dropdown-item">Error fetching cart items</li>';
                }
                echo '<li class="dropdown-divider"></li>';
                echo '<li><a class="dropdown-item" href="cart.php">Go to Cart</a></li>';
                echo '    </ul>';
                echo '</div>';

                // Pet Profile Dropdown
                echo '<div class="dropdown">';
                echo '    <a class="dropdown-toggle" href="#" role="button" id="petProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">';
                echo '        <i class="fa fa-paw" aria-hidden="true"></i><span class="icon-label" style="margin-left: 5px;">Pet Profile</span>';
                echo '    </a>';
                echo '    <ul class="dropdown-menu" aria-labelledby="petProfileDropdown">';
                echo '        <li><a class="dropdown-item" href="pet_profile.php">Pet Profile</a></li>';
                echo '    </ul>';
                echo '</div>';
                
                // Account Dropdown
                echo '<div class="dropdown">';
                echo '    <a class="dropdown-toggle" href="#" role="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">';
                echo '        <i class="bi bi-person-circle"></i><span class="icon-label" style="margin-left: 5px;">' . htmlspecialchars($_SESSION['username']) . '</span>';
                echo '    </a>';
                echo '    <ul class="dropdown-menu" aria-labelledby="accountDropdown">';
                echo '        <li><a class="dropdown-item" href="account.php">Account</a></li>';
                echo '        <li><a class="dropdown-item" href="logout.php">Logout</a></li>';
                echo '    </ul>';
                echo '</div>';
                echo '</div>';
            } else {
                echo '<a href="login.php" class="btn"><i class="fa fa-sign-in-alt"></i><span class="icon-label" style="margin-left: 5px;">Login</span></a>';
                echo '<a href="register.php" class="btn"><i class="fa fa-user-plus"></i><span class="icon-label" style="margin-left: 5px;">Register</span></a>';
            }
            ?>
        </div>
    </div>
</body>
</html>
