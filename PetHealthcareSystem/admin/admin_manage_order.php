<?php
include 'admin_header.php';

// Fetch orders from the database
$orders = mysqli_query($conn, "SELECT * FROM `order` ORDER BY order_date DESC");

// Initialize the orders array
$orderSections = [
    'processing' => [],
    'delivering' => [],
    'delivered' => []
];

// Categorize orders into their respective sections
while ($order = mysqli_fetch_assoc($orders)) {
    if ($order['status'] === 'Processing') {
        $orderSections['processing'][] = $order;
    } elseif ($order['status'] === 'Delivering') {
        $orderSections['delivering'][] = $order;
    } elseif ($order['status'] === 'Delivered') {
        $orderSections['delivered'][] = $order;
    }
}

// Update order status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    mysqli_query($conn, "UPDATE `order` SET status = '$new_status' WHERE order_id = '$order_id'");
    echo "<script>
            alert('Order status updated successfully!');
            window.location.href = 'admin_manage_order.php';
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="admin_css/main.css">
    <style>
        h2 {
            color: #0047ab;
            text-align: center;
            font-weight: bold;
            margin-top: 50px;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #0047ab;
        }

        .tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
            background-color: #e0e0e0;
            color: #333;
        }

        .tab.active {
            background-color: #0047ab;
            color: white;
            font-weight: bold;
        }

        .tab-content {
            display: none;
            margin-top: 20px;
        }

        .tab-content.active {
            display: block;
        }

        .order-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-card h3 {
            font-size: 18px;
            color: #0047ab;
        }

        .order-card p {
            margin: 10px 0;
        }

        .status-form {
            margin-top: 15px;
        }

        .status-form select {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .status-form button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            background-color: #0047ab;
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .status-form button:hover {
            background-color: #00327a;
        }

        #searchInput {
            width: 60%;
            padding: 10px;
            margin: 20px auto;
            display: block;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div>
    <h2>Manage Orders</h2>
    <input type="text" id="searchInput" onkeyup="searchOrders()" placeholder="Search for orders...">
</div>

<div class="container">
    <div class="tabs">
        <div class="tab active" data-target="processing">Processing</div>
        <div class="tab" data-target="delivering">Delivering</div>
        <div class="tab" data-target="delivered">Delivered</div>
    </div>

    <div class="tab-content active" id="processing">
        <?php if (!empty($orderSections['processing'])) { ?>
            <?php foreach ($orderSections['processing'] as $order) { ?>
                <div class="order-card">
                    <h3>Order ID: <?php echo $order['order_id']; ?></h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>HP Number:</strong> <?php echo htmlspecialchars($order['hp_number']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                    <p><strong>Products:</strong> <?php echo htmlspecialchars($order['products']); ?></p>
                    <p><strong>Price:</strong> RM <?php echo htmlspecialchars($order['price']); ?></p>
                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                    <form method="post" class="status-form">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <select name="status" required>
                            <option value="Delivering">Delivering</option>
                            <option value="Delivered">Delivered</option>
                        </select>
                        <button type="submit" name="update_status">Update Status</button>
                    </form>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No processing orders.</p>
        <?php } ?>
    </div>

    <div class="tab-content" id="delivering">
        <?php if (!empty($orderSections['delivering'])) { ?>
            <?php foreach ($orderSections['delivering'] as $order) { ?>
                <div class="order-card">
                    <h3>Order ID: <?php echo $order['order_id']; ?></h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>HP Number:</strong> <?php echo htmlspecialchars($order['hp_number']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                    <p><strong>Products:</strong> <?php echo htmlspecialchars($order['products']); ?></p>
                    <p><strong>Price:</strong> RM <?php echo htmlspecialchars($order['price']); ?></p>
                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                    <form method="post" class="status-form">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <select name="status" required>
                            <option value="Delivered">Delivered</option>
                        </select>
                        <button type="submit" name="update_status">Update Status</button>
                    </form>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No delivering orders.</p>
        <?php } ?>
    </div>

    <div class="tab-content" id="delivered">
        <?php if (!empty($orderSections['delivered'])) { ?>
            <?php foreach ($orderSections['delivered'] as $order) { ?>
                <div class="order-card">
                    <h3>Order ID: <?php echo $order['order_id']; ?></h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>HP Number:</strong> <?php echo htmlspecialchars($order['hp_number']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                    <p><strong>Products:</strong> <?php echo htmlspecialchars($order['products']); ?></p>
                    <p><strong>Price:</strong> RM <?php echo htmlspecialchars($order['price']); ?></p>
                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                    <p><strong>Status:</strong> Delivered</p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No delivered orders.</p>
        <?php } ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('data-target');

            // Deactivate all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Hide all tab contents
            tabContents.forEach(content => content.classList.remove('active'));

            // Activate the clicked tab
            this.classList.add('active');
            // Show the corresponding tab content
            document.getElementById(target).classList.add('active');
        });
    });
});

function searchOrders() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toUpperCase();
    const orderCards = document.querySelectorAll(".order-card");

    orderCards.forEach(card => {
        const text = card.textContent || card.innerText;
        card.style.display = text.toUpperCase().indexOf(filter) > -1 ? "" : "none";
    });
}
</script>

</body>
</html>
