<?php
include 'config/config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vaccine_name = $_POST['vaccine_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $pet_id = $_POST['pet_id'];

    // Insert the new vaccine record into the database
    $query = "INSERT INTO vaccine_record (vaccine_name, start_date, end_date, pet_id) VALUES ('$vaccine_name', '$start_date', '$end_date', '$pet_id')";
    if (mysqli_query($conn, $query)) {
        // Redirect back to the vaccine history page
        header("Location: vet_pet_vaccine.php?id=$pet_id");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
