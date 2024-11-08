<?php
include 'vet_header.php';

date_default_timezone_set('Asia/Kuala_Lumpur');

$vet_id = $_SESSION['vet_id']; 

// Fetch appointments for this veterinarian, including user details
$appointments = [
    'ongoing' => [],
    'cancelled' => [],
    'completed' => []
];

$statuses = ['Pending' => 'ongoing', 'Cancelled' => 'cancelled', 'Completed' => 'completed'];

foreach ($statuses as $status => $section) {
    $result = mysqli_query($conn, "SELECT a.*, u.user_id, u.username, u.email 
                                   FROM appointment a 
                                   JOIN user u ON a.user_id = u.user_id 
                                   WHERE a.vet_id = '$vet_id' AND a.status = '$status' 
                                   ORDER BY a.date DESC, a.time DESC");
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[$section][] = $row;
    }
}

// Handle reschedule appointment
if (isset($_POST['reschedule_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_date = $_POST['new_date'];
    $new_time = $_POST['new_time'];

    // Check if the new time slot is already booked for ongoing appointments
    $checkSlot = mysqli_query($conn, "SELECT * FROM appointment WHERE vet_id = '$vet_id' AND date = '$new_date' AND time = '$new_time' AND status = 'Pending'");
    if (mysqli_num_rows($checkSlot) > 0) {
        echo "<script>alert('This time slot is already booked. Please choose a different time.');</script>";
    } else {
        $update = "UPDATE appointment SET date = '$new_date', time = '$new_time', status = 'Pending' WHERE appointment_id = '$appointment_id'";
        $upload = mysqli_query($conn, $update);

        if ($upload) {
            echo "<script>alert('Appointment rescheduled successfully!'); window.location.href = 'vet_appointment.php';</script>";
        } else {
            echo "<script>alert('Could not reschedule the appointment. Please try again');</script>";
        }
    }
}

// Handle cancel appointment
if (isset($_POST['cancel_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $update = "UPDATE appointment SET status = 'Cancelled' WHERE appointment_id = '$appointment_id'";
    $upload = mysqli_query($conn, $update);

    if ($upload) {
        echo "<script>alert('Appointment cancelled successfully!'); window.location.href = 'vet_appointment.php';</script>";
    } else {
        echo "<script>alert('Could not cancel the appointment. Please try again');</script>";
    }
}

// Handle set appointment to completed
if (isset($_POST['complete_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $update = "UPDATE appointment SET status = 'Completed' WHERE appointment_id = '$appointment_id'";
    $upload = mysqli_query($conn, $update);

    if ($upload) {
        echo "<script>alert('Appointment marked as completed!'); window.location.href = 'vet_appointment.php';</script>";
    } else {
        echo "<script>alert('Could not update the appointment. Please try again');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veterinarian Appointments</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #0047ab;
            text-align: center;
            font-weight: bold;
            margin-top: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #0047ab;
            margin-bottom: 20px;
        }

        .tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
            background-color: #e0e0e0;
            color: #333;
        }

        .tab.active {
            background-color: #0047ab;
            color: white;
            font-weight: bold;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .appointment-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .appointment-card h3 {
            font-size: 18px;
            color: #0047ab;
        }

        .appointment-card p {
            margin: 5px 0;
        }

        .overdue {
            color: red;
            font-weight: bold;
            font-size: 16px;
            text-align: right;
        }

        .button-container {
            text-align: right;
            margin-top: 10px;
        }

        .btn {
            background-color: #0047ab;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-right: 5px;
        }

        .btn:hover {
            background-color: #00327a;
        }

        .search-bar {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        /* Modal styles for rescheduling */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 90%;
            height: 500px;
            max-width: 600px;
        }

        .modal.active {
            display: block;
        }

        .modal .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
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
    </style>
</head>
<body>

<div class="container">
    <h2>Veterinarian Appointments</h2>

    <input type="text" id="searchBar" class="search-bar" placeholder="Search by username, email, or dates...">

    <div class="tabs">
        <div class="tab active" data-target="ongoing">Ongoing</div>
        <div class="tab" data-target="cancelled">Cancelled</div>
        <div class="tab" data-target="completed">Completed</div>
    </div>

    <div class="tab-content active" id="ongoing">
        <?php if (!empty($appointments['ongoing'])) { ?>
            <?php foreach ($appointments['ongoing'] as $appointment) { 
                $appointmentDate = new DateTime($appointment['date']);
                $today = new DateTime();
                $isOverdue = $appointmentDate < $today;
            ?>
                <div class="appointment-card">
                    <h3>Appointment on <?php echo date('d M Y', strtotime($appointment['date'])); ?> at <?php echo date('H:i', strtotime($appointment['time'])); ?></h3>
                    <?php if ($isOverdue) { ?>
                        <p class="overdue">Overdue</p>
                    <?php } ?>
                    <p><strong>User ID:</strong> <?php echo htmlspecialchars($appointment['user_id']); ?></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($appointment['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($appointment['email']); ?></p>
                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($appointment['notes']); ?></p>
                    <div class="button-container">
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['appointment_id']; ?>">
                            <button type="submit" name="cancel_appointment" class="btn" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</button>
                            <button type="submit" name="complete_appointment" class="btn" onclick="return confirm('Are you sure you want to mark this appointment as completed?');">Complete</button>
                        </form>
                        <button class="btn reschedule-btn" data-id="<?php echo $appointment['appointment_id']; ?>">Reschedule</button>
                        <!-- Chat Button -->
                        <a href="vet_chat.php?user_id=<?php echo $appointment['user_id']; ?>" class="btn">Chat</a>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No ongoing appointments.</p>
        <?php } ?>
    </div>

    <!-- Sections for Cancelled and Completed -->
    <div class="tab-content" id="cancelled">
        <?php if (!empty($appointments['cancelled'])) { ?>
            <?php foreach ($appointments['cancelled'] as $appointment) { ?>
                <div class="appointment-card">
                    <h3>Appointment on <?php echo date('d M Y', strtotime($appointment['date'])); ?> at <?php echo date('H:i', strtotime($appointment['time'])); ?></h3>
                    <p><strong>User ID:</strong> <?php echo htmlspecialchars($appointment['user_id']); ?></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($appointment['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($appointment['email']); ?></p>
                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($appointment['notes']); ?></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No cancelled appointments.</p>
        <?php } ?>
    </div>

    <div class="tab-content" id="completed">
        <?php if (!empty($appointments['completed'])) { ?>
            <?php foreach ($appointments['completed'] as $appointment) { ?>
                <div class="appointment-card">
                    <h3>Appointment on <?php echo date('d M Y', strtotime($appointment['date'])); ?> at <?php echo date('H:i', strtotime($appointment['time'])); ?></h3>
                    <p><strong>User ID:</strong> <?php echo htmlspecialchars($appointment['user_id']); ?></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($appointment['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($appointment['email']); ?></p>
                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($appointment['notes']); ?></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No completed appointments.</p>
        <?php } ?>
    </div>
</div>

<!-- Reschedule Modal -->
<div class="modal" id="rescheduleModal">
    <span class="close">&times;</span>
    <h2>Reschedule Appointment</h2>
    <form method="post">
        <input type="hidden" name="appointment_id" id="reschedule_appointment_id">
        <label for="new_date">New Date</label>
        <input type="date" id="new_date" name="new_date" required min="<?php echo date('Y-m-d'); ?>">

        <label for="new_time">Select Time</label>
        <div class="time-slots">
            <?php 
            for ($hour = 9; $hour <= 21; $hour++) { 
                $timeFormatted = sprintf('%02d:00', $hour);
                echo "<button type='button' class='time-button' data-time='{$timeFormatted}'>{$timeFormatted}</button>";
            } 
            ?>
        </div>
        <input type="hidden" id="new_time" name="new_time" required>

        <button type="submit" name="reschedule_appointment" class="btn">Reschedule</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    const searchBar = document.getElementById('searchBar');
    const rescheduleButtons = document.querySelectorAll('.reschedule-btn');
    const modal = document.getElementById('rescheduleModal');
    const closeModal = document.querySelector('.modal .close');
    const dateInput = document.getElementById('new_date');
    const timeButtons = document.querySelectorAll('.time-button');
    const timeInput = document.getElementById('new_time');

    // Tab switching
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(target).classList.add('active');
        });
    });

    // Search functionality
    searchBar.addEventListener('input', function() {
        const filter = searchBar.value.toLowerCase();
        document.querySelectorAll('.appointment-card').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Open reschedule modal
    rescheduleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const appointmentId = this.getAttribute('data-id');
            document.getElementById('reschedule_appointment_id').value = appointmentId;
            modal.classList.add('active');
            updateAvailableTimes();
        });
    });

    // Close reschedule modal
    closeModal.addEventListener('click', () => {
        modal.classList.remove('active');
    });

    window.addEventListener('click', event => {
        if (event.target === modal) {
            modal.classList.remove('active');
        }
    });

    // Update available times
    function updateAvailableTimes() {
        const date = dateInput.value;
        if (date) {
            fetch(`check_slots.php?date=${date}&vet_id=<?php echo $vet_id; ?>`)
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
        }
    }

    dateInput.addEventListener('change', updateAvailableTimes);

    // Time slot selection
    timeButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.classList.contains('disabled')) return;
            timeButtons.forEach(btn => btn.classList.remove('selected'));
            this.classList.add('selected');
            timeInput.value = this.getAttribute('data-time');
        });
    });
});
</script>

</body>
</html>
