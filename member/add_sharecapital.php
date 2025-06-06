<?php
session_start();
require_once '../connection/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$memberID = intval($_SESSION['user_id']); // Ensure user_id is an integer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $notes = $_POST['notes'] ?? '';
    $appointmentDate = $_POST['appointment_date'];
    $status = 'In Progress';
    $description = 'Share Capital Deposit';
    $serviceID = 15; // Share Capital Deposit ID

    // Validate appointment date format (YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('Y-m-d', $appointmentDate);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $appointmentDate) {
        header("Location: home.php?error=invalid_date");
        exit();
    }

    // Insert into share_capital table
    $sqlCapital = "INSERT INTO share_capital (MemberID, Amount, Notes, Status) 
                   VALUES (?, ?, ?, ?)";
    $stmtCapital = $conn->prepare($sqlCapital);
    $stmtCapital->bind_param("sdss", $memberID, $amount, $notes, $status);

    if ($stmtCapital->execute()) {
        $shareCapitalID = $conn->insert_id;
        $stmtCapital->close();

        // Fetch user info
        $sqlUser = "SELECT first_name, last_name, email FROM users WHERE user_id = ?";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bind_param("i", $memberID);
        $stmtUser->execute();
        $stmtUser->bind_result($firstName, $lastName, $email);

        if ($stmtUser->fetch()) {
            $stmtUser->close();

            $statusAppointment = 'Pending';

            // Insert into appointments table with ShareCapitalID
            $sqlAppointments = "INSERT INTO appointments 
                (user_id, serviceID, description, payable_amount, appointmentdate, status, first_name, last_name, email, ShareCapitalID) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtAppointments = $conn->prepare($sqlAppointments);
            $stmtAppointments->bind_param(
                "iisdsssssi",
                $memberID,
                $serviceID,
                $description,
                $amount,
                $appointmentDate,
                $statusAppointment,
                $firstName,
                $lastName,
                $email,
                $shareCapitalID
            );

            if ($stmtAppointments->execute()) {
                $stmtAppointments->close();
                header("Location: home.php?success=2");
                exit();
            } else {
                $stmtAppointments->close();
                header("Location: home.php?error=appointment_fail");
                exit();
            }
        } else {
            $stmtUser->close();
            header("Location: home.php?error=user_fetch_fail");
            exit();
        }
    } else {
        $stmtCapital->close();
        header("Location: home.php?error=sharecapital_fail");
        exit();
    }
}
?>
