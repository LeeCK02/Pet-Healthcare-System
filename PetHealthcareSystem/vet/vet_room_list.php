<?php
include 'vet_header.php';

// Fetch all rooms
$query = "SELECT * FROM room";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
    <style>
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1rem;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        td img {
            max-width: 80px;
            border-radius: 4px;
        }
        .btn-view {
            background-color: #28a745;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn-view:hover {
            background-color: #218838;
        }
        .actions {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Room Lists</h1>
    <table>
        <thead>
            <tr>
                <th>Room ID</th>
                <th>Location</th>
                <th>Size</th>
                <th>Total Space</th>
                <th>Space Remaining</th>
                <th>Status</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($room = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $room['room_id']; ?></td>
                    <td><?php echo $room['location']; ?></td>
                    <td><?php echo $room['size']; ?></td>
                    <td><?php echo $room['total_space']; ?></td>
                    <td><?php echo $room['space_remaining']; ?></td>
                    <td><?php echo $room['status']; ?></td>
                    <td><img src="../admin/uploaded_img/<?php echo $room['room_img']; ?>" alt="Room Image"></td>
                    <td class="actions"><a href="vet_hospitalization.php?room_id=<?php echo $room['room_id']; ?>" class="btn-view">View Hospitalizations</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
