<?php
include '../connection/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $type = $_POST['type'];
    
    $sql = "INSERT INTO services (name, type) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $type);
    
    if ($stmt->execute()) {
        header("Location: services.php?success=1");
    } else {
        header("Location: services.php?error=1");
    }
}

?>