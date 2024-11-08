<?php
include 'header.php';

// Fetch the pet_id from the URL
$pet_id = $_GET['pet_id'];

// Fetch the prescription data for the given pet
$query = "SELECT * FROM prescription WHERE pet_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();

// Separate the prescriptions into active and past
$active_prescriptions = [];
$past_prescriptions = [];
$current_date = date('Y-m-d');

while ($row = $result->fetch_assoc()) {
    if ($row['end_date'] >= $current_date) {
        $active_prescriptions[] = $row;
    } else {
        $past_prescriptions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Details</title>
    <style>
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 1.5rem;
            margin-top: 30px;
            color: #007BFF;
        }
        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        .prescription-table th, .prescription-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .prescription-table th {
            background-color: #007BFF;
            color: white;
        }
        .prescription-table td {
            background-color: #fff;
        }
        .prescription-table tr:hover {
            background-color: #f1f1f1;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .btn-back {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 50px;
            text-decoration: none;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Prescription Details for Pet ID: <?php echo htmlspecialchars($pet_id); ?></h2>

    <!-- Active Prescriptions -->
    <div class="section-title">Active Prescriptions</div>
    <?php if (!empty($active_prescriptions)): ?>
        <table class="prescription-table">
            <thead>
                <tr>
                    <th>Prescription ID</th>
                    <th>Medication Name</th>
                    <th>Dosage</th>
                    <th>Frequency</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($active_prescriptions as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['prescription_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['medication_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['dosage']); ?></td>
                        <td><?php echo htmlspecialchars($row['frequency']); ?></td>
                        <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['notes']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <p>No active prescriptions found for this pet.</p>
        </div>
    <?php endif; ?>

    <!-- Past Prescriptions -->
    <div class="section-title">Past Prescriptions</div>
    <?php if (!empty($past_prescriptions)): ?>
        <table class="prescription-table">
            <thead>
                <tr>
                    <th>Prescription ID</th>
                    <th>Medication Name</th>
                    <th>Dosage</th>
                    <th>Frequency</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($past_prescriptions as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['prescription_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['medication_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['dosage']); ?></td>
                        <td><?php echo htmlspecialchars($row['frequency']); ?></td>
                        <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['notes']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <p>No past prescriptions found for this pet.</p>
        </div>
    <?php endif; ?>
</div>

<div class="text-center">
    <a href="pet_profile.php" class="btn-back">Back to Pet Profile</a>
</div>

</body>
</html>
