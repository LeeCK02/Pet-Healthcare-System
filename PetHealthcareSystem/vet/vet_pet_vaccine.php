<?php
include 'vet_header.php';

$pet_id = $_GET['id']; // Get the pet ID from the query parameter

// Fetch the pet's details
$pet_result = mysqli_query($conn, "SELECT * FROM pet WHERE pet_id = '$pet_id'");
$pet = mysqli_fetch_assoc($pet_result);
$pet_name = htmlspecialchars($pet['name']);
$pet_type = htmlspecialchars($pet['type']); // Get pet type

// Fetch vaccine records for this pet
$vaccine_records = mysqli_query($conn, "SELECT * FROM vaccine_record WHERE pet_id = '$pet_id'");

// Fetch vaccines available for the pet type
$vaccines = mysqli_query($conn, "SELECT * FROM pet_vaccine WHERE type = '$pet_type'");

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_vaccine'])) {
        // Add Vaccine
        $vaccine_name = $_POST['vaccine_name'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Check if a vaccine with the same name exists within the given time period
        $checkQuery = "SELECT * FROM vaccine_record WHERE pet_id = '$pet_id' AND vaccine_name = '$vaccine_name' 
                       AND (start_date <= '$end_date' AND end_date >= '$start_date')";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            echo "<script>alert('This vaccine already exists for the given time period.');</script>";
        } else {
            $insertQuery = "INSERT INTO vaccine_record (pet_id, vaccine_name, start_date, end_date) 
                            VALUES ('$pet_id', '$vaccine_name', '$start_date', '$end_date')";
            $insertResult = mysqli_query($conn, $insertQuery);

            if ($insertResult) {
                echo "<script>
                        alert('Vaccine added successfully!');
                        window.location.href = window.location.href;
                      </script>";
            } else {
                echo "<script>alert('Failed to add vaccine. Please try again.');</script>";
            }
        }
    } elseif (isset($_POST['edit_vaccine'])) {
        // Edit Vaccine
        $vaccine_record_id = $_POST['record_id'];
        $vaccine_name = $_POST['vaccine_name'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Check if there are any other records with the same name and overlapping time range
        $checkQuery = "SELECT * FROM vaccine_record 
                       WHERE pet_id = '$pet_id' AND vaccine_name = '$vaccine_name' 
                       AND (start_date <= '$end_date' AND end_date >= '$start_date')
                       AND vaccine_record_id != '$vaccine_record_id'"; // Exclude the current record
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            echo "<script>alert('This vaccine already exists for the given time period.');</script>";
        } else {
            $updateQuery = "UPDATE vaccine_record SET vaccine_name = '$vaccine_name', start_date = '$start_date', end_date = '$end_date' 
                            WHERE vaccine_record_id = '$vaccine_record_id'";
            if (mysqli_query($conn, $updateQuery)) {
                echo "<script>
                        alert('Vaccine record updated successfully!');
                        window.location.href = window.location.href;
                      </script>";
            } else {
                echo "<script>alert('Failed to update vaccine record. Please try again.');</script>";
            }
        }
    }
}

// Handle Delete Request
if (isset($_GET['delete'])) {
    $record_id = $_GET['delete'];
    $deleteQuery = "DELETE FROM vaccine_record WHERE vaccine_record_id = '$record_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "<script>
                alert('Vaccine record deleted successfully!');
                window.location.href = 'vet_pet_vaccine.php?id=$pet_id';
              </script>";
    } else {
        echo "<script>alert('Failed to delete vaccine record. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pet_name; ?>'s Vaccine History</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_css/main.css">
    <style>
        .vaccine-history {
            margin: 2rem 0;
            width: 80%;
            margin: 0 auto;
        }
        .vaccine-history h1 {
            margin-top: 30px;
            margin-bottom: 20px;
            text-align: center;
        }
        .vaccine-history-table {
            width: 100%;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
        }
        .vaccine-history-table thead {
            background: #4CAF50;
            color: white;
        }
        .vaccine-history-table th, .vaccine-history-table td {
            padding: 1rem;
            border-bottom: 1px solid #ccc;
        }
        .action-btn, .edit-btn, .delete-btn {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .edit-btn {
            background-color: #4CAF50;
        }
        .edit-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: red;
            text-decoration: none !important;
        }
        .delete-btn:hover {
            background-color: darkred;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .close-btn {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }
        .close-btn:hover {
            color: black;
        }
        .modal-form {
            margin-top: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group select, .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="vaccine-history">
        <h1><?php echo $pet_name; ?>'s Vaccine History</h1>
        <table class="vaccine-history-table">
            <thead>
                <tr>
                    <th>Vaccine Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($vaccine_records)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                    <td>
                        <button class="edit-btn" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                        <a href="vet_pet_vaccine.php?id=<?php echo $pet_id; ?>&delete=<?php echo $row['vaccine_record_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Add Vaccine Form -->
        <div class="add-vaccine-form">
            <h2>Add Vaccine Record</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="vaccine_name">Vaccine Name</label>
                    <select id="vaccine_name" name="vaccine_name" required>
                        <option value="">Select a vaccine</option>
                        <?php while ($vaccine = mysqli_fetch_assoc($vaccines)) { ?>
                            <option value="<?php echo htmlspecialchars($vaccine['vaccine_name']); ?>">
                                <?php echo htmlspecialchars($vaccine['vaccine_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" required>
                </div>
                <input type="hidden" name="pet_id" value="<?php echo $pet_id; ?>">
                <button type="submit" name="add_vaccine" class="submit-btn">Add Vaccine</button>
            </form>
        </div>

        <!-- Edit Vaccine Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeEditModal()">&times;</span>
                <h2>Edit Vaccine Record</h2>
                <form method="POST" action="" class="modal-form">
                    <input type="hidden" id="record_id" name="record_id">
                    <div class="form-group">
                        <label for="edit_vaccine_name">Vaccine Name</label>
                        <select id="edit_vaccine_name" name="vaccine_name" required>
                            <option value="">Select a vaccine</option>
                            <?php
                            mysqli_data_seek($vaccines, 0); // Reset the pointer to the start
                            while ($vaccine = mysqli_fetch_assoc($vaccines)) { ?>
                                <option value="<?php echo htmlspecialchars($vaccine['vaccine_name']); ?>">
                                    <?php echo htmlspecialchars($vaccine['vaccine_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_start_date">Start Date</label>
                        <input type="date" id="edit_start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_end_date">End Date</label>
                        <input type="date" id="edit_end_date" name="end_date" required>
                    </div>
                    <button type="submit" name="edit_vaccine" class="submit-btn">Update Vaccine</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(record) {
            document.getElementById('record_id').value = record.vaccine_record_id;
            document.getElementById('edit_vaccine_name').value = record.vaccine_name;
            document.getElementById('edit_start_date').value = record.start_date;
            document.getElementById('edit_end_date').value = record.end_date;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Date validation for add and edit forms
        document.getElementById('end_date').addEventListener('change', validateDates);
        document.getElementById('edit_end_date').addEventListener('change', validateDates);

        function validateDates() {
            const startDate = new Date(document.getElementById('start_date').value || document.getElementById('edit_start_date').value);
            const endDate = new Date(this.value);
            if (endDate < startDate) {
                alert('End date cannot be earlier than start date.');
                this.value = ''; // Clear the invalid end date
            }
        }
    </script>
</body>
</html>
