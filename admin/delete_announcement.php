<?php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $id = $_POST['id'];

    // Prepare and execute the SQL statement
    $sql = "DELETE FROM announcements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to the announcements page with success message
        header("Location: announcement.php?success=3");
        exit();
    } else {
        // Redirect back to the announcements page with error message
        header("Location: announcement.php?error=3");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly without POST method
    header("Location: announcement.php");
    exit();
}
?>