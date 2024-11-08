<?php
include 'admin_header.php';

$rooms = mysqli_query($conn, "SELECT * FROM room ORDER BY room_id");

// Handle adding a new room
if (isset($_POST['add_room'])) {
    $location = $_POST['location'];
    $size = $_POST['size'];
    $total_space = $_POST['total_space'];
    $space_remaining = $_POST['total_space'];
    $status = $_POST['status'];
    $room_img = $_FILES['room_img']['name'];
    $target = "uploaded_img/" . basename($room_img);

    // Check if a room with the same location already exists
    $checkDuplicate = mysqli_query($conn, "SELECT * FROM room WHERE location = '$location'");
    if (mysqli_num_rows($checkDuplicate) > 0) {
        echo "<script>alert('A room with the same location already exists.');</script>";
    } else {
        $insert = "INSERT INTO room (location, size, total_space, space_remaining, status, room_img) 
                   VALUES ('$location', '$size', '$total_space', '$space_remaining', '$status', '$room_img')";
        $upload = mysqli_query($conn, $insert);

        if ($upload) {
            move_uploaded_file($_FILES['room_img']['tmp_name'], $target);
            echo "<script>
                    alert('Room added successfully!');
                    window.location.href = 'admin_manage_hospitalization.php';
                  </script>";
        } else {
            echo "<script>alert('Could not add the room.');</script>";
        }
    }
}

// Handle deleting a room
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM room WHERE room_id = $deleteId");
    echo "<script>
            alert('Room deleted successfully!');
            window.location.href = 'admin_manage_hospitalization.php';
          </script>";
}

// Handle editing a room
if (isset($_POST['edit_room'])) {
    $room_id = $_POST['room_id'];
    $location = $_POST['location'];
    $size = $_POST['size'];
    $total_space = $_POST['total_space'];
    $space_remaining = $_POST['space_remaining'];
    $status = $_POST['status'];

    $room_img = $_FILES['room_img']['name'];
    $target = "uploaded_img/" . basename($room_img);

    // Check if a room with the same location (excluding the current room) already exists
    $checkDuplicate = mysqli_query($conn, "SELECT * FROM room WHERE location = '$location' AND room_id != '$room_id'");
    if (mysqli_num_rows($checkDuplicate) > 0) {
        echo "<script>alert('A room with the same location already exists. Please choose a different location.');</script>";
    } else {
        if (!empty($room_img)) {
            // If a new image is uploaded, update the image
            $update = "UPDATE room SET 
                       location = '$location', size = '$size', total_space = '$total_space', 
                       space_remaining = '$space_remaining', status = '$status', room_img = '$room_img' 
                       WHERE room_id = '$room_id'";
            move_uploaded_file($_FILES['room_img']['tmp_name'], $target);
        } else {
            // If no new image is uploaded, keep the existing image
            $update = "UPDATE room SET 
                       location = '$location', size = '$size', total_space = '$total_space', 
                       space_remaining = '$space_remaining', status = '$status' 
                       WHERE room_id = '$room_id'";
        }

        if (mysqli_query($conn, $update)) {
            echo "<script>
                    alert('Room updated successfully!');
                    window.location.href = 'admin_manage_hospitalization.php';
                  </script>";
        } else {
            echo "<script>alert('Could not update the room.');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pet Healthcare &bull; Manage Rooms</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_css/main.css">
    <style>
        .add-box label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #0047ab;
        }

        .room-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .room-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            width: 25%;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .room-box img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .room-box h3 {
            font-size: 1.4rem;
            margin-top: 10px;
            color: #198c8c;
        }

        .room-box p {
            font-size: 0.95rem;
            color: #666;
        }

        .room-box:hover {
            transform: translateY(-5px);
        }

        .add-vet, .edit-btn, .delete-btn {
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            border: none;
            transition: background-color 0.3s;
        }

        .add-vet {
            background-color: #2ab06b;
            color: white;
        }
        .add-vet:hover {
            background-color: #24a060;
        }
        .edit-btn {
            height: auto;
            background-color: #0047ab;
            color: white;
        }
        .edit-btn:hover {
            background-color: #00337a;
        }
        .delete-btn {
            height: auto;
            background-color: #ff4d4d;
            color: white;
        }
        .delete-btn:hover {
            background-color: #e60000;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            overflow: auto;
        }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: slide-down 0.5s ease;
        }

        .modal-content h2 {
            margin-top: 0;
            text-align: center;
            color: #333;
            font-size: 1.5rem;
        }

        @keyframes slide-down {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #888;
            font-size: 24px;
            cursor: pointer;
        }

        .close:hover {
            color: #333;
        }

        input[type="text"], input[type="number"], select, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #198c8c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #107e7e;
        }
    </style>
</head>
<body>
<div style="padding: 1px; margin-left: 50px; text-align:center;">
    <h1 style="margin-top: 50px; margin-bottom: 50px;">Manage Rooms</h1>

    <button type="button" class="add-vet" onclick="addRoom();">Create New Room</button><br>

    <!-- Add Room Form -->
    <div id="add-box" class="add-box" style="display: none; padding: 20px; border: 1px solid; width: 50%; margin: 50px auto;">
        <button type="button" class="close" onclick="hideBox();">X</button>
        <h2>Create New Room</h2>
        <form id="addRoomForm" action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm('addRoomForm')">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required>
            <label for="size">Size</label>
            <input type="text" id="size" name="size" required>
            <label for="total_space">Total Space</label>
            <input type="number" id="total_space" name="total_space" required>
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="">Select Status</option>
                <option value="available">Available</option>
                <option value="full">Full</option>
            </select>
            <label for="room_img">Room Image</label>
            <input type="file" id="room_img" name="room_img" required>
            <input type="submit" name="add_room" value="Add Room">
        </form>
    </div>

    <!-- Room Cards -->
    <div class="room-container">
        <?php while($row = mysqli_fetch_assoc($rooms)) { ?>
            <div class="room-box" onclick="navigateToDetails(<?php echo $row['room_id']; ?>)">
                <img src="uploaded_img/<?php echo $row['room_img']; ?>" alt="Room Image">
                <h3><?php echo $row['location']; ?></h3>
                <p>Size: <?php echo $row['size']; ?></p>
                <p>Total Space: <?php echo $row['total_space']; ?></p>
                <p>Space Remaining: <?php echo $row['space_remaining']; ?></p>
                <p>Status: <?php echo ucfirst($row['status']); ?></p>
                <button class="edit-btn" onclick="event.stopPropagation(); openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                <button class="delete-btn" onclick="event.stopPropagation(); confirmDelete(<?php echo $row['room_id']; ?>)">Delete</button>
            </div>
        <?php } ?>
    </div>


    <!-- Edit Room Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Room</h2>
            <form id="editRoomForm" action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm('editRoomForm')">
                <input type="hidden" id="room_id" name="room_id">
                <label for="edit_location">Location</label>
                <input type="text" id="edit_location" name="location" required>
                <label for="edit_size">Size</label>
                <input type="text" id="edit_size" name="size" required>
                <label for="edit_total_space">Total Space</label>
                <input type="number" id="edit_total_space" name="total_space" required>
                <label for="edit_space_remaining">Space Remaining</label>
                <input type="number" id="edit_space_remaining" name="space_remaining" required>
                <label for="edit_status">Status</label>
                <select id="edit_status" name="status" required>
                    <option value="">Select Status</option>
                    <option value="available">Available</option>
                    <option value="full">Full</option>
                </select>
                <label for="edit_room_img">Room Image</label>
                <input type="file" id="edit_room_img" name="room_img">
                <input type="submit" name="edit_room" value="Update Room">
            </form>
        </div>
    </div>
</div>

<script>
    function addRoom() {
        document.getElementById("add-box").style.display = "block";
    }

    function hideBox() {
        document.getElementById("add-box").style.display = "none";
    }

    function openEditModal(room) {
        document.getElementById("room_id").value = room.room_id;
        document.getElementById("edit_location").value = room.location;
        document.getElementById("edit_size").value = room.size;
        document.getElementById("edit_total_space").value = room.total_space;
        document.getElementById("edit_space_remaining").value = room.space_remaining;
        document.getElementById("edit_status").value = room.status;
        document.getElementById("editModal").style.display = "flex";
    }

    function closeEditModal() {
        document.getElementById("editModal").style.display = "none";
    }

    function confirmDelete(roomId) {
        if (confirm("Are you sure you want to delete this room?")) {
            window.location.href = 'admin_manage_hospitalization.php?delete=' + roomId;
        }
    }

    function navigateToDetails(roomId) {
        window.location.href = 'admin_hospitalization_details.php?room_id=' + roomId;
    }


    function validateForm(formId) {
        const form = document.getElementById(formId);
        const totalSpace = form.querySelector('input[name="total_space"]').value;
        const spaceRemaining = form.querySelector('input[name="space_remaining"]').value;

        // Check if remaining space is greater than total space
        if (parseInt(spaceRemaining) > parseInt(totalSpace)) {
            alert("Space remaining cannot be greater than total space.");
            return false;
        }

        // Check for other required fields
        for (let element of form.elements) {
            if (element.required && element.value.trim() === "") {
                alert("Please fill in all required fields.");
                return false;
            }
        }
        return true;
    }
</script>

</body>
</html>
