<?php
include '../connection/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    
    // Validate required fields
    if (!$id || empty($title) || empty($description) || empty($event_date)) {
        header("Location: events.php?error=invalid_input");
        exit();
    }
    
    // Handle file upload
    $image = null;
    $update_image = false;
    
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../dist/assets/images/events/";
        
        // Create uploads directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        // Generate unique filename
        $image_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $image = uniqid() . '.' . $image_ext;
        $target_file = $target_dir . $image;
        
        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($image_ext, $allowed_types)) {
            header("Location: events.php?error=invalid_file_type");
            exit();
        }
        
        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            header("Location: events.php?error=upload_failed");
            exit();
        }
        
        $update_image = true;
    }
    
    // Prepare SQL statement
    if ($update_image) {
        // Update with new image
        $sql = "UPDATE events SET title = ?, description = ?, image = ?, event_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $description, $image, $event_date, $id);
    } else {
        // Update without changing image
        $sql = "UPDATE events SET title = ?, description = ?, event_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $description, $event_date, $id);
    }
    
    // Execute and redirect
    if ($stmt->execute()) {
        header("Location: events.php?success=2");
    } else {
        header("Location: events.php?error=2");
    }
    
    $stmt->close();
    $conn->close();
    exit();
}
?>