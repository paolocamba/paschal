<?php
// Include database connection
include '../connection/config.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get logged-in user ID for signature
$currentUserID = $_SESSION['user_id'] ?? null;
$signature = 'unknown';

if ($currentUserID) {
    // Fetch first and last name of logged-in user to create initials
    $stmtSig = mysqli_prepare($conn, "SELECT first_name, last_name FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($stmtSig, "i", $currentUserID);
    mysqli_stmt_execute($stmtSig);
    mysqli_stmt_bind_result($stmtSig, $firstName, $lastName);
    if (mysqli_stmt_fetch($stmtSig)) {
        $signature = strtoupper(substr($firstName, 0, 1)) . '.' . strtoupper(substr($lastName, 0, 1)) . '.';
    }
    mysqli_stmt_close($stmtSig);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $memberID = $_POST['MemberID'];
    $amount = floatval($_POST['Amount']);
    $type = $_POST['Type']; // 'Deposit' or 'Withdrawal'
    $notes = $_POST['Notes'];
    $status = $_POST['Status'];

    // Get signature as initials of logged-in user
    $currentUserID = $_SESSION['user_id'] ?? null;
    $signature = 'unknown';

    if ($currentUserID) {
        $stmtSig = mysqli_prepare($conn, "SELECT first_name, last_name FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmtSig, "i", $currentUserID);
        mysqli_stmt_execute($stmtSig);
        mysqli_stmt_bind_result($stmtSig, $firstName, $lastName);
        if (mysqli_stmt_fetch($stmtSig)) {
            $signature = strtoupper(substr($firstName, 0, 1)) . '.' . strtoupper(substr($lastName, 0, 1)) . '.';
        }
        mysqli_stmt_close($stmtSig);
    }

    try {
        mysqli_begin_transaction($conn);

        // Optional: Prevent withdrawal if insufficient balance
        if ($type === 'Withdrawal') {
            $balanceQuery = "SELECT 
                                SUM(CASE WHEN Type = 'Deposit' THEN Amount ELSE -Amount END) AS Balance
                             FROM savings 
                             WHERE MemberID = ? AND Status = 'Approved'";
            $stmt = mysqli_prepare($conn, $balanceQuery);
            mysqli_stmt_bind_param($stmt, "s", $memberID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $currentBalance = $row['Balance'] ?? 0;

            if ($amount > $currentBalance) {
                throw new Exception("Insufficient balance for withdrawal.");
            }
        }

        // 1. Insert into savings table
        $savingsQuery = "INSERT INTO savings (MemberID, Amount, Type, Notes, Status) 
                         VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $savingsQuery);
        mysqli_stmt_bind_param($stmt, "sdsss", $memberID, $amount, $type, $notes, $status);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error inserting into savings.");
        }

        // ✅ Get the newly inserted SavingsID
        $savingsID = mysqli_insert_id($conn);
        if (!$savingsID) {
            throw new Exception("Failed to retrieve SavingsID.");
        }

        // 2. Recalculate total savings for the member
        $updateBalanceQuery = "SELECT 
                                    SUM(CASE WHEN Type = 'Deposit' THEN Amount ELSE -Amount END) AS TotalSavings 
                               FROM savings 
                               WHERE MemberID = ? AND Status = 'Approved'";
        $stmt = mysqli_prepare($conn, $updateBalanceQuery);
        mysqli_stmt_bind_param($stmt, "s", $memberID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $newBalance = $row['TotalSavings'] ?? 0;

        // 3. Update users table with new savings balance
        $updateUserQuery = "UPDATE users SET savings = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $updateUserQuery);
        mysqli_stmt_bind_param($stmt, "ds", $newBalance, $memberID);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to update user savings.");
        }

        // 4. Fetch user info for appointments
        $userQuery = "SELECT first_name, last_name, email FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $userQuery);
        mysqli_stmt_bind_param($stmt, "s", $memberID);
        mysqli_stmt_execute($stmt);
        $userResult = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($userResult);

        if (!$user) {
            throw new Exception("Member not found.");
        }

        // 5. Insert appointment record
        date_default_timezone_set('Asia/Manila');
        $appointmentDate = date('Y-m-d');
        $description = "Savings $type";
        $appointmentStatus = 'Approved';

        $apptQuery = "INSERT INTO appointments (first_name, last_name, email, appointmentdate, user_id, status, description, SavingsID) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $apptQuery);
        mysqli_stmt_bind_param($stmt, "sssssssi", 
            $user['first_name'], 
            $user['last_name'], 
            $user['email'], 
            $appointmentDate, 
            $memberID, 
            $appointmentStatus, 
            $description,
            $savingsID
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error inserting appointment.");
        }

        // 6. Generate new control number
        $controlQuery = "SELECT MAX(CAST(control_number AS UNSIGNED)) AS max_number FROM transactions";
        $controlResult = mysqli_query($conn, $controlQuery);
        $controlRow = mysqli_fetch_assoc($controlResult);
        $nextControl = str_pad(($controlRow['max_number'] ?? 0) + 1, 5, '0', STR_PAD_LEFT);

        // 7. Insert transaction record with SavingsID ✅
        $serviceName = "Savings $type";
        $paymentStatus = 'Completed';

        $transQuery = "INSERT INTO transactions 
            (user_id, service_name, amount, control_number, payment_status, signature, SavingsID) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $transQuery);
        mysqli_stmt_bind_param($stmt, "ssdsssi", 
            $memberID, 
            $serviceName, 
            $amount, 
            $nextControl, 
            $paymentStatus, 
            $signature,
            $savingsID
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error inserting transaction.");
        }

        // All good
        mysqli_commit($conn);
        header("Location: savings.php?success=1");
        exit();

    } catch (Exception $e) {
        mysqli_rollback(mysql: $conn);
        header("Location: savings.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

mysqli_close($conn);
?>
