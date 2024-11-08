<?php
include 'header.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Show an alert and redirect using JavaScript
    echo "<script>
            alert('You must be logged in to access this page.');
            window.location.href = 'login.php'; // Redirect to the login page
          </script>";
    exit();
}

$user_id = $_SESSION['user_id']; // Assuming the user is logged in

// Fetch all veterinarians with the last message details, sorted by timestamp
$vets = mysqli_query($conn, "
    SELECT v.vet_id, v.name, v.profile_picture,
           (SELECT message FROM consultation WHERE vet_id = v.vet_id AND user_id = '$user_id' ORDER BY timestamp DESC LIMIT 1) AS last_message,
           (SELECT timestamp FROM consultation WHERE vet_id = v.vet_id AND user_id = '$user_id' ORDER BY timestamp DESC LIMIT 1) AS last_timestamp,
           (SELECT sender_type FROM consultation WHERE vet_id = v.vet_id AND user_id = '$user_id' ORDER BY timestamp DESC LIMIT 1) AS last_sender,
           (SELECT attachment FROM consultation WHERE vet_id = v.vet_id AND user_id = '$user_id' ORDER BY timestamp DESC LIMIT 1) AS last_attachment
    FROM veterinary v
    ORDER BY last_timestamp DESC
");

// Separate veterinarians who have been chatting from those who haven't
$chattedVets = [];
$newVets = [];

while ($vet = mysqli_fetch_assoc($vets)) {
    if (!empty($vet['last_message']) || !empty($vet['last_attachment'])) {
        $chattedVets[] = $vet;
    } else {
        $newVets[] = $vet;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Veterinarian</title>
    <style>
        h1 {
            text-align: center;
            color: #0047ab;
            margin-top: 30px;
        }

        .vet-list {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .vet-section {
            margin-bottom: 30px;
        }

        .vet-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 10px;
            width: 45%;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .vet-card:hover {
            transform: scale(1.05);
        }

        .vet-card a {
            text-decoration: none;
            color: #0047ab;
            font-weight: bold;
        }

        .vet-pic {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .last-message {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
            line-height: 1.5;
        }

        .timestamp {
            font-size: 12px;
            color: #999;
        }

        .sender {
            font-weight: bold;
            color: #0047ab;
        }

        @media (max-width: 600px) {
            .vet-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<h1>Choose Veterinarian</h1>

<div class="vet-section">
    <h2>Veterinarians You've Chatted With</h2>
    <div class="vet-list">
        <?php if (empty($chattedVets)) { ?>
            <p style="text-align: center; width: 100%;">No conversations yet.</p>
        <?php } else { ?>
            <?php foreach ($chattedVets as $vet) { ?>
                <div class="vet-card">
                    <img src="vet/<?php echo htmlspecialchars($vet['profile_picture']); ?>" alt="<?php echo htmlspecialchars($vet['name']); ?>" class="vet-pic">
                    <p><?php echo htmlspecialchars($vet['name']); ?></p>
                    <div class="last-message">
                        <a>Last Message:</a><br>
                        <span class="sender"><?php echo $vet['last_sender'] === 'user' ? 'You' : htmlspecialchars($vet['name']); ?>:</span> 
                        <?php if (!empty($vet['last_message'])): ?>
                            <?php echo htmlspecialchars($vet['last_message']); ?>
                        <?php elseif (!empty($vet['last_attachment'])): ?>
                            <?php
                            // Check the file extension for the last attachment
                            $file_extension = pathinfo($vet['last_attachment'], PATHINFO_EXTENSION);
                            if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                echo '<a href="' . htmlspecialchars($vet['last_attachment']) . '" target="_blank"><img src="' . htmlspecialchars($vet['last_attachment']) . '" alt="Attachment" style="max-width: 100px; border-radius: 5px;"></a>';
                            } else {
                                echo '<a href="' . htmlspecialchars($vet['last_attachment']) . '" target="_blank">View Attachment (' . htmlspecialchars($file_extension) . ')</a>';
                            }
                            ?>
                        <?php else: ?>
                            <span>No message or attachment</span>
                        <?php endif; ?>
                    </div>
                    <div class="timestamp">
                        <?php echo date('d M Y, H:i', strtotime($vet['last_timestamp'])); ?>
                    </div>
                    <a href="user_chat.php?vet_id=<?php echo $vet['vet_id']; ?>">Chat Now</a>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<div class="vet-section">
    <h2>New Veterinarians</h2>
    <div class="vet-list">
        <?php if (empty($newVets)) { ?>
            <p style="text-align: center; width: 100%;">All veterinarians have been chatted with.</p>
        <?php } else { ?>
            <?php foreach ($newVets as $vet) { ?>
                <div class="vet-card">
                    <img src="vet/<?php echo htmlspecialchars($vet['profile_picture']); ?>" alt="<?php echo htmlspecialchars($vet['name']); ?>" class="vet-pic">
                    <p><?php echo htmlspecialchars($vet['name']); ?></p>
                    <a href="user_chat.php?vet_id=<?php echo $vet['vet_id']; ?>">Chat Now</a>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

</body>
</html>
