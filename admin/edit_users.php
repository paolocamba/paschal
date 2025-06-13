<?php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    if (!isset($_POST['id']) || !isset($_POST['user_type'])) {
        header("Location: users.php?error=3"); // Missing parameters
        exit();
    }

    $id = (int)$_POST['id'];
    $user_type = $_POST['user_type'];

    try {
        // Update only the user_type
        $sql = "UPDATE users SET user_type = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        if (!$stmt->bind_param("si", $user_type, $id)) {
            throw new Exception("Bind failed: " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        header("Location: users.php?success=1");
        exit();
        
    } catch (Exception $e) {
        error_log("User edit error: " . $e->getMessage());
        header("Location: users.php?error=1");
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        $conn->close();
    }
} else {
    header("Location: users.php?error=2");
    exit();
}
?>