<?php
include 'vet_header.php';

$pet_id = $_GET['id']; // Get the pet ID from the query parameter
$user_id = $_GET['user']; // Get the user ID from the query parameter

// Fetch pet details
$pet_result = mysqli_query($conn, "SELECT * FROM pet WHERE pet_id = '$pet_id'");
$pet = mysqli_fetch_assoc($pet_result);

// Fetch medical records for this pet
$medical_records = mysqli_query($conn, "SELECT * FROM medical_record WHERE pet_id = '$pet_id' ORDER BY date DESC");

// Handle Delete Request
if (isset($_GET['delete'])) {
    $record_id = $_GET['delete'];
    $deleteQuery = "DELETE FROM medical_record WHERE medical_id = '$record_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>
                alert('Medical record deleted successfully!');
                window.location.href = 'vet_view_medical.php?id=$pet_id&user=$user_id';
              </script>";
    } else {
        echo "<script>alert('Failed to delete medical record. Please try again.');</script>";
    }
}
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
        .action-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            width: 100%;
            margin-top: 10px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
        }
        .action-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            width: 20%;
            background-color: red;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
        .back-btn {
            text-align: center;
            margin-top: 20px;
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
                            <strong>Medical Report:</strong> <a href="uploads/<?php echo htmlspecialchars($record['medical_report']); ?>" target="_blank">View Report</a><br>
                        <?php endif; ?>
                        <a href="vet_view_medical.php?id=<?php echo $pet_id; ?>&user=<?php echo $user_id; ?>&delete=<?php echo $record['medical_id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this medical record?');">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No medical records found for this pet.</p>
        <?php endif; ?>

        <!-- Add Medical Report Button -->
        <a href="vet_medical.php?id=<?php echo $pet_id; ?>&user=<?php echo $user_id; ?>" class="action-btn">Add Medical Records</a>
        
        <div class="back-btn">
            <a href="vet_view_pet.php?user=<?php echo $user_id; ?>" class="action-btn" style="background-color: #f44336;">Back to Pet Profile</a>
        </div>
    </div>
</body>
</html>
