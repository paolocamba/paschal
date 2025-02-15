<?php
include '../connection/config.php';

// Handle form submission for updating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $day = $_POST['day'];

    // Handle file upload
    $image = null;
    $update_image = false;

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../dist/assets/images/announcements/";
        
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
            header("Location: announcement.php?error=invalid_file_type");
            exit();
        }
        
        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            header("Location: announcement.php?error=upload_failed");
            exit();
        }
        
        $update_image = true;
    }

    // Prepare SQL statement
    if ($update_image) {
        // Update with new image
        $sql = "UPDATE announcements SET name = ?, date = ?, day = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $date, $day, $image, $id);
    } else {
        // Update without changing image
        $sql = "UPDATE announcements SET name = ?, date = ?, day = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $date, $day, $id);
    }

    // Execute and redirect
    if ($stmt->execute()) {
        header("Location: announcement.php?success=2");
    } else {
        header("Location: announcement.php?error=2");
    }
    $stmt->close();
    $conn->close();
    exit();
}
?>