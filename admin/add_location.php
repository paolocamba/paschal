<?php
// add_location.php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    
    $sql = "INSERT INTO locations (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    
    if ($stmt->execute()) {
        header("Location: location.php?success=1");
    } else {
        header("Location: location.php?error=1");
    }
    exit();
}
?>
