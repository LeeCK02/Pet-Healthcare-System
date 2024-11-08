<?php 
include 'header.php';

// Start output buffering to avoid "headers already sent" error
ob_start();

// Fetch pets for the current user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM pet WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pets = $result->fetch_all(MYSQLI_ASSOC);

// Fetch ongoing hospitalizations
$currentDate = date('Y-m-d');
$hospitalizations = [];

foreach ($pets as $pet) {
    $pet_id = $pet['pet_id'];
    $query = "SELECT * FROM hospitalization WHERE pet_id = ? AND status = 'ongoing' AND start_date <= ? AND end_date >= ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $pet_id, $currentDate, $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hospitalizations[$pet_id] = $result->fetch_assoc();
    }
}

// Handle form submission for adding a new pet
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_pet'])) {
        $name = $_POST['name'];
        $type = $_POST['type'];
        $breed = $_POST['breed'];
        $age = $_POST['age'];
        $preferences = $_POST['preferences'];

        // Check if a pet with the same name already exists for the user
        $check_query = "SELECT * FROM pet WHERE user_id = ? AND name = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("is", $user_id, $name);
        $stmt->execute();
        $check_result = $stmt->get_result();
        if ($check_result->num_rows > 0) {
            echo "<script>alert('You already have a pet with this name. Please choose a different name.');</script>";
        } else {
            // Handle file upload
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $image = 'uploaded_img/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $image);
            } else {
                echo "<script>alert('Please select an image.');</script>";
                exit();
            }

            $query = "INSERT INTO pet (name, type, breed, age, image, preferences, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssissi", $name, $type, $breed, $age, $image, $preferences, $user_id);
            $stmt->execute();

            echo "<script>alert('Pet added successfully.'); window.location.href = 'pet_profile.php';</script>";
            exit();
        }
    } elseif (isset($_POST['edit_pet'])) {
        $pet_id = $_POST['pet_id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $breed = $_POST['breed'];
        $age = $_POST['age'];
        $preferences = $_POST['preferences'];

        // Check if a pet with the same name already exists for the user (excluding the current pet)
        $check_query = "SELECT * FROM pet WHERE user_id = ? AND name = ? AND pet_id != ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("isi", $user_id, $name, $pet_id);
        $stmt->execute();
        $check_result = $stmt->get_result();
        if ($check_result->num_rows > 0) {
            echo "<script>alert('You already have a pet with this name. Please choose a different name.');</script>";
        } else {
            // Handle file upload
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $image = 'uploaded_img/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $image);
            }

            $query = "UPDATE pet SET name = ?, type = ?, breed = ?, age = ?, image = ?, preferences = ? WHERE pet_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssissi", $name, $type, $breed, $age, $image, $preferences, $pet_id);
            $stmt->execute();

            echo "<script>alert('Pet updated successfully.'); window.location.href = 'pet_profile.php';</script>";
            exit();
        }
    } elseif (isset($_POST['delete_pet'])) {
        $pet_id = $_POST['pet_id'];

        $query = "DELETE FROM pet WHERE pet_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $pet_id);
        $stmt->execute();

        echo "<script>alert('Pet deleted successfully.'); window.location.href = 'pet_profile.php';</script>";
        exit();
    }
}

// End output buffering and flush output
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pet Profile</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <style>
        .main {
            padding: 100px 100px;
        }
        .pet-container {
            display: flex;
            flex-wrap: wrap;
            gap: 70px;
            margin-top: 30px;
        }
        .pet-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            width: 350px;
            text-align: center;
            background: rgb(231,209,236);
            background: linear-gradient(90deg, rgba(231,209,236,1) 12%, rgba(161,172,234,1) 49%, rgba(163,240,255,1) 97%);
        }
        .pet-box img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .btn-add {
            margin: 20px 0;
            display: inline-block;
            background-color: #ff0066;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            cursor: pointer;
        }
        .form-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .form-container.show {
            display: block;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
        }
        .form-container input[type="text"], .form-container input[type="number"], .form-container textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container input[type="submit"] {
            background-color: #ff0066;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #cc0052;
        }
        .btn-info {
            margin-top: 10px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 900;
        }
        .overlay.show {
            display: block;
        }

        select {
            width: 100%; 
            padding: 0.5rem; 
            font-size: 1rem; 
            border: 1px solid #ccc; 
            border-radius: 0.375rem; 
            background-color: #fff; 
            color: #333;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease; 
        }
        #image-preview {
            max-width: 200px;
            height: auto;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="main">
        <div align="center">
            <h3 style="display: inline;">Your Pet</h3>
            <a href="#" class="btn-add" id="show-form" style="display: inline; margin-left: 50px;">Add Pet +</a>
        </div>

        <div class="overlay" id="overlay"></div>
        <div class="form-container" id="form-container">
            <h2>Add/Edit Pet</h2>
            <form action="pet_profile.php" method="POST" enctype="multipart/form-data" id="pet-form" onsubmit="return validateForm() && confirm('Are you sure you want to add/edit this pet?')">
                <input type="hidden" id="pet_id" name="pet_id" value="">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
                
                <label for="type">Pet Type:</label>
                <select id="type" name="type" required>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                    <option value="Others">Others</option>
                </select>
                
                <label for="breed">Breed</label>
                <input type="text" id="breed" name="breed" required>

                <label for="age">Age</label>
                <input type="number" id="age" name="age" required>
                
                <label for="preferences">Preferences</label>
                <textarea id="preferences" name="preferences" rows="4" required></textarea>
                
                <label for="image">Image</label>
                <input type="file" id="image" name="image" onchange="previewImage()" required>
                <img id="image-preview" style="display: none;">
                
                <input type="submit" id="submit-btn" name="add_pet" value="Add Pet">
            </form>
        </div>

        <div class="pet-container">
            <?php foreach ($pets as $pet): ?>
            <div class="pet-box">
                <img src="<?php echo htmlspecialchars($pet['image']); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>">
                <h3><?php echo htmlspecialchars($pet['name']); ?></h3>
                <p>Type: <?php echo htmlspecialchars($pet['type']); ?></p>
                <p>Breed: <?php echo htmlspecialchars($pet['breed']); ?></p>
                <p>Age: <?php echo htmlspecialchars($pet['age']); ?> years old</p>
                <p>Preferences: <?php echo htmlspecialchars($pet['preferences']); ?></p>

                <?php if (isset($hospitalizations[$pet['pet_id']])): ?>
                    <button class="btn btn-warning" style="margin-bottom: 10px;" data-bs-toggle="modal" data-bs-target="#hospitalizationModal-<?php echo htmlspecialchars($pet['pet_id']); ?>">
                        Under Hospitalization
                    </button></br>

                    <div class="modal fade" id="hospitalizationModal-<?php echo htmlspecialchars($pet['pet_id']); ?>" tabindex="-1" aria-labelledby="hospitalizationModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="hospitalizationModalLabel">Hospitalization Details for <?php echo htmlspecialchars($pet['name']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Reason:</strong> <?php echo htmlspecialchars($hospitalizations[$pet['pet_id']]['reason']); ?></p>
                                    <p><strong>Treatment:</strong> <?php echo htmlspecialchars($hospitalizations[$pet['pet_id']]['treatment']); ?></p>
                                    <p><strong>Start Date:</strong> <?php echo htmlspecialchars($hospitalizations[$pet['pet_id']]['start_date']); ?></p>
                                    <p><strong>Expected End Date:</strong> <?php echo htmlspecialchars($hospitalizations[$pet['pet_id']]['end_date']); ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <button class="btn btn-primary" onclick="editPet(
                    '<?php echo htmlspecialchars($pet['pet_id']); ?>',
                    '<?php echo htmlspecialchars($pet['name']); ?>',
                    '<?php echo htmlspecialchars($pet['type']); ?>',
                    '<?php echo htmlspecialchars($pet['breed']); ?>',
                    '<?php echo htmlspecialchars($pet['age']); ?>',
                    '<?php echo htmlspecialchars($pet['preferences']); ?>',
                    '<?php echo htmlspecialchars($pet['image']); ?>'
                )">Edit</button>

                <form action="pet_profile.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this pet?')">
                    <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($pet['pet_id']); ?>">
                    <input type="submit" name="delete_pet" value="Remove" class="btn btn-danger">
                </form>
                </br>

                <a href="vaccine_history.php?pet_id=<?php echo htmlspecialchars($pet['pet_id']); ?>" class="btn btn-info">View Vaccine</a>
                <a href="prescription.php?pet_id=<?php echo htmlspecialchars($pet['pet_id']); ?>" class="btn btn-info">View Prescription</a>
            </div>
            <?php endforeach; ?>
        </div>

        <script>
            function validateForm() {
                const name = document.getElementById('name').value.trim();
                const type = document.getElementById('type').value;
                const breed = document.getElementById('breed').value.trim();
                const age = document.getElementById('age').value.trim();
                const preferences = document.getElementById('preferences').value.trim();
                const image = document.getElementById('image').files.length;

                if (!name || !type || !breed || !age || !preferences || image === 0) {
                    alert('Please fill in all fields and select an image.');
                    return false;
                }
                return true;
            }

            function previewImage() {
                const file = document.getElementById('image').files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('image-preview').src = e.target.result;
                        document.getElementById('image-preview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Show the form for adding or editing pets
            document.getElementById('show-form').addEventListener('click', function(event) {
                event.preventDefault();
                document.getElementById('overlay').classList.add('show');
                document.getElementById('form-container').classList.add('show');
                document.getElementById('submit-btn').name = 'add_pet';
                document.getElementById('pet_id').value = '';
                document.getElementById('form-container').querySelector('h2').textContent = 'Add New Pet';
            });

            // Hide the form
            document.getElementById('overlay').addEventListener('click', function() {
                document.getElementById('overlay').classList.remove('show');
                document.getElementById('form-container').classList.remove('show');
            });

            // Edit pet functionality
            function editPet(pet_id, name, type, breed, age, preferences, image) {
                document.getElementById('overlay').classList.add('show');
                document.getElementById('form-container').classList.add('show');
                document.getElementById('submit-btn').name = 'edit_pet';
                document.getElementById('pet_id').value = pet_id;
                document.getElementById('name').value = name;
                document.getElementById('type').value = type;
                document.getElementById('breed').value = breed;
                document.getElementById('age').value = age;
                document.getElementById('preferences').value = preferences;
                document.getElementById('form-container').querySelector('h2').textContent = 'Edit Pet';
            }
        </script>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>