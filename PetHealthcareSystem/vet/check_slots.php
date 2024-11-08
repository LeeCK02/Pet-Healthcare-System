<?php
include 'config/config.php'; // database connection

date_default_timezone_set('Asia/Kuala_Lumpur'); 

if (isset($_GET['date']) && isset($_GET['vet_id'])) {
    $date = $_GET['date'];
    $vet_id = $_GET['vet_id'];

    $bookedTimes = [];

    // Fetch booked times for the selected vet and date
    $result = mysqli_query($conn, "SELECT time FROM appointment WHERE vet_id = '$vet_id' AND date = '$date' AND status = 'Pending' or 'Completed'");
    while ($row = mysqli_fetch_assoc($result)) {
        $bookedTimes[] = date('H:i', strtotime($row['time'])); // Convert to HH:MM format
    }

    echo json_encode($bookedTimes);
}
?>
