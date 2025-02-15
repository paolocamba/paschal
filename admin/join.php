<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pascal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Correct SQL query
$sql = "SELECT loanapplication.LoanID, land_appraisal.loanable_amount 
        FROM loanapplication 
        JOIN land_appraisal ON loanapplication.LoanID = land_appraisal.LoanID"; // Ensure both tables share a LoanID column

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Loan ID: " . $row["LoanID"]. " - Loanable Amount: " . $row["loanable_amount"]. "<br>";
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>
