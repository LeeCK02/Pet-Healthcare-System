<?php
include 'vet_header.php';

// Get the room ID from the URL
$room_id = isset($_GET['room_id']) ? $_GET['room_id'] : 0;

// Fetch ongoing hospitalizations for the selected room
$query = "SELECT h.*, p.name AS pet_name, u.username AS user_name
          FROM hospitalization h
          JOIN pet p ON h.pet_id = p.pet_id
          JOIN user u ON p.user_id = u.user_id
          WHERE h.room_id = '$room_id' AND h.status = 'ongoing'";
$result = mysqli_query($conn, $query);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hosp_id']) && isset($_POST['status'])) {
    $hosp_id = $_POST['hosp_id'];
    $new_status = $_POST['status'];

    // Check the current hospitalization status before updating
    $hosp_query = "SELECT room_id, status FROM hospitalization WHERE hosp_id = '$hosp_id'";
    $hosp_result = mysqli_query($conn, $hosp_query);
    $hospitalization = mysqli_fetch_assoc($hosp_result);

    // Update the hospitalization status
    $update_query = "UPDATE hospitalization SET status = '$new_status' WHERE hosp_id = '$hosp_id'";
    if (mysqli_query($conn, $update_query)) {
        // If the status is changed to "completed", update the room's remaining space
        if ($new_status == 'completed' && $hospitalization['status'] != 'completed') {
            $room_id = $hospitalization['room_id']; // Get the room ID associated with the hospitalization

            // Increment the remaining space for the room
            $increment_query = "UPDATE room SET space_remaining = space_remaining + 1 WHERE room_id = '$room_id'";
            mysqli_query($conn, $increment_query);
        }
        echo "<script>alert('Status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating status: " . mysqli_error($conn) . "');</script>";
    }

    // Refresh the hospitalization list after update
    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ongoing Hospitalizations for Room <?php echo $room_id; ?></title>
    <style>
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1rem;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        .btn-add {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }
        .btn-add:hover {
            background-color: #0056b3;
        }
        .status-select {
            min-width: 120px;
        }
        .btn-update {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-update:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Ongoing Hospitalizations for Room <?php echo $room_id; ?></h1>

    <a href="add_hospitalization.php?room_id=<?php echo $room_id; ?>" class="btn-add">Add Hospitalization</a>

    <table>
        <thead>
            <tr>
                <th>Hospitalization ID</th>
                <th>Pet Name</th>
                <th>Owner Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Treatment</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($hospitalization = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $hospitalization['hosp_id']; ?></td>
                    <td><?php echo $hospitalization['pet_name']; ?></td>
                    <td><?php echo $hospitalization['user_name']; ?></td>
                    <td><?php echo $hospitalization['start_date']; ?></td>
                    <td><?php echo $hospitalization['end_date']; ?></td>
                    <td><?php echo $hospitalization['reason']; ?></td>
                    <td><?php echo $hospitalization['treatment']; ?></td>
                    <td><?php echo $hospitalization['status']; ?></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="hosp_id" value="<?php echo $hospitalization['hosp_id']; ?>">
                            <select name="status" class="status-select" required>
                                <option value="ongoing" <?php echo ($hospitalization['status'] == 'ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                                <option value="completed" <?php echo ($hospitalization['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                            <button type="submit" class="btn-update">Update</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
