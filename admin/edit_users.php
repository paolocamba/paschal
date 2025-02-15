<?php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $street = $_POST['street'];
    $barangay = $_POST['barangay'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];
    $mobile = $_POST['mobile'];
    $user_type = $_POST['user_type']; // Add this line

    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, username = ?, street = ?, barangay = ?, municipality = ?, province = ?, mobile = ?, user_type = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $first_name, $last_name, $email, $username, $street, $barangay, $municipality, $province, $mobile, $user_type, $id); // Bind user_type

    if ($stmt->execute()) {
        // Redirect back to the page where the user list is displayed
        header("Location: users.php?success=2");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
