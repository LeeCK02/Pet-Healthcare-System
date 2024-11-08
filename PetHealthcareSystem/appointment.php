<?php 
ob_start(); // Start output buffering
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Show an alert and redirect using JavaScript
    echo "<script>
            alert('You must be logged in to access this page.');
            window.location.href = 'login.php'; // Redirect to the login page
          </script>";
    exit();
}

date_default_timezone_set('Asia/Kuala_Lumpur');

$user_id = $_SESSION['user_id'];

// Fetch veterinary list
$vetsQuery = mysqli_query($conn, "SELECT vet_id, name FROM veterinary");
$vets = [];
while ($row = mysqli_fetch_assoc($vetsQuery)) {
    $vets[$row['vet_id']] = $row['name'];
}

// Initialize the appointments array
$appointments = [
    'ongoing' => [],
    'cancelled' => [],
    'completed' => []
];

// Fetch appointments based on status
$statuses = ['Pending' => 'ongoing', 'Cancelled' => 'cancelled', 'Completed' => 'completed'];

foreach ($statuses as $status => $section) {
    $result = mysqli_query($conn, "SELECT a.*, v.name AS vet_name FROM appointment a JOIN veterinary v ON a.vet_id = v.vet_id WHERE a.user_id = '$user_id' AND a.status = '$status' ORDER BY a.date DESC, a.time DESC");
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[$section][] = $row;
    }
}

if (isset($_POST['cancel_appointment'])) {
    $appointment_id = $_POST['appointment_id'];

    // Fetch the appointment details to check the date
    $appointmentQuery = mysqli_query($conn, "SELECT date FROM appointment WHERE appointment_id = '$appointment_id'");
    $appointment = mysqli_fetch_assoc($appointmentQuery);

    // Calculate the difference in days between today and the appointment date
    $appointmentDate = new DateTime($appointment['date']);
    $currentDate = new DateTime();
    $interval = $currentDate->diff($appointmentDate);
    $daysRemaining = $interval->days;

    if ($appointmentDate >= $currentDate || $daysRemaining >= 3) {
        $update = "UPDATE appointment SET status = 'Cancelled' WHERE appointment_id = '$appointment_id'";
        $upload = mysqli_query($conn, $update);

        if ($upload) {
            $message[] = 'Appointment cancelled successfully!';
            header('Location: appointment.php'); // Refresh the page to show updated data
            ob_end_flush(); // Flush output buffer and turn off output buffering
            exit;
        } else {
            echo "<script>alert('Could not cancel the appointment. Please try again');</script>";
        }
    } else {
        echo "<script>alert('You can only cancel appointments at least 3 days in advance');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        h2 {
            color: #0047ab;
            text-align: center;
            font-weight: bold;
            margin-top: 50px;
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
            margin-top: 20px;
        }

        .tab-content.active {
            display: block;
        }

        .appointment-card {
            position: relative;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .appointment-card .appointment-date-time {
            position: absolute;
            right: 15px;
            top: 30px;
            font-size: 18px;
            background: #7D5CCF;
            background: radial-gradient(circle farthest-side at center center, #7D5CCF 0%, #CF0000 99%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
            text-align: right;
        }

        .appointment-card h3 {
            margin-top: 10px;
            font-size: 18px;
            color: #0047ab;
        }

        .appointment-card p {
            margin: 20px 0;
        }

        .appointment-card .btn {
            background-color: #ff4c4c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .appointment-card .btn:hover {
            background-color: #e03c3c;
        }

        .btn-book-appointment {
            display: block;
            width: 200px;
            margin: 20px auto;
            background-color: #0047ab;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-book-appointment:hover {
            background-color: #00327a;
        }

        .button-container {
            text-align: center;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .button-container a {
            background-color: #0047ab;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .button-container a:hover {
            background-color: #00327a;
        }

        .overdue {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }

        .chat-btn {
            background-color: #00b300;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .chat-btn:hover {
            background-color: #008000;
        }
    </style>
</head>
<body>

<div>
    <h2>Your Appointments</h2>
    <div class="button-container">
        <a href="appointment_booking.php" class="btn-book-appointment">Book Appointment</a>
    </div>
</div>

<div class="container">
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
                    <div class="appointment-info">
                        <h3>Appointment on <?php echo date('d M Y', strtotime($appointment['date'])); ?> at <?php echo date('H:i', strtotime($appointment['time'])); ?></h3>
                        <p><strong>Notes:</strong> <?php echo htmlspecialchars($appointment['notes']); ?></p>
                        <p><strong>Veterinarian:</strong> <?php echo htmlspecialchars($appointment['vet_name']); ?></p>
                        <?php if ($isOverdue) { ?>
                            <p class="overdue">Overdue</p>
                        <?php } ?>
                    </div>
                    <div class="appointment-date-time">
                        <p><?php echo date('d M Y', strtotime($appointment['date'])); ?></p>
                        <p><?php echo date('H:i', strtotime($appointment['time'])); ?></p>
                    </div>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="appointment_id" value="<?php echo $appointment['appointment_id']; ?>">
                        <input type="submit" name="cancel_appointment" value="Cancel Appointment" class="btn" onclick="return confirm('Are you sure you want to cancel this appointment?');">
                    </form>
                    <a href="user_chat.php?vet_id=<?php echo $appointment['vet_id']; ?>" class="chat-btn">Chat with Vet</a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No ongoing appointments.</p>
        <?php } ?>
    </div>

    <div class="tab-content" id="cancelled">
        <?php if (!empty($appointments['cancelled'])) { ?>
            <?php foreach ($appointments['cancelled'] as $appointment) { ?>
                <div class="appointment-card">
                    <h3>Appointment on <?php echo date('d M Y', strtotime($appointment['date'])); ?> at <?php echo date('H:i', strtotime($appointment['time'])); ?></h3>
                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($appointment['notes']); ?></p>
                    <p><strong>Veterinarian:</strong> <?php echo htmlspecialchars($appointment['vet_name']); ?></p>
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
                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($appointment['notes']); ?></p>
                    <p><strong>Veterinarian:</strong> <?php echo htmlspecialchars($appointment['vet_name']); ?></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No completed appointments.</p>
        <?php } ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = this.getAttribute('data-target');

            // Deactivate all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Hide all tab contents
            tabContents.forEach(content => content.classList.remove('active'));

            // Activate the clicked tab
            this.classList.add('active');
            // Show the corresponding tab content
            document.getElementById(target).classList.add('active');
        });
    });
});
</script>

</body>
</html>
