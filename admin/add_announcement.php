<?php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $date = $_POST['date'];
    $day = $_POST['day'];  // Capture the day of the week

    // Handle file upload
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../dist/assets/images/announcements/";
        
        // Ensure the directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $image = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image;
        
        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Handle file upload error
            header("Location: announcement.php?error=file_upload");
            exit();
        }
    }

    // Prepare and execute the SQL statement
    $sql = "INSERT INTO announcements (name, date, day, image, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    
    // Correct the order of bind_param
    $stmt->bind_param("ssss", $name, $date, $day, $image);

    if ($stmt->execute()) {
        // Redirect back to the announcements page with success message
        header("Location: announcement.php?success=1");
        exit();
    } else {
        // Redirect back to the announcements page with error message
        header("Location: announcement.php?error=db_insert");
        exit();
    }
}
?>