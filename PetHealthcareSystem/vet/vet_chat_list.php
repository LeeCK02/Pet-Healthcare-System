<?php
include 'vet_header.php';

$vet_id = $_SESSION['vet_id']; 

// Search functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch the list of users who have chatted with this veterinarian along with the last message
$query = "
    SELECT u.user_id, u.username, u.profile_pic, c.message, c.timestamp, c.sender_type, c.attachment
    FROM user u
    JOIN consultation c ON u.user_id = c.user_id
    WHERE c.vet_id = '$vet_id'
    AND c.timestamp = (
        SELECT MAX(timestamp)
        FROM consultation
        WHERE user_id = u.user_id AND vet_id = '$vet_id'
    )
";
if ($search) {
    $query .= " AND u.username LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}
$query .= " ORDER BY c.timestamp DESC"; // Sort by the newest message received

$users = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #0047ab;
            margin-top: 30px;
        }

        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-container input {
            padding: 8px;
            width: 300px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .user-list {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .user-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 10px;
            width: 45%;
            text-align: left;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .user-card:hover {
            transform: scale(1.05);
        }

        .user-card img {
            max-width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-card a {
            text-decoration: none;
            color: #0047ab;
            font-weight: bold;
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
            .user-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<h1>Chat with Users</h1>

<div class="search-container">
    <form method="GET" action="vet_chat_list.php">
        <input type="text" name="search" placeholder="Search user by name" value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>
</div>

<div class="user-list">
    <?php while ($user = mysqli_fetch_assoc($users)) { ?>
        <div class="user-card">
            <div style="display: flex; align-items: center;">
                <img src="../<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture">
                <p><?php echo htmlspecialchars($user['username']); ?></p>
            </div>
            <div class="last-message">
                <a>Last Message:</a><br>
                <span class="sender"><?php echo $user['sender_type'] === 'user' ? $user['username'] : 'You'; ?>:</span> 
                <?php if ($user['message']): ?>
                    <?php echo $user['message']; ?>
                <?php elseif ($user['attachment']): ?>
                    <?php
                    // Check the file extension
                    $file_extension = pathinfo($user['attachment'], PATHINFO_EXTENSION);
                    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        // Display image attachments
                        echo '<a href="../' . htmlspecialchars($user['attachment']) . '" target="_blank"><img src="../' . htmlspecialchars($user['attachment']) . '" alt="Attachment" style="max-width: 100px; border-radius: 5px;"></a>';
                    } else {
                        // Display other attachments including PDFs as a generic download link
                        echo '<a href="../' . htmlspecialchars($user['attachment']) . '" target="_blank">View Attachment (' . htmlspecialchars($file_extension) . ')</a>';
                    }
                    ?>
                <?php else: ?>
                    <span>No message or attachment</span>
                <?php endif; ?>
                <?php if ($user['sender_type'] === 'user'): ?>
                    <span style="color: red; font-weight: bold;"> - Unreplied</span>
                <?php endif; ?>
            </div>
            <div class="timestamp">
                <?php echo date('d M Y, H:i', strtotime($user['timestamp'])); ?>
            </div>
            <a href="vet_chat.php?user_id=<?php echo $user['user_id']; ?>">Chat Now</a>
        </div>
    <?php } ?>
</div>

</body>
</html>
