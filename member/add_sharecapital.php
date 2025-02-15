<?php
session_start();
require_once '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memberID = $_SESSION['user_id'];
    $amount = floatval($_POST['amount']);
    $paymentMethod = $_POST['payment_method'];
    $notes = $_POST['notes'] ?? '';
    $status = 'In Progress';
    
    $sql = "INSERT INTO share_capital (MemberID, Amount, PaymentMethod, Notes, Status) 
            VALUES (?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsss", $memberID, $amount, $paymentMethod, $notes, $status);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: home.php?success=2");
        exit();
    } else {
        $stmt->close();
        header("Location: home.php?error=2");
        exit();
    }
}
?>