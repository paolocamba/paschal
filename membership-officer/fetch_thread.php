<?php
session_start();
include '../connection/config.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

if (!isset($_GET['thread_id'])) {
    die("Thread ID not provided");
}

$thread_id = $_GET['thread_id'];
$user_id = $_SESSION['user_id'];

$thread_sql = "SELECT m.*, 
              u1.first_name as sender_first_name, u1.last_name as sender_last_name, u1.uploadID as sender_image,
              u2.first_name as receiver_first_name, u2.last_name as receiver_last_name, u2.uploadID as receiver_image
              FROM messages m
              JOIN users u1 ON m.sender_id = u1.user_id
              JOIN users u2 ON m.receiver_id = u2.user_id
              WHERE m.thread_id = ? OR m.message_id = ?
              ORDER BY m.sent_at DESC";

$stmt = $conn->prepare($thread_sql);
$stmt->bind_param("ii", $thread_id, $thread_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($message = $result->fetch_assoc()) {
        $is_sender = ($message['sender_id'] == $user_id);
        ?>
        <div class="thread-message mb-2 p-2 rounded <?php echo $message['is_read'] || $is_sender ? 'bg-white' : 'bg-light'; ?>">
            <div class="d-flex justify-content-between">
                <strong><?php echo htmlspecialchars($message['sender_first_name'] . ' ' . $message['sender_last_name']); ?></strong>
                <small class="text-muted"><?php echo date('M j, Y h:i A', strtotime($message['sent_at'])); ?></small>
            </div>
            <div class="message-text mt-1"><?php echo nl2br(htmlspecialchars($message['message'])); ?></div>
        </div>
        <?php
    }
} else {
    echo '<div class="alert alert-info py-1 px-2 mb-2">No previous messages in this conversation</div>';
}
?>