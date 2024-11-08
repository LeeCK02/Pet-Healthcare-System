<?php
include 'admin_header.php';

if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    // Fetch the room details
    $room_query = mysqli_query($conn, "SELECT * FROM room WHERE room_id = $room_id");
    $room = mysqli_fetch_assoc($room_query);

    // Fetch hospitalization details for the room
    $hospitalization_query = mysqli_query($conn, "SELECT * FROM hospitalization WHERE room_id = $room_id");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hospitalization Details for Room <?php echo htmlspecialchars($room['location']); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_css/main.css">
    <style>

        h1, h2 {
            color: #2C3E50;
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            margin-top: 20px;
            font-size: 2.5rem;
        }

        h2 {
            font-size: 1.8rem;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 30px;
        }

        p {
            font-size: 1.2rem;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 1.1rem;
        }

        th {
            background-color: #3498DB;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .button {
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            margin: 10px auto;
        }

        .button:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Hospitalization Details for Room: <?php echo htmlspecialchars($room['location']); ?></h1>
    <h2>Room Information</h2>
    <p>Size: <?php echo htmlspecialchars($room['size']); ?></p>
    <p>Total Space: <?php echo htmlspecialchars($room['total_space']); ?></p>
    <p>Space Remaining: <?php echo htmlspecialchars($room['space_remaining']); ?></p>
    <p>Status: <?php echo ucfirst(htmlspecialchars($room['status'])); ?></p>

    <h2>Hospitalization Records</h2>
    <table>
        <tr>
            <th>Hospitalization ID</th>
            <th>Pet ID</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
        <?php while ($detail = mysqli_fetch_assoc($hospitalization_query)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($detail['hosp_id']); ?></td>
                <td><?php echo htmlspecialchars($detail['pet_id']); ?></td>
                <td><?php echo htmlspecialchars($detail['reason']); ?></td>
                <td><?php echo htmlspecialchars($detail['status']); ?></td>
                <td><?php echo htmlspecialchars($detail['start_date']); ?></td>
                <td><?php echo htmlspecialchars($detail['end_date']); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
