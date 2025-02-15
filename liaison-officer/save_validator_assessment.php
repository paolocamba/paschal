<?php
require_once '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $loanId = $_POST['loan_id'] ?? null;
        if (!$loanId) {
            throw new Exception('Loan ID is required');
        }

        $conn->begin_transaction();
        
        // Get userID from loanapplication table
        $userSql = "SELECT userID FROM loanapplication WHERE LoanID = ?";
        $userStmt = $conn->prepare($userSql);
        $userStmt->bind_param("s", $loanId);
        $userStmt->execute();
        $result = $userStmt->get_result();
        
        if (!$result || !($userId = $result->fetch_assoc()['userID'])) {
            throw new Exception('User ID not found');
        }

        // Delete existing records for this loan ID to avoid duplicates
        $deleteSql = "DELETE FROM land_appraisal WHERE LoanID = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $loanId);
        $deleteStmt->execute();

        // Prepare numeric values
        $squareMeters = floatval($_POST['validator_square_meters']);
        $finalZonalValue = floatval($_POST['final_zonal_value']);
        $emvPerSqm = floatval($_POST['emv_per_sqm']);
        $totalValue = floatval($_POST['total_value']);
        $loanableValue = floatval($_POST['loanable_value']);

        // Insert into land_appraisal table
        $sql = "INSERT INTO land_appraisal (
            LoanID,
            userID,
            square_meters,
            type_of_land,
            location,
            right_of_way,
            hospital,
            clinic,
            school,
            market,
            church,
            public_terminal,
            final_zonal_value,
            EMV_per_sqm,
            total_value,
            loanable_amount,
            image_path
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Handle image upload first
        $imagePath = null;
        if (!empty($_FILES['property_images']['name'][0])) {
            $uploadPath = '../dist/assets/images/collateral/';
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $fileName = $loanId . '_' . time() . '_' . basename($_FILES['property_images']['name'][0]);
            $targetFile = $uploadPath . $fileName;
            
            if (move_uploaded_file($_FILES['property_images']['tmp_name'][0], $targetFile)) {
                $imagePath = '../dist/assets/images/collateral/' . $fileName;
            }
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iidsssssssssdddds",
            $loanId,
            $userId,
            $squareMeters,
            $_POST['validator_land_type'],
            $_POST['validator_location'],
            $_POST['validator_right_of_way'],
            $_POST['validator_hospital'],
            $_POST['validator_clinic'],
            $_POST['validator_school'],
            $_POST['validator_market'],
            $_POST['validator_church'],
            $_POST['validator_public_terminal'],
            $finalZonalValue,
            $emvPerSqm,
            $totalValue,
            $loanableValue,
            $imagePath
        );

        if (!$stmt->execute()) {
            throw new Exception('Failed to insert data: ' . $stmt->error);
        }

        $conn->commit();
        echo json_encode(['success' => true]);
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false, 
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}