<?php
include '../connection/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    
    $sql = "UPDATE services SET name = ?, type = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $type, $id);
    
    if ($stmt->execute()) {
        header("Location: services.php?success=2");
    } else {
        header("Location: services.php?error=2");
    }
}
?>