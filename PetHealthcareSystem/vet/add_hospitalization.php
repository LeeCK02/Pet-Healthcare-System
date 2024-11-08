<?php
include 'vet_header.php';

// Check if room_id is set in the URL
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
}

// Fetch all users
$user_query = "SELECT * FROM user";
$user_result = mysqli_query($conn, $user_query);

// Fetch pets based on selected user (if available)
$pet_result = [];

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $pet_query = "SELECT * FROM pet WHERE user_id = '$user_id'";
    $pet_result = mysqli_query($conn, $pet_query);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pet_id'])) {
    $pet_id = $_POST['pet_id'];
    
    // Get the dates as YYYY-MM-DD
    $start_date = date('Y-m-d', strtotime($_POST['start_date']));
    $end_date = date('Y-m-d', strtotime($_POST['end_date']));
    $reason = $_POST['reason'];
    $treatment = $_POST['treatment'];
    $status = $_POST['status'];

    // Check for existing hospitalization records for the same pet in the selected date range
    $check_query = "SELECT * FROM hospitalization WHERE pet_id = '$pet_id' 
                    AND ((start_date <= '$end_date' AND end_date >= '$start_date'))";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Pet is already hospitalized in the selected date range
        echo "<script>alert('Error: This pet is already hospitalized during the selected dates.');</script>";
    } else {
        // Check the remaining space in the room
        $room_check_query = "SELECT space_remaining FROM room WHERE room_id = '$room_id'";
        $room_check_result = mysqli_query($conn, $room_check_query);
        $room_data = mysqli_fetch_assoc($room_check_result);

        // Check if the room has remaining space
        if ($room_data['space_remaining'] <= 0) {
            echo "<script>alert('Error: No remaining space in the selected room.');</script>";
        } else {
            // Insert hospitalization if no duplication found and space is available
            $insert_query = "INSERT INTO hospitalization (pet_id, room_id, start_date, end_date, reason, treatment, status) 
                             VALUES ('$pet_id', '$room_id', '$start_date', '$end_date', '$reason', '$treatment', '$status')";
            if (mysqli_query($conn, $insert_query)) {
                // Decrement the remaining space in the room
                $update_room_query = "UPDATE room SET space_remaining = space_remaining - 1 WHERE room_id = '$room_id'";
                mysqli_query($conn, $update_room_query);
                
                // Success alert and redirection
                echo "<script>
                        alert('Hospitalization added successfully!');
                        window.location.href = 'vet_hospitalization.php?room_id=$room_id';
                      </script>";
            } else {
                echo "<script>alert('Error adding hospitalization: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hospitalization</title>
    <style>
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        form {
            margin: 0 auto;
            font-size: 1rem;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        select, input[type="text"], input[type="date"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-submit {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function fetchPets() {
            var user_id = document.getElementById('user_id').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'fetch_pets.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('petSelect').innerHTML = xhr.responseText;
                }
            };
            xhr.send('user_id=' + user_id);
        }

        function validateDates() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                alert('End date cannot be before start date.');
                document.getElementById('end_date').value = ''; // Clear end date
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Add Hospitalization</h1>

    <form action="" method="POST" onsubmit="return validateDates();">
        <label for="user_id">Select Owner:</label>
        <select name="user_id" id="user_id" onchange="fetchPets()">
            <option value="">-- Select User --</option>
            <?php while ($user = mysqli_fetch_assoc($user_result)) { ?>
                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['username']; ?></option>
            <?php } ?>
        </select>

        <label for="pet_id">Select Pet:</label>
        <select name="pet_id" id="petSelect">
            <option value="">-- Select Pet --</option>
            <!-- Pet options will be dynamically populated via AJAX -->
        </select>

        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" required>

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" required>

        <label for="reason">Reason:</label>
        <textarea name="reason" id="reason" rows="3" required></textarea>

        <label for="treatment">Treatment:</label>
        <textarea name="treatment" id="treatment" rows="3" required></textarea>

        <input type="hidden" name="status" value="ongoing">

        <button type="submit" class="btn-submit">Add Hospitalization</button>
    </form>
</div>

</body>
</html>
