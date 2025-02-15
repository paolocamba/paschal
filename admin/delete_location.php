<?php
// delete_location.php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    $sql = "DELETE FROM locations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: location.php?success=3");
    } else {
        header("Location: location.php?error=1");
    }
    exit();
}
?>