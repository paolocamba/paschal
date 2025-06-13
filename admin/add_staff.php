<?php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate required fields
    if (!isset($_POST['member_email']) || !isset($_POST['user_type'])) {
        header("Location: users.php?error=3"); // Missing parameters
        exit();
    }

    $member_email = $_POST['member_email'];
    $user_type = $_POST['user_type'];

    try {
        // First verify the member exists and is not already staff
        $check_sql = "SELECT id, user_type FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $member_email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows === 0) {
            header("Location: users.php?error=4"); // Member not found
            exit();
        }

        $user = $result->fetch_assoc();
        if ($user['user_type'] !== 'Member') {
            header("Location: users.php?error=5"); // Already a staff member
            exit();
        }

        // Update the user to staff role
        $update_sql = "UPDATE users SET user_type = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $user_type, $member_email);

        if ($update_stmt->execute()) {
            // Success - redirect back to users list
            header("Location: users.php?success=1");
            exit();
        } else {
            throw new Exception("Execute failed: " . $update_stmt->error);
        }

    } catch (Exception $e) {
        error_log("Staff assignment error: " . $e->getMessage());
        header("Location: users.php?error=1");
        exit();
    } finally {
        if (isset($check_stmt)) $check_stmt->close();
        if (isset($update_stmt)) $update_stmt->close();
        $conn->close();
    }
} else {
    header("Location: users.php?error=2");
    exit();
}
?>