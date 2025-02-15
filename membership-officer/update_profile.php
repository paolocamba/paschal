<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../connection/config.php';

$user_id = $_SESSION['user_id'];

// Update user information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $email = $_POST['email'] ?? '';
    $street = $_POST['street'] ?? '';
    $barangay = $_POST['barangay'] ?? '';
    $municipality = $_POST['municipality'] ?? '';
    $province = $_POST['province'] ?? '';

    // Update profile information
    $sql = "UPDATE users SET 
            first_name = ?, 
            last_name = ?, 
            mobile = ?, 
            email = ?, 
            street = ?, 
            barangay = ?, 
            municipality = ?, 
            province = ? 
            WHERE user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", 
        $first_name, 
        $last_name, 
        $mobile, 
        $email, 
        $street, 
        $barangay, 
        $municipality, 
        $province, 
        $user_id
    );

    if (!$stmt->execute()) {
        echo "Error updating profile information: " . $stmt->error;
    }
    $stmt->close();

    // Handle profile picture upload
    if (isset($_FILES['uploadID']) && $_FILES['uploadID']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['uploadID']['tmp_name'];
        $file_name = basename($_FILES['uploadID']['name']);
        $upload_dir = '../dist/assets/images/user/';
        
        // Generate a unique filename to prevent overwriting
        $unique_filename = uniqid() . '_' . $file_name;
        $upload_file = $upload_dir . $unique_filename;

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($file_tmp, $upload_file)) {
            // Update the database with the new profile image
            $sql = "UPDATE users SET uploadID = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $unique_filename, $user_id);
            
            if (!$stmt->execute()) {
                echo "Error updating profile image: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error uploading file.";
        }
    }

    // Handle password change
    if (!empty($_POST['old_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            // Verify the old password
            $sql = "SELECT password FROM users WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $hashed_password = $row['password'];

                // Check if the old password is correct
                if (password_verify($old_password, $hashed_password)) {
                    // Hash the new password and update it
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET password = ? WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $new_hashed_password, $user_id);
                    
                    if (!$stmt->execute()) {
                        echo "Error updating password: " . $stmt->error;
                    } else {
                        echo "Password updated successfully.";
                    }
                } else {
                    echo "Old password is incorrect.";
                }
            }
            $stmt->close();
        } else {
            echo "New passwords do not match.";
        }
    }

    // Redirect to the profile page
    header("Location: settings.php?success=1");
    exit();
}

// Fetch current user data for display
$sql = "SELECT first_name, last_name, mobile, email, uploadID, street, barangay, municipality, province FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $mobile = $row['mobile'];
    $email = $row['email'];
    $uploadID = $row['uploadID'] ?? 'default_image.jpg';
    $street = $row['street'] ?? '';
    $barangay = $row['barangay'] ?? '';
    $municipality = $row['municipality'] ?? '';
    $province = $row['province'] ?? '';
} else {
    $first_name = $last_name = $mobile = $email = $street = $barangay = $municipality = $province = '';
    $uploadID = 'default_image.jpg';
}
$stmt->close();
$conn->close();
?>