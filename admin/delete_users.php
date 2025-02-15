<?php
include '../connection/config.php';
session_start(); // Start the session

// Log activity function
function logActivity($user_id, $user_type, $activity) {
    global $conn;
    $sql = "INSERT INTO activity_logs (user_id, user_type, action, details) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $user_type, $activity, $activity);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Log activity
        logActivity($_SESSION['user_id'], $_SESSION['user_type'], "Deleted Users:");
        // Redirect back to the page where the user list is displayed
        header("Location: users.php?success=3");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
