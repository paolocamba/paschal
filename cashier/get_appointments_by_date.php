<?php
session_start();
include '../connection/config.php';

// Check if date parameter is provided
if (!isset($_GET['date'])) {
    die("Date parameter is required");
}

$date = $_GET['date'];

try {
    // Query to fetch appointments for the selected date
    $sql = "SELECT id, first_name, last_name, email, description, appointmentdate, status 
            FROM appointments 
            WHERE DATE(appointmentdate) = ?
            ORDER BY 
                CASE WHEN status = 'Pending' THEN 0 ELSE 1 END,
                appointmentdate ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>';
            echo '<div class="user-info">';
            echo '<strong>' . htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']) . '</strong>';
            echo '<span class="user-email">' . htmlspecialchars($row['email']) . '</span>';
            echo '</div>';
            echo '</td>';
            echo '<td>' . htmlspecialchars($row['description']) . '</td>';
            echo '<td>' . date('h:i A', strtotime($row['appointmentdate'])) . '</td>';
            echo '<td><span class="badge badge-' . ($row['status'] == 'Pending' ? 'warning' : ($row['status'] == 'Approved' ? 'success' : 'danger')) . ' text-white">' . htmlspecialchars($row['status']) . '</span></td>';
            echo '<td><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewModal' . $row['id'] . '"><i class="fa-solid fa-eye"></i></button></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5" class="text-center">No appointments found for this date</td></tr>';
    }
} catch (Exception $e) {
    echo '<tr><td colspan="5" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
}
?>