<?php
include 'admin_header.php'; 

// Handle form submission to add or edit a vaccine
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vaccine_id = isset($_POST['vaccine_id']) ? intval($_POST['vaccine_id']) : 0;
    $vaccine_name = mysqli_real_escape_string($conn, $_POST['vaccine_name']);
    $vaccine_description = mysqli_real_escape_string($conn, $_POST['vaccine_description']);
    $symptoms = mysqli_real_escape_string($conn, $_POST['symptoms']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $importance_level = mysqli_real_escape_string($conn, $_POST['importance_level']);

    // Check if a vaccine with the same name already exists
    $check_query = "SELECT * FROM pet_vaccine WHERE vaccine_name = '$vaccine_name'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0 && $vaccine_id === 0) {
        // If the vaccine name already exists and it's a new entry, display an error message
        echo "<script>alert('A vaccine with the same name already exists!');</script>";
    } else {
        // Add new vaccine or update existing vaccine
        if ($vaccine_id > 0) {
            $query = "UPDATE pet_vaccine SET 
                      vaccine_name = '$vaccine_name', 
                      vaccine_description = '$vaccine_description', 
                      symptoms = '$symptoms', 
                      type = '$type', 
                      importance_level = '$importance_level' 
                      WHERE vaccine_id = $vaccine_id";
            mysqli_query($conn, $query);
            echo "<script>
                    alert('Vaccine updated successfully!');
                    window.location.href = 'admin_manage_vaccine.php';
                  </script>";
        } else {
            $query = "INSERT INTO pet_vaccine 
                      (vaccine_name, vaccine_description, symptoms, type, importance_level) 
                      VALUES ('$vaccine_name', '$vaccine_description', '$symptoms', '$type', '$importance_level')";
            mysqli_query($conn, $query);
            echo "<script>
                    alert('New vaccine added successfully!');
                    window.location.href = 'admin_manage_vaccine.php';
                  </script>";
        }
        exit;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $vaccine_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM pet_vaccine WHERE vaccine_id = $vaccine_id");
    echo "<script>
            alert('Vaccine deleted successfully!');
            window.location.href = 'admin_manage_vaccine.php';
          </script>";
    exit;
}

// Fetch all vaccines from the database
$vaccines = mysqli_query($conn, "SELECT * FROM pet_vaccine");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vaccines</title>
    <style>

        h1 {
            text-align: center;
            color: #0047ab;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #0047ab;
            color: white;
        }

        /* Button styles */
        button {
            padding: 10px 20px;
            background-color: #0047ab;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #00337a;
        }

        .editBtn {
            width: 70px;
            padding: 10px 20px;
            background-color: #0047ab;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .editBtn:hover {
            background-color: #00337a;
        }

        .deleteBtn {
            width: 70px;
            padding: 10px 20px;
            margin-top: 10px;
            background-color: red; 
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .deleteBtn:hover {
            background-color: darkred; /* Darker red on hover */
        }


        label {
            display: block; /* Ensure labels take the full width and appear above inputs */
            margin-bottom: 5px; /* Space between label and input */
        }

        input[type="text"], textarea {
            width: 90%; /* Full width for inputs */
            padding: 10px;
            margin-bottom: 10px; /* Space below each input */
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="select"], select {
            width: 94%; /* Full width for inputs */
            padding: 10px;
            margin-bottom: 10px; /* Space below each input */
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Error message */
        .error {
            color: red;
            margin-bottom: 15px;
        }

        /* Add Vaccine Box */
        .add-vaccine-box {
            max-width: 600px;
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            margin: 0 auto;
        }

        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4); 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 600px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .vaccine-table{
            margin: 0 auto;
            width: 80%;
        }
    </style>
</head>
<body>

<h1>Manage Pet Vaccines</h1>

<!-- Add New Vaccine Form -->
<div class="add-vaccine-box">
    <h2>Add New Vaccine</h2>
    <form method="POST" action="admin_manage_vaccine.php" onsubmit="return confirm('Are you sure you want to add or update this vaccine?');">
        <input type="hidden" name="vaccine_id" value="<?php echo isset($vaccine_id) ? $vaccine_id : 0; ?>">

        <label for="vaccine_name">Vaccine Name:</label>
        <input type="text" name="vaccine_name" id="vaccine_name" required>

        <label for="vaccine_description">Description:</label>
        <textarea name="vaccine_description" id="vaccine_description" rows="3" required></textarea>

        <label for="symptoms">Symptoms:</label>
        <textarea name="symptoms" id="symptoms" rows="2" required></textarea>

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="Cat">Cat</option>
            <option value="Dog">Dog</option>
            <option value="Others">Others</option>
        </select>

        <label for="importance_level">Importance Level:</label>
        <select name="importance_level" id="importance_level" required>
            <option value="Core">Core</option>
            <option value="Non-Core">Non-Core</option>
        </select>

        <button type="submit">Add Vaccine</button>
    </form>
</div>

<!-- Existing Vaccine Table -->
<div class="vaccine-table">
    <h2>Existing Vaccines</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Symptoms</th>
                <th>Type</th>
                <th>Importance Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($vaccine = mysqli_fetch_assoc($vaccines)) { ?>
                <tr>
                    <td><?php echo $vaccine['vaccine_id']; ?></td>
                    <td><?php echo $vaccine['vaccine_name']; ?></td>
                    <td><?php echo nl2br($vaccine['vaccine_description']); ?></td>
                    <td><?php echo nl2br($vaccine['symptoms']); ?></td>
                    <td><?php echo $vaccine['type']; ?></td>
                    <td><?php echo $vaccine['importance_level']; ?></td>
                    <td>
                        <button class="editBtn" data-vaccine='<?php echo json_encode($vaccine); ?>'>Edit</button>
                        <button class="deleteBtn" onclick="confirmDelete(<?php echo $vaccine['vaccine_id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal for Editing -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Vaccine</h2>
        <form method="POST" action="admin_manage_vaccine.php" onsubmit="return confirm('Are you sure you want to update this vaccine?');">
            <input type="hidden" name="vaccine_id" id="edit_vaccine_id">

            <label for="edit_vaccine_name">Vaccine Name:</label>
            <input type="text" name="vaccine_name" id="edit_vaccine_name" readonly>

            <label for="edit_vaccine_description">Description:</label>
            <textarea name="vaccine_description" id="edit_vaccine_description" rows="3" required></textarea>

            <label for="edit_symptoms">Symptoms:</label>
            <textarea name="symptoms" id="edit_symptoms" rows="2" required></textarea>

            <label for="edit_type">Type:</label>
            <select name="type" id="edit_type" required>
                <option value="Cat">Cat</option>
                <option value="Dog">Dog</option>
                <option value="Others">Others</option>
            </select>

            <label for="edit_importance_level">Importance Level:</label>
            <select name="importance_level" id="edit_importance_level" required>
                <option value="Core">Core</option>
                <option value="Non-Core">Non-Core</option>
            </select>

            <button type="submit">Update Vaccine</button>
        </form>
    </div>
</div>

<script>
    // Modal and form handling code
    var modal = document.getElementById("editModal");
    var span = document.getElementsByClassName("close")[0];

    document.querySelectorAll(".editBtn").forEach(button => {
        button.onclick = function() {
            var vaccine = JSON.parse(this.getAttribute('data-vaccine'));
            document.getElementById("edit_vaccine_id").value = vaccine.vaccine_id;
            document.getElementById("edit_vaccine_name").value = vaccine.vaccine_name;
            document.getElementById("edit_vaccine_description").value = vaccine.vaccine_description;
            document.getElementById("edit_symptoms").value = vaccine.symptoms;
            document.getElementById("edit_type").value = vaccine.type;
            document.getElementById("edit_importance_level").value = vaccine.importance_level;
            modal.style.display = "block";
        }
    });

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function confirmDelete(vaccineId) {
        if (confirm('Are you sure you want to delete this vaccine?')) {
            window.location.href = 'admin_manage_vaccine.php?delete=' + vaccineId;
        }
    }
</script>

</body>
</html>