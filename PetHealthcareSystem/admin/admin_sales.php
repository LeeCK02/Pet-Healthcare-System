<?php
include 'admin_header.php';

// Initialize variables
$year = isset($_GET['year']) ? $_GET['year'] : '';
$month = isset($_GET['month']) ? $_GET['month'] : '';

// Fetch sales data based on filters
if ($year && $month) {
    $sales_query = mysqli_query($conn, "SELECT MONTH(order_date) AS month, SUM(price) AS total_sales 
                                         FROM `order` 
                                         WHERE YEAR(order_date) = $year AND MONTH(order_date) = $month 
                                         GROUP BY MONTH(order_date)");
} elseif ($year) {
    // Fetch total sales for each month in the selected year
    $sales_query = mysqli_query($conn, "SELECT MONTH(order_date) AS month, SUM(price) AS total_sales 
                                         FROM `order` 
                                         WHERE YEAR(order_date) = $year 
                                         GROUP BY MONTH(order_date)");
} else {
    $sales_query = mysqli_query($conn, "SELECT YEAR(order_date) AS year, SUM(price) AS total_sales 
                                         FROM `order` 
                                         GROUP BY YEAR(order_date)");
}

// Fetch sales by product with total quantity and total price
$product_sales_query = mysqli_query($conn, "SELECT products FROM `order`");
$product_sales = [];

while ($order = mysqli_fetch_assoc($product_sales_query)) {
    $products = $order['products'];
    $products_array = explode(',', $products); // Split by comma

    foreach ($products_array as $product) {
        preg_match('/(.*) \(Qty: (\d+)\)/', trim($product), $matches);
        if (count($matches) === 3) {
            $product_name = trim($matches[1]); // Product name
            $quantity = (int)$matches[2]; // Quantity

            // Calculate total price for the product
            $price_query = mysqli_query($conn, "SELECT price FROM product WHERE name = '$product_name' LIMIT 1");
            $price_row = mysqli_fetch_assoc($price_query);
            $price = $price_row ? (float)$price_row['price'] : 0; // Default to 0 if no price found
            $total_price = $quantity * $price; // Calculate total price

            if (!isset($product_sales[$product_name])) {
                $product_sales[$product_name] = ['quantity' => 0, 'total_price' => 0]; // Initialize if not set
            }
            $product_sales[$product_name]['quantity'] += $quantity; // Sum the quantities
            $product_sales[$product_name]['total_price'] += $total_price; // Sum the total price
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Analysis</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        h1, h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 60%;
            margin: auto;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        select, button {
            padding: 10px;
            margin: 5px;
        }
        #salesChart {
            width: 60%;
            height: 200px;
            margin: auto;
        }
        .flex-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Sales Analysis</h1>

    <form method="GET" action="">
        <label for="year">Select Year:</label>
        <select id="year" name="year">
            <option value="">--Select Year--</option>
            <?php
            $year_query = mysqli_query($conn, "SELECT DISTINCT YEAR(order_date) AS year FROM `order`");
            while ($year_row = mysqli_fetch_assoc($year_query)) {
                $selected = $year_row['year'] == $year ? 'selected' : '';
                echo "<option value='" . $year_row['year'] . "' $selected>" . $year_row['year'] . "</option>";
            }
            ?>
        </select>

        <label for="month">Select Month:</label>
        <select id="month" name="month">
            <option value="">--Select Month--</option>
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?php echo $m; ?>" <?php echo $m == $month ? 'selected' : ''; ?>>
                    <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                </option>
            <?php endfor; ?>
        </select>

        <button type="submit">Filter</button>
    </form>

    <h2>Sales Data</h2>
    <div class="flex-container">
        <canvas id="salesChart"></canvas>
    </div>

    <h2>Sales by Product</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Total Quantity Sold</th>
            <th>Total Price (RM)</th>
        </tr>
        <?php foreach ($product_sales as $product_name => $data): ?>
            <tr>
                <td><?php echo htmlspecialchars($product_name); ?></td>
                <td><?php echo number_format($data['quantity']); ?></td>
                <td><?php echo 'RM ' . number_format($data['total_price'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesData = {
            labels: [],
            datasets: [{
                label: 'Total Sales',
                data: [],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Populate sales data
        <?php 
        mysqli_data_seek($sales_query, 0); // Reset pointer for the sales query

        if ($year && $month) {
            // Both year and month are selected
            while ($sales = mysqli_fetch_assoc($sales_query)) {
                echo "salesData.labels.push('" . date('F', mktime(0, 0, 0, $sales['month'], 1)) . " $year');"; 
                echo "salesData.datasets[0].data.push(" . (float)$sales['total_sales'] . ");"; 
            }
        } elseif ($year) {
            // Only year is selected
            // Prepare labels for all months
            $months = range(1, 12);
            foreach ($months as $m) {
                $sales_total = 0;
                // Fetch total sales for the month
                $monthly_query = mysqli_query($conn, "SELECT SUM(price) AS total_sales 
                                                       FROM `order` 
                                                       WHERE YEAR(order_date) = $year AND MONTH(order_date) = $m");
                if ($monthly = mysqli_fetch_assoc($monthly_query)) {
                    $sales_total = (float)$monthly['total_sales'];
                }
                echo "salesData.labels.push('" . date('F', mktime(0, 0, 0, $m, 1)) . "');"; 
                echo "salesData.datasets[0].data.push($sales_total);"; 
            }
        } elseif ($month) {
            echo "alert('Please select a year for that month.');";
        } else {
            echo "alert('Please select at least a year or year & month.');";
        }
        ?>

        // Determine chart type based on selected filters
        const chartType = <?php echo ($year && !$month) ? "'line'" : "'bar'"; ?>;

        // Create the chart
        const salesChart = new Chart(ctx, {
            type: chartType,
            data: salesData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return 'RM ' + tooltipItem.raw.toFixed(2); // Format tooltip value with RM
                            }
                        }
                    }
                }
            }
        });

    </script>
</body>
</html>
