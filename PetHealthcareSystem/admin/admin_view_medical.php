<?php
include 'admin_header.php';

$pet_id = $_GET['id']; // Get the pet ID from the query parameter

// Fetch pet details
$pet_result = mysqli_query($conn, "SELECT * FROM pet WHERE pet_id = '$pet_id'");
$pet = mysqli_fetch_assoc($pet_result);

// Fetch medical records for this pet
$medical_records = mysqli_query($conn, "SELECT * FROM medical_record WHERE pet_id = '$pet_id' ORDER BY date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Medical Records</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_css/main.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .record {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
        .record:last-child {
            border-bottom: none;
        }
        .record-date {
            font-weight: bold;
            color: #4CAF50;
        }
        .record-details {
            margin-top: 10px;
            line-height: 1.5;
        }
        .admin-back-btn {
            text-align: center;
            margin-top: 20px;
        }
        .action-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
        }
        .action-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Medical Records for <?php echo htmlspecialchars($pet['name']); ?></h1>
        
        <?php if (mysqli_num_rows($medical_records) > 0): ?>
            <?php while ($record = mysqli_fetch_assoc($medical_records)): ?>
                <div class="record">
                    <p class="record-date"><?php echo date('d M Y', strtotime($record['date'])); ?></p>
                    <div class="record-details">
                        <strong>Diagnosis:</strong> <?php echo htmlspecialchars($record['diagnosis']); ?><br>
                        <strong>Treatment:</strong> <?php echo htmlspecialchars($record['treatment']); ?><br>
                        <strong>Notes:</strong> <?php echo htmlspecialchars($record['notes']); ?><br>
                        <?php if ($record['medical_report']): ?>
                            <strong>Medical Report:</strong> <a href="../vet/uploads/<?php echo htmlspecialchars($record['medical_report']); ?>" target="_blank">View Report</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No medical records found for this pet.</p>
        <?php endif; ?>

        <div class="admin-back-btn">
            <a href="admin_view_pet.php?user=<?php echo $pet['user_id']; ?>" class="action-btn">Back to Pet Profile</a>
        </div>
    </div>
</body>
</html>
