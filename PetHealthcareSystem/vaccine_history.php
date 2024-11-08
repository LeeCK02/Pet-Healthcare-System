<?php 
include 'header.php';
include 'config/config.php'; // Database connection

// Start output buffering
ob_start();

// Fetch pet_id from the query string
$pet_id = $_GET['pet_id'] ?? 0;

// Fetch pet details to determine the pet type
$pet_query = "SELECT * FROM pet WHERE pet_id = ?";
$pet_stmt = $conn->prepare($pet_query);
$pet_stmt->bind_param("i", $pet_id);
$pet_stmt->execute();
$pet_result = $pet_stmt->get_result();
$pet = $pet_result->fetch_assoc();
$pet_type = $pet['type'] ?? ''; // pet type

// Fetch vaccine records for the selected pet
$query = "SELECT * FROM vaccine_record WHERE pet_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();
$vaccines = $result->fetch_all(MYSQLI_ASSOC);

// Fetch Core vaccines for the corresponding pet type
$core_query = "SELECT * FROM pet_vaccine WHERE importance_level = 'Core' AND type = ?";
$core_stmt = $conn->prepare($core_query);
$core_stmt->bind_param("s", $pet_type); // Bind the pet type to the query
$core_stmt->execute();
$core_result = $core_stmt->get_result();
$core_vaccines = $core_result->fetch_all(MYSQLI_ASSOC);

// Filter recommended vaccines
$recommended_vaccines = [];
foreach ($core_vaccines as $core_vaccine) {
    $vaccine_taken = false;
    foreach ($vaccines as $vaccine) {
        if ($vaccine['vaccine_name'] === $core_vaccine['vaccine_name'] && 
            strtotime($vaccine['end_date']) > time()) {
            $vaccine_taken = true;
            break;
        }
    }
    if (!$vaccine_taken) {
        $recommended_vaccines[] = $core_vaccine;
    }
}

// End output buffering and flush output
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vaccine History</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <style>
        .main {
            padding: 50px;
            background-color: #eef2f3;
        }
        .vaccine-table {
            margin-top: 30px;
        }
        .vaccine-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: white;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }
        .vaccine-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .vaccine-card h5 {
            color: #0056b3;
        }
        .due-date {
            position: absolute;
            top: 15px;
            right: 15px;
            font-weight: bold;
            color: red;
        }
        .due-soon {
            color: #ff5733; /* Red for urgent */
        }
        .btn-back {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            text-decoration: none;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
        .recommendation {
            margin-top: 50px;
            margin-bottom: 30px;
            background-color: #d1ecf1;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="main">
        <h2 class="text-center mb-4">Vaccine History</h2>
        
        <div class="row vaccine-table">
            <?php if (count($vaccines) > 0): ?>
                <?php foreach ($vaccines as $vaccine): ?>
                    <div class="col-md-4 mb-4">
                        <div class="vaccine-card">
                            <h5><?php echo htmlspecialchars($vaccine['vaccine_name']); ?></h5>
                            <p><strong>Record ID:</strong> <?php echo htmlspecialchars($vaccine['vaccine_record_id']); ?></p>
                            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($vaccine['start_date']); ?></p>
                            <p><strong>End Date:</strong> <?php echo htmlspecialchars($vaccine['end_date']); ?></p>
                            
                            <?php 
                            // Calculate the due date
                            $due_date = date('Y-m-d', strtotime($vaccine['end_date']));
                            $days_left = (strtotime($due_date) - time()) / (60 * 60 * 24);
                            ?>
                            <div class="due-date <?php echo $days_left <= 30 ? 'due-soon' : ''; ?>">
                                Due in <?php echo max(0, ceil($days_left)); ?> days
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center col-12">No vaccine records found for this pet.</p>
            <?php endif; ?>
        </div>

        <!-- Recommendations Section -->
        <div class="recommendation">
            <h4>Recommended Vaccines (Core Importance)</h4>
            <ul>
                <?php if (count($recommended_vaccines) > 0): ?>
                    <?php foreach ($recommended_vaccines as $rec_vaccine): ?>
                        <li style="margin-bottom: 20px;">
                            <strong><?php echo htmlspecialchars($rec_vaccine['vaccine_name']); ?></strong>: 
                            <div><?php echo nl2br(htmlspecialchars($rec_vaccine['vaccine_description'])); ?></div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No core vaccines recommended at this time.</li>
                <?php endif; ?>
            </ul>
        </div>
        
        <div class="text-center">
            <a href="pet_profile.php" class="btn-back">Back to Pet Profile</a>
        </div>
    </div>

    <?php 
    include 'footer.php'; 
    ?>
</body>
</html>
