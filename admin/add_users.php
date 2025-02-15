<?php
include '../connection/config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $street = $_POST['street'];
    $barangay = $_POST['barangay'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, last_name, email, username, street, barangay, municipality, province, mobile, password, status, user_type) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $first_name, $last_name, $email, $username, $street, $barangay, $municipality, $province, $mobile, $hashed_password, $user_type);

    if ($stmt->execute()) {
        $new_user_id = $stmt->insert_id; // Get the last inserted ID
        // Now update the same record to set user_id to new_user_id
        $update_sql = "UPDATE users SET user_id = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_user_id, $new_user_id);


        
       
        if ($update_stmt->execute()) {
            header("Location: users.php?success=1");
        } else {
            echo "Error updating record: " . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
