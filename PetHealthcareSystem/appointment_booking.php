<?php 
// Start output buffering to avoid "headers already sent" error
ob_start();
include 'header.php';

date_default_timezone_set('Asia/Kuala_Lumpur'); 

$user_id = $_SESSION['user_id']; 

// Fetch veterinary list
$vets = mysqli_query($conn, "SELECT * FROM veterinary");

$bookedTimes = []; // Initialize the array to store booked times

if (isset($_POST['date']) && isset($_POST['vet_id'])) {
    $selected_date = $_POST['date'];
    $selected_vet_id = $_POST['vet_id'];

    // Fetch booked times for the selected vet and date
    $result = mysqli_query($conn, "SELECT time FROM appointment WHERE vet_id = '$selected_vet_id' AND date = '$selected_date'");
    while ($row = mysqli_fetch_assoc($result)) {
        $bookedTimes[] = date('H:i', strtotime($row['time'])); // Convert to HH:MM format
    }
}

if (isset($_POST['book_appointment'])) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $notes = $_POST['notes'];
    $vet_id = $_POST['vet_id'];

    // Check if the time slot is already booked
    $checkSlot = mysqli_query($conn, "SELECT * FROM appointment WHERE vet_id = '$vet_id' AND date = '$date' AND time = '$time'");

    if (mysqli_num_rows($checkSlot) > 0) {
        $message[] = 'This time slot is already booked. Please choose a different time.';
    } else {
        // Insert the appointment
        $insert = "INSERT INTO appointment (date, time, notes, status, user_id, vet_id) 
                   VALUES ('$date', '$time', '$notes', 'Pending', '$user_id', '$vet_id')";
        $upload = mysqli_query($conn, $insert);

        if ($upload) {
            echo "<script>
                    alert('Appointment booked successfully!');
                    window.location.href = 'appointment.php'; // Redirect to the appointment page after the alert
                  </script>";
            // End output buffering and flush output
            ob_end_flush();
        } else {
            echo "<script>alert('Could not book the appointment. Please try again.');</script>";
        }
        
    }
}
?>

<style>
h1, h2 {
    color: #0047ab;
    text-align: center;
    font-weight: bold;
}

.add-box {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 30px auto;
    border: 1px solid #ddd;
}

.add-box label {
    font-size: 16px;
    margin-bottom: 8px;
    display: block;
    color: #333;
    font-weight: bold;
}

.add-box input[type="date"],
.add-box input[type="time"],
.add-box select,
.add-box textarea {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #888;
    box-sizing: border-box;
    font-size: 16px;
    background-color: #f5f5f5;
}

.add-box textarea {
    resize: vertical;
    min-height: 100px;
}

.add-box input[type="submit"] {
    background-color: #0047ab;
    color: white;
    font-size: 18px;
    font-weight: bold;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.add-box input[type="submit"]:hover {
    background-color: #00327a;
}

.time-slots {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.time-slots button {
    background-color: #0047ab;
    color: white;
    padding: 10px;
    margin: 5px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.time-slots button:hover {
    background-color: #00327a;
}

.time-slots button.disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.time-slots button.selected {
    background-color: #008000;
    font-weight: bold;
}

.message {
    text-align: center;
    font-size: 16px;
    margin-bottom: 20px;
}
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>

<div class="add-box">
    <h2>Book an Appointment</h2>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo "<div class='message'>$msg</div>";
        }
    }
    ?>

    <form id="appointment-form" action="" method="post">
        <label for="date">Date</label>
        <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo isset($_POST['date']) ? $_POST['date'] : ''; ?>">

        <label for="vet_id">Select Veterinarian</label>
        <select id="vet_id" name="vet_id" required>
            <option value="" disabled selected>Select a Veterinarian</option>
            <?php while ($vet = mysqli_fetch_assoc($vets)) { ?>
                <option value="<?php echo $vet['vet_id']; ?>" <?php echo (isset($_POST['vet_id']) && $_POST['vet_id'] == $vet['vet_id']) ? 'selected' : ''; ?>>
                    <?php echo $vet['name']; ?>
                </option>
            <?php } ?>
        </select>

        <label for="time">Select Time</label>
        <div class="time-slots">
            <?php 
            for ($hour = 9; $hour <= 21; $hour++) { 
                $timeFormatted = sprintf('%02d:00', $hour);
                $disabled = in_array($timeFormatted, $bookedTimes) ? 'disabled' : ''; // Disable button if booked
                $class = in_array($timeFormatted, $bookedTimes) ? 'disabled' : '';
                echo "<button type='button' class='time-button $class' data-time='{$timeFormatted}' $disabled>{$timeFormatted}</button>";
            } 
            ?>
        </div>
        <input type="hidden" id="time" name="time" required>

        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="4"></textarea>

        <input type="submit" name="book_appointment" value="Book Appointment">
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('appointment-form');
    const dateInput = document.getElementById('date');
    const vetSelect = document.getElementById('vet_id');
    const timeButtons = document.querySelectorAll('.time-button');

    // Function to update available time slots
    function updateAvailableTimes() {
        const date = dateInput.value;
        const vetId = vetSelect.value;

        if (date && vetId) {
            fetch(`check_slots.php?date=${date}&vet_id=${vetId}`)
                .then(response => response.json())
                .then(bookedTimes => {
                    timeButtons.forEach(button => {
                        const time = button.getAttribute('data-time');
                        if (bookedTimes.includes(time)) {
                            button.classList.add('disabled');
                            button.disabled = true;
                        } else {
                            button.classList.remove('disabled');
                            button.disabled = false;
                        }
                    });
                });
        } else {
            // If date or vet is not selected, disable all buttons
            timeButtons.forEach(button => {
                button.classList.add('disabled');
                button.disabled = true;
            });
        }
    }

    // Initial call to ensure time slots are correctly disabled when page loads
    updateAvailableTimes();

    // Reset selected time and update available times when date or vet changes
    dateInput.addEventListener('change', function() {
        document.getElementById('time').value = ''; // Reset hidden time input
        timeButtons.forEach(button => button.classList.remove('selected')); // Deselect all buttons
        updateAvailableTimes();
    });

    vetSelect.addEventListener('change', function() {
        document.getElementById('time').value = ''; // Reset hidden time input
        timeButtons.forEach(button => button.classList.remove('selected')); // Deselect all buttons
        updateAvailableTimes();
    });

    // Handle time slot selection
    timeButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.classList.contains('disabled')) {
                return; // Prevent selection of disabled buttons
            }

            // Deselect other buttons
            timeButtons.forEach(btn => btn.classList.remove('selected'));

            // Select the clicked button
            this.classList.add('selected');
            // Set the time in the hidden input field
            document.getElementById('time').value = this.getAttribute('data-time');
        });
    });
});
</script>

</body>
</html>

