<?php
include 'admin_header.php';

// Fetch pet data from the database
$pet_query = mysqli_query($conn, "SELECT type, COUNT(*) AS count FROM pet GROUP BY type");
$pet_counts = [];

while ($row = mysqli_fetch_assoc($pet_query)) {
    $pet_counts[$row['type']] = (int)$row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        h1, h2 {
            text-align: center;
            color: #333;
        }
        #petChart {
            width: 60%;
            height: 400px;
            margin: auto;
        }
    </style>
</head>
<body>
    <h1>Pet Type Statistics</h1>
    
    <h2>Number of Pets by Type</h2>
    <canvas id="petChart"></canvas>

    <script>
        const ctx = document.getElementById('petChart').getContext('2d');
        const petData = {
            labels: ['Dog', 'Cat', 'Other'],
            datasets: [{
                label: 'Number of Pets',
                data: [
                    <?php echo isset($pet_counts['Dog']) ? $pet_counts['Dog'] : 0; ?>,
                    <?php echo isset($pet_counts['Cat']) ? $pet_counts['Cat'] : 0; ?>,
                    <?php echo isset($pet_counts['Other']) ? $pet_counts['Other'] : 0; ?>
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Create the chart
        const petChart = new Chart(ctx, {
            type: 'bar',
            data: petData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
