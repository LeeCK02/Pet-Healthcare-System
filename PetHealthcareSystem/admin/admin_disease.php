<?php
include 'admin_header.php';

// Initialize an array to hold diagnosis counts
$diagnosis_count = [];

// Fetch diagnoses from medical_record
$query = "SELECT diagnosis FROM medical_record";
$result = mysqli_query($conn, $query);

// Process the results
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $diagnosis = strtolower(trim($row['diagnosis'])); // Normalize case
        if (!empty($diagnosis)) {
            if (isset($diagnosis_count[$diagnosis])) {
                $diagnosis_count[$diagnosis]++;
            } else {
                $diagnosis_count[$diagnosis] = 1; // Initialize count
            }
        }
    }
}

// Prepare data for the graph
$diagnoses = array_keys($diagnosis_count);
$counts = array_values($diagnosis_count);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnosis Statistics</title>
    <link rel="stylesheet" href="admin_css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        canvas {
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Diagnosis Statistics</h1>
        <canvas id="diagnosisChart" width="400" height="200"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('diagnosisChart').getContext('2d');
        const diagnosisChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($diagnoses); ?>,
                datasets: [{
                    label: 'Number of Cases',
                    data: <?php echo json_encode($counts); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    hoverBackgroundColor: 'rgba(75, 192, 192, 0.7)',
                    hoverBorderColor: 'rgba(75, 192, 192, 1)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Cases'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Diagnosis'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
</body>
</html>
