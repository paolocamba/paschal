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
    // Fetch initials
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
    $notes = $_POST['Notes'];
    $status = $_POST['Status'];

    try {
        mysqli_begin_transaction($conn);

        // 1. Insert into share_capital table (only deposits)
        $insertCapitalQuery = "INSERT INTO share_capital (MemberID, Amount, Notes, Status) 
                               VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertCapitalQuery);
        mysqli_stmt_bind_param($stmt, "sdss", $memberID, $amount, $notes, $status);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error inserting into share_capital.");
        }

        // ✅ 2. Get the newly inserted ShareCapitalID
        $shareCapitalID = mysqli_insert_id($conn);
        if (!$shareCapitalID) {
            throw new Exception("Failed to retrieve ShareCapitalID.");
        }

        // 3. Recalculate total share capital for the member
        $sumCapitalQuery = "SELECT SUM(Amount) AS TotalCapital 
                            FROM share_capital 
                            WHERE MemberID = ? AND Status = 'Approved'";
        $stmt = mysqli_prepare($conn, $sumCapitalQuery);
        mysqli_stmt_bind_param($stmt, "i", $memberID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $newCapitalBalance = $row['TotalCapital'] ?? 0;

        // 4. Update user's share capital balance
        $updateUserCapitalQuery = "UPDATE users SET share_capital = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $updateUserCapitalQuery);
        mysqli_stmt_bind_param($stmt, "di", $newCapitalBalance, $memberID);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to update user's share capital.");
        }

        // 5. Fetch user info for appointment
        $userQuery = "SELECT first_name, last_name, email FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $userQuery);
        mysqli_stmt_bind_param($stmt, "i", $memberID);
        mysqli_stmt_execute($stmt);
        $userResult = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($userResult);

        if (!$user) {
            throw new Exception("Member not found.");
        }

        // 6. Insert appointment record
        date_default_timezone_set('Asia/Manila');
        $appointmentDate = date('Y-m-d');
        $description = "Share Capital Deposit";
        $appointmentStatus = 'Approved';

        $apptQuery = "INSERT INTO appointments 
            (first_name, last_name, email, appointmentdate, user_id, status, description, ShareCapitalID) 
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
            $shareCapitalID
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error inserting appointment.");
        }

        // 7. Generate control number
        $controlQuery = "SELECT MAX(CAST(control_number AS UNSIGNED)) AS max_number FROM transactions";
        $controlResult = mysqli_query($conn, $controlQuery);
        $controlRow = mysqli_fetch_assoc($controlResult);
        $nextControl = str_pad(($controlRow['max_number'] ?? 0) + 1, 5, '0', STR_PAD_LEFT);

        // 8. Insert transaction record with ShareCapitalID
        $serviceName = "Share Capital Deposit";
        $paymentStatus = 'Completed';

        $transQuery = "INSERT INTO transactions 
            (user_id, service_name, amount, control_number, payment_status, signature, ShareCapitalID) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $transQuery);
        mysqli_stmt_bind_param($stmt, "ssdsssi", 
            $memberID, 
            $serviceName, 
            $amount, 
            $nextControl, 
            $paymentStatus, 
            $signature,
            $shareCapitalID
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error inserting transaction.");
        }

        // ✅ Commit all
        mysqli_commit($conn);
        header("Location: sharecapital.php?success=2");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        header("Location: sharecapital.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

mysqli_close($conn);
?>
