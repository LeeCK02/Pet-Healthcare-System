<?php
include 'vet_header.php';

$vet_id = $_SESSION['vet_id']; 
$user_id = $_GET['user_id'];

// Fetch user's name
$user_result = mysqli_query($conn, "SELECT username FROM user WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($user_result);
$user_name = $user['username'];

// Fetch previous chat messages
$messages = mysqli_query($conn, "
    SELECT * FROM consultation
    WHERE user_id = '$user_id' AND vet_id = '$vet_id'
    ORDER BY timestamp ASC
");

// Handle new message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attachment = $_FILES['attachment'];
    $message = trim($_POST['message'] ?? ''); // Get the text message and trim whitespace

    // Initialize the file path variable
    $file_path = null;

    // Check if a file was uploaded
    if ($attachment['error'] == UPLOAD_ERR_OK) {
        // Define a directory to store uploaded files
        $upload_dir = '../uploads/'; 
        $file_name = basename($attachment['name']);
        $unique_file_name = uniqid() . '-' . $file_name; // unique ID to avoid file name conflicts
        $file_path = $upload_dir . $unique_file_name; // Full path to store the file

        // Move the uploaded file to the designated directory
        if (move_uploaded_file($attachment['tmp_name'], $file_path)) {
            // File uploaded successfully, save only the path to the database
            mysqli_query($conn, "
                INSERT INTO consultation (user_id, vet_id, sender_type, message, attachment)
                VALUES ('$user_id', '$vet_id', 'vet', NULL, 'uploads/$unique_file_name')
            ");
        } else {
            // Handle the error if the file cannot be moved
            echo "Error uploading the file.";
        }
    }



    // If no attachment was uploaded, save the text message if it exists
    if (!empty($message)) {
        mysqli_query($conn, "
            INSERT INTO consultation (user_id, vet_id, sender_type, message, attachment)
            VALUES ('$user_id', '$vet_id', 'vet', '$message', NULL)
        ");
    }

    // Check if either a message or an attachment was sent
    if (empty($message) && $attachment['error'] != UPLOAD_ERR_OK) {
        echo "<script>alert('You must send a message or an attachment.');</script>";
    } else {
        header("Location: vet_chat.php?user_id=$user_id"); // Refresh the page to load new messages
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($user_name); ?></title>
    <style>

        .chat-container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: white;
            height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background-color: #0047ab;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f0f0f0;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            width: fit-content;
        }

        .message.user {
            background-color: #ddd;
            align-self: flex-start; /* User messages on the left */
        }

        .message.vet {
            background-color: #0047ab;
            color: white;
            align-self: flex-end; /* Vet messages on the right */
        }

        .chat-footer {
            padding: 10px;
            display: flex;
            background-color: #fff;
            border-top: 1px solid #ddd;
        }

        .chat-footer input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-right: 10px;
        }

        .chat-footer input[type="file"] {
            margin-right: 10px;
        }

        .chat-footer button {
            padding: 10px 20px;
            background-color: #0047ab;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">
        Chat with <?php echo htmlspecialchars($user_name); ?>
    </div>

    <div class="chat-messages" id="chat-messages">
        <?php while ($message = mysqli_fetch_assoc($messages)) { ?>
            <div class="message <?php echo $message['sender_type']; ?>">
                <?php if ($message['message']): ?>
                    <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                <?php endif; ?>
                <br><small><?php echo htmlspecialchars($message['timestamp']); ?></small>
                <?php if ($message['attachment']): ?>
                    <br>
                    <?php if (strpos($message['attachment'], '.jpg') !== false || strpos($message['attachment'], '.png') !== false): ?>
                        <img src="../<?php echo htmlspecialchars($message['attachment']); ?>" alt="Attachment" style="max-width: 200px; border-radius: 5px;">
                    <?php else: ?>
                        <a href="../<?php echo htmlspecialchars($message['attachment']); ?>" target="_blank">View Attachment</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php } ?>
    </div>

    <div class="chat-footer">
        <form method="post" action="" enctype="multipart/form-data">
            <input type="text" name="message" placeholder="Type a message">
            <input type="file" name="attachment" accept="image/*,.pdf,.doc,.docx,.ppt,.pptx,.txt" id="attachment">
            <button type="submit">Send</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');

    // Scroll to the bottom on page load
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Check for user scroll
    let isUserScrolling = false;

    chatMessages.addEventListener('scroll', function() {
        // Check if the user has scrolled away from the bottom
        isUserScrolling = chatMessages.scrollTop + chatMessages.clientHeight < chatMessages.scrollHeight;
    });

    // Function to scroll to bottom when a new message is added
    function scrollToBottom() {
        if (!isUserScrolling) {
            chatMessages.scrollTop = chatMessages.scrollHeight; // Only scroll if user hasn't scrolled up
        }
    }

    // Call scrollToBottom whenever new messages are added dynamically (if needed)
});
</script>



</body>
</html>