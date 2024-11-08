<?php
include 'admin_header.php';

$user_id = $_GET['user']; // Get the user ID from the query parameter
$pets = mysqli_query($conn, "SELECT * FROM pet WHERE user_id = '$user_id'");
$user_result = mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($user_result); // Fetch the user details
$username = htmlspecialchars($user['username']); // Get the username safely
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Pets - Admin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_css/main.css">
    <style>
        .pet-display {
            margin: 2rem 0;
            margin-bottom: 100px;
        }
        .pet-display-table {
            width: 80%;
            margin: 0 auto;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
        }
        .pet-display-table thead {
            background: #4CAF50;
            color: white;
        }
        .pet-display-table th, .pet-display-table td {
            padding: 1rem;
            border-bottom: 1px solid #ccc;
        }
        .pet-display-table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
        .action-btn {
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .action-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-top: 30px;">
        <h1><?php echo $username; ?>'s Pets</h1>
        <div class="pet-display">
            <table class="pet-display-table">
                <thead>
                    <tr>
                        <th>Pet ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Breed</th>
                        <th>Age</th>
                        <th>Image</th>
                        <th>Preferences</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($pets)) { ?>
                    <tr>
                        <td><?php echo $row['pet_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                        <td><?php echo htmlspecialchars($row['breed']); ?></td>
                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                        <td><img src="../<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"></td>
                        <td><?php echo htmlspecialchars($row['preferences']); ?></td>
                        <td>
                            <button class="action-btn" style="padding: 10px;" onclick="window.location.href='admin_view_pet_vaccine.php?id=<?php echo $row['pet_id']; ?>'">View Vaccine Record</button>
                            <button class="action-btn" style="padding: 10px; margin-top: 5px;" onclick="window.location.href='admin_view_medical.php?id=<?php echo $row['pet_id']; ?>&user=<?php echo $row['user_id']; ?>'">View Medical Records</button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button class="action-btn" style="margin-top: 20px; font-size: 20px; padding: 10px;" onclick="window.location.href='admin_manage_user.php'">Back</button>
        </div>
    </div>
</body>
</html>
