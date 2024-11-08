<?php
include 'config/config.php'; // Database connection

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Fetch pets for the selected user
    $pet_query = "SELECT * FROM pet WHERE user_id = '$user_id'";
    $pet_result = mysqli_query($conn, $pet_query);

    if (mysqli_num_rows($pet_result) > 0) {
        while ($pet = mysqli_fetch_assoc($pet_result)) {
            echo '<option value="' . $pet['pet_id'] . '">' . $pet['name'] . '</option>';
        }
    } else {
        echo '<option value="">No pets available</option>';
    }
}
