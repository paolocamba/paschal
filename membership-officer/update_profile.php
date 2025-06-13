<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../connection/config.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
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

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully";
    } else {
        $_SESSION['error'] = "Error updating profile information: " . $stmt->error;
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

        // Validate file type and size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($_FILES['uploadID']['type'], $allowed_types)) {
            $_SESSION['error'] = "Invalid file type. Only JPG, PNG, and GIF are allowed";
        } elseif ($_FILES['uploadID']['size'] > $max_size) {
            $_SESSION['error'] = "File too large. Maximum size is 2MB";
        } elseif (move_uploaded_file($file_tmp, $upload_file)) {
            // Update the database with the new profile image
            $sql = "UPDATE users SET uploadID = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $unique_filename, $user_id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Profile picture updated successfully";
                $_SESSION['uploadID'] = $unique_filename; // Update session variable
            } else {
                $_SESSION['error'] = "Error updating profile image: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Error uploading file";
        }
    }

    header("Location: settings.php");
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