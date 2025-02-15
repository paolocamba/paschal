<?php
include '../connection/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    
    // Handle file upload
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../dist/assets/images/events/";
        $image = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }
    
    $sql = "INSERT INTO events (title, description, image, event_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $description, $image, $event_date);
    
    if ($stmt->execute()) {
        header("Location: events.php?success=1");
    } else {
        header("Location: events.php?error=1");
    }
    exit();
}
?>