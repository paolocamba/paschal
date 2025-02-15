<?php
include '../connection/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    if (!$id) {
        header("Location: events.php?error=3");
        exit();
    }
    
    // First, retrieve the image filename to delete the file
    $select_sql = "SELECT image FROM events WHERE id = ?";
    $select_stmt = $conn->prepare($select_sql);
    $select_stmt->bind_param("i", $id);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    $event = $result->fetch_assoc();
    $select_stmt->close();
    
    // Delete database record
    $delete_sql = "DELETE FROM events WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $id);
    
    if ($delete_stmt->execute()) {
        // Delete associated image file if it exists
        if (!empty($event['image']) && file_exists("../dist/assets/images/events" . $event['image'])) {
            unlink("uploads/" . $event['image']);
        }
        
        header("Location: events.php?success=3");
    } else {
        header("Location: events.php?error=3");
    }
    
    $delete_stmt->close();
    $conn->close();
    exit();
}
?>