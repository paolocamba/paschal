<?php
session_start();
require '../connection/config.php';

// Staff must be logged in
if (!isset($_SESSION['staff_id'])) {
    die(json_encode(["success" => false, "message" => "Unauthorized access"]));
}

// Get POST data
$loanId = (int)$_POST['loan_id'];
$memberId = (int)$_POST['member_id'];
$amountPaid = (float)$_POST['amount'];
$receiptNumber = $_POST['receipt_no'];
$staffId = (int)$_SESSION['staff_id'];

// Begin transaction
$conn->begin_transaction();

try {
    // 1. Verify loan exists and get details
    $loanQuery = "SELECT PayableAmount, PayableDate FROM credit_history 
                  WHERE LoanID = ? AND MemberID = ?";
    $stmt = $conn->prepare($loanQuery);
    $stmt->bind_param("ii", $loanId, $memberId);
    $stmt->execute();
    $loan = $stmt->get_result()->fetch_assoc();

    if (!$loan) {
        throw new Exception("Loan not found or does not belong to member");
    }

    // 2. Determine payment status
    $dueDate = $loan['PayableDate'];
    $isLate = (date('Y-m-d') > $dueDate) ? 'Late' : 'Completed';
    $isPartial = ($amountPaid < $loan['PayableAmount']) ? 'Partial' : 'Completed';
    
    // Store the final status in a variable
    $paymentStatus = ($isPartial === 'Partial') ? $isPartial : $isLate;

    // 3. Record in transactions table first (to get transaction_id)
    $transactionQuery = "INSERT INTO transactions (
        user_id, service_name, amount, payment_status, control_number
    ) VALUES (?, 'Loan Payment', ?, 'Completed', ?)";
    $stmt = $conn->prepare($transactionQuery);
    $stmt->bind_param("ids", $memberId, $amountPaid, $receiptNumber);
    $stmt->execute();
    $transactionId = $conn->insert_id;

    // 4. Record in loan_payments
    $paymentQuery = "INSERT INTO loan_payments (
        transaction_id, LoanID, MemberID, amount_paid, receipt_number,
        payment_status, recorded_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($paymentQuery);
    $stmt->bind_param("iiidssi", 
        $transactionId, $loanId, $memberId, $amountPaid, 
        $receiptNumber, $paymentStatus, 
        $staffId
    );
    $stmt->execute();

    // 5. Update loan status if fully paid
    if ($isPartial === 'Completed') {
        $updateLoan = "UPDATE credit_history SET Status = 'Paid' 
                      WHERE LoanID = ?";
        $stmt = $conn->prepare($updateLoan);
        $stmt->bind_param("i", $loanId);
        $stmt->execute();
    }

    $conn->commit();
    echo json_encode(["success" => true, "message" => "Payment recorded successfully"]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}