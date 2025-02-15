<?php
// get_passbook_data.php
session_start();
include '../connection/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_GET['type'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$type = $_GET['type'];
$userId = $_SESSION['user_id'];

try {
    $sql = "";
    if ($type === 'savings') {
        $sql = "SELECT s.TransactionDate, t.control_number, t.service_name, s.Amount, t.signature
                FROM savings s
                INNER JOIN transactions t ON s.MemberID = t.user_id
                WHERE s.MemberID = ?
                ORDER BY s.TransactionDate DESC";
    } else if ($type === 'share_capital') {
        $sql = "SELECT sc.TransactionDate, t.control_number, t.service_name, sc.Amount, t.signature
                FROM share_capital sc
                INNER JOIN transactions t ON sc.MemberID = t.user_id
                WHERE sc.MemberID = ?
                ORDER BY sc.TransactionDate DESC";
    } else {
        throw new Exception('Invalid type specified');
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>