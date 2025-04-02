<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Get LoanID from GET request
$LoanID = isset($_GET['LoanID']) ? (int)$_GET['LoanID'] : null;

if (!$LoanID) {
    echo json_encode(['success' => false, 'error' => 'LoanID parameter is required']);
    exit();
}

// Prepare JSON data
$data = json_encode(['loan_id' => $LoanID]);

// Call the Flask API
$apiUrl = 'http://127.0.0.1:5000/run-model';
$ch = curl_init($apiUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);  // Increase timeout
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);

if ($response === false) {
    echo json_encode(['success' => false, 'error' => 'CURL error: ' . curl_error($ch)]);
} else {
    echo $response;
}

curl_close($ch);
?>
