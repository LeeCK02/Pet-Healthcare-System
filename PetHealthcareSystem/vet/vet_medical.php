<?php
include 'vet_header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission
    $pet_id = $_POST['pet_id'];
    $user_id = $_POST['user_id'];
    $vet_id = $_SESSION['vet_id']; 
    $date = $_POST['date'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $notes = $_POST['notes'];
    $medication_name = $_POST['medication_name'];
    $dosage = $_POST['dosage'];
    $frequency = $_POST['frequency'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $pres_notes = $_POST['pres_notes'];

    // Handle file upload
    $medical_report = null; // Initialize variable for the medical report
    if (isset($_FILES['medical_report']) && $_FILES['medical_report']['error'] == 0) {
        $target_dir = "uploads/";
        $medical_report = basename($_FILES['medical_report']['name']);
        $target_file = $target_dir . $medical_report;

        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        if (in_array($file_type, $allowed_types) && $_FILES['medical_report']['size'] < 5000000) {
            if (!move_uploaded_file($_FILES['medical_report']['tmp_name'], $target_file)) {
                echo "<script>alert('Error uploading the medical report.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type or file too large.');</script>";
        }
    }

    // Insert medical record
    $insert_medical_query = "INSERT INTO medical_record (pet_id, user_id, vet_id, date, diagnosis, treatment, notes, medical_report) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_medical_query);
    $stmt->bind_param("iiisssss", $pet_id, $user_id, $vet_id, $date, $diagnosis, $treatment, $notes, $medical_report);
    $stmt->execute();

    // If medication information is provided, insert it into the prescription table
    if (!empty($medication_name)) {
        $insert_prescription_query = "INSERT INTO prescription (pet_id, user_id, vet_id, medication_name, dosage, frequency, start_date, end_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_prescription_query);
        $stmt->bind_param("iiissssss", $pet_id, $user_id, $vet_id, $medication_name, $dosage, $frequency, $start_date, $end_date, $pres_notes);
        $stmt->execute();
    }

    // Success alert and redirection
    echo "<script>
            alert('Medical record added successfully!');
            window.location.href='vet_view_pet.php?user={$user_id}';
          </script>";
    exit;
}

// Fetch pet details
$pet_id = $_GET['id'];
$user_id = $_GET['user'];
$pet_result = mysqli_query($conn, "SELECT * FROM pet WHERE pet_id = '$pet_id'");
$pet = mysqli_fetch_assoc($pet_result);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medical Record</title>
    <link rel="stylesheet" href="admin_css/main.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            border-color: #4CAF50;
        }
        .submit-btn, .back-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: block;
            width: 100%;
            margin-top: 10px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
        }
        .submit-btn:hover, .back-btn:hover {
            background-color: #45a049;
        }
        .medication-section {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            background-color: #f9f9f9;
            margin-top: 70px;
        }
        .medication-section h3 {
            margin-top: 0;
            color: #333;
        }
    </style>
    <script>
        function validateMedication() {
            const medicationName = document.querySelector('input[name="medication_name"]').value;
            const dosage = document.querySelector('input[name="dosage"]').value;
            const frequency = document.querySelector('input[name="frequency"]').value;
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;

            // Check if any of the medication fields are filled
            if (medicationName || dosage || frequency || startDate || endDate) {
                // If any medication field is filled, ensure all must be filled
                if (!medicationName || !dosage || !frequency || !startDate || !endDate) {
                    alert('Please fill all fields related to the prescribed medication if you enter any medication information.');
                    return false; // Prevent form submission
                }
            }
            return true; // Proceed with form submission
        }
    </script>

</head>
<body>
    <div class="form-container">
        <h1>Add Medical Record for <?php echo htmlspecialchars($pet['name']); ?></h1>
        <form method="POST" enctype="multipart/form-data" onsubmit="return validateMedication();">
            <input type="hidden" name="pet_id" value="<?php echo $pet_id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> <!-- Pass user_id from URL -->

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="diagnosis">Diagnosis:</label>
                <input type="text" name="diagnosis" required>
            </div>
            <div class="form-group">
                <label for="treatment">Treatment:</label>
                <textarea name="treatment" required></textarea>
            </div>
            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea name="notes" required></textarea>
            </div>
            <div class="form-group">
                <label for="medical_report">Upload Medical Report:</label>
                <input type="file" name="medical_report" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
            </div>
            <div class="medication-section">
                <h3>Prescribe Medication (Optional)</h3>
                <div class="form-group">
                    <label for="medication_name">Medication Name:</label>
                    <input type="text" name="medication_name">
                </div>
                <div class="form-group">
                    <label for="dosage">Dosage:</label>
                    <input type="text" name="dosage">
                </div>
                <div class="form-group">
                    <label for="frequency">Frequency:</label>
                    <input type="text" name="frequency">
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date">
                </div>
                <div class="form-group">
                    <label for="pres_notes">Notes:</label>
                    <input type="text" name="pres_notes">
                </div>
            </div>
            <button type="submit" class="submit-btn">Add Medical Record</button>
            <a href="vet_view_pet.php?user=<?php echo $user_id; ?>" class="back-btn">Back to Pet Profile</a>
        </form>
    </div>
</body>
</html>
