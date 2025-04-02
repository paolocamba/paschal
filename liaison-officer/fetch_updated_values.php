<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "pascal");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Database connection failed."]));
}

if (!isset($_GET['LoanID'])) {
    echo json_encode(["success" => false, "error" => "LoanID is required."]);
    exit;
}

$loanID = intval($_GET['LoanID']);
$query = "SELECT final_zonal_value, EMV_per_sqm, total_value, loanable_amount FROM land_appraisal WHERE LoanID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $loanID);
$stmt->execute();
$result = $stmt->get_result();

if ($data = $result->fetch_assoc()) {
    echo json_encode(["success" => true, "data" => $data]);
} else {
    echo json_encode(["success" => false, "error" => "Loan not found."]);
}

$stmt->close();
$conn->close();
?>
