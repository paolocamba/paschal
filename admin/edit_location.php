<?php
// edit_location.php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    
    $sql = "UPDATE locations SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $id);
    
    if ($stmt->execute()) {
        header("Location: location.php?success=2");
    } else {
        header("Location: location.php?error=1");
    }
    exit();
}
?>

