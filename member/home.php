<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

include '../connection/config.php';

// Fetch the user's data from the "users" table based on the user ID
$id = $_SESSION['user_id'];
$sql = "SELECT username, first_name, last_name, mobile, email, street, barangay, municipality, province, uploadID, membership_type, is_logged_in FROM users WHERE user_id = '$id'";
$result = $conn->query($sql);

// Check if the query was successful
if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $name = $row["username"];
  $uploadID = $row["uploadID"];
  $first_name = $row["first_name"];
  $last_name = $row["last_name"];
  $mobile = $row["mobile"];
  $email = $row["email"];
  $street = $row["street"];
  $barangay = $row["barangay"];
  $municipality = $row["municipality"];
  $province = $row["province"];
  $membership_type = $row["membership_type"];

} else {
  // Default values if user data is not found
  $username = "Guest";
  $uploadID = "default_image.jpg"; // Assuming default image name
  $first_name = "";
  $last_name = "";
  $mobile = "";
  $email = "";
  $street = "";
  $barangay = "";
  $municipality = "";
  $province = "";
  $membership_type = "";
}

// Assign the value of $username and $image to $_SESSION variables
$_SESSION['membership_type'] = $row['membership_type'];
$_SESSION['uploadID'] = $uploadID;
$_SESSION['is_logged_in'] = $row['is_logged_in']; // Add this line
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard | Member</title>
  
  <!-- plugins:css -->
  <link rel="stylesheet" href="../dist/assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../dist/assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
  
  <!-- FONTAWESOME CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../dist/assets/js/select.dataTables.min.css">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- inject:css -->
   <link rel="stylesheet" href="../dist/assets/css/style.css">
  
  <!-- jQuery FIRST (required for Bootstrap) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- Then Bootstrap Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
      .nav-link i {
          margin-right: 10px;
      }

      .btn-primary {
          background-color: #03C03C !important;
      }

      .btn-primary:hover {
          background-color: #00563B !important;
      }

      .btn-outline-primary:hover {
          background-color: #00563B !important;
      }

      /* Custom styles for the search bar */
      .input-group {
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
          border-radius: 0.5rem;
          overflow: hidden;
      }

      .input-group .form-control {
          border: none;
          padding: 1rem 1.5rem;
      }

      .input-group .btn {
          padding: 0.5rem 1.5rem;
          border: none;
      }

      .input-group .form-control:focus {
          box-shadow: none;
      }

      /* Table styles */
      .table {
          margin-bottom: 0;
      }

      .table th {
          border-top: none;
          font-weight: 600;
          text-transform: uppercase;
          font-size: 0.85rem;
          color: #555;
      }

      .badge {
          padding: 0.5em 1em;
          font-weight: 500;
      }
      .page-item.active .page-link {
          background-color: #00563B !important;
          border-color: #00563B !important;
      }

      .table-responsive {
          overflow-x: auto;
          -webkit-overflow-scrolling: touch;
          position: relative;
      }

      th:last-child, td:last-child { 
          position: sticky;
          right: 0;
          background: white;
          z-index: 2;
      }

      th:last-child {
          z-index: 3;
      }

      .dashboard-card {
          background: rgba(255, 255, 255, 0.9);
          border-radius: 15px;
          backdrop-filter: blur(10px);
          border: 1px solid rgba(255, 255, 255, 0.18);
          box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
          margin-bottom: 25px;
          overflow: hidden;
          transition: all 0.3s ease;
      }

      .clickable {
          cursor: pointer;
      }

      .clickable:hover {
          transform: translateY(-5px);
          box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      }

      .card-body {
          padding: 1.5rem;
          position: relative;
          z-index: 1;
      }

      .card-icon {
          position: absolute;
          top: 1rem;
          right: 1rem;
          font-size: 2rem;
          opacity: 0.1;
          transform: rotate(-15deg);
      }

      .card-title {
          color: #1a3547;
          font-size: 1.1rem;
          font-weight: 600;
          margin-bottom: 1rem;
          text-transform: uppercase;
          letter-spacing: 0.5px;
      }

      .amount {
          font-size: 1.8rem;
          font-weight: 700;
          color: #198754;
          margin-bottom: 0.5rem;
          font-family: 'Arial', sans-serif;
      }

      .trend {
          font-size: 0.9rem;
          color: #28a745;
          display: flex;
          align-items: center;
          gap: 5px;
      }

      .trend i {
          font-size: 0.8rem;
      }

      .card-footer {
          background: rgba(25, 135, 84, 0.1);
          border-top: none;
          padding: 0.75rem 1.5rem;
          color: #198754;
          font-size: 0.9rem;
          font-weight: 500;
      }

      /* Modal Styling */
      .modal {
          position: fixed;
          top: 0;
          left: 0;
          z-index: 1050;
          display: none;
          width: 100%;
          height: 100%;
          overflow-x: hidden;
          overflow-y: auto;
          outline: 0;
      }

      .modal-content {
          border-radius: 15px;
          border: none;
          box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      }

      .modal-header {
          background: linear-gradient(135deg, #198754 0%, #115c39 100%);
          color: white;
          border-radius: 15px 15px 0 0;
          padding: 1.5rem;
      }

      .modal-body {
          padding: 2rem;
      }

      .modal-footer {
          border-top: 1px solid rgba(0, 0, 0, 0.1);
          padding: 1.5rem;
      }

      .stat-card {
          background: rgba(25, 135, 84, 0.1);
          border-radius: 10px;
          padding: 1rem;
          margin-bottom: 1rem;
      }

      .stat-label {
          color: #666;
          font-size: 0.9rem;
          margin-bottom: 0.5rem;
      }

      .stat-value {
          color: #198754;
          font-size: 1.2rem;
          font-weight: 600;
      }

      .btn-custom {
          background: linear-gradient(135deg, #198754 0%, #115c39 100%);
          border: none;
          border-radius: 8px;
          padding: 0.5rem 1.5rem;
          color: white;
          font-weight: 500;
          transition: all 0.3s ease;
      }

      .btn-custom:hover {
          transform: translateY(-2px);
          color: white;
          box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
      }

      .form-group {
          margin-bottom: 1.5rem;
      }

      .form-label {
          font-weight: 600;
          color: #1a3547;
          margin-bottom: 0.5rem;
      }

      .form-control {
          border-radius: 8px;
          border: 1px solid rgba(0, 0, 0, 0.1);
          padding: 0.75rem;
          transition: all 0.3s ease;
      }

      .form-control:focus {
          box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
          border-color: #198754;
      }

      .deposit-section {
          background: rgba(25, 135, 84, 0.05);
          border-radius: 12px;
          padding: 1.5rem;
          margin-top: 2rem;
      }

      .section-title {
          color: #198754;
          font-weight: 600;
          margin-bottom: 1.5rem;
          font-size: 1.1rem;
      }

      .btn-deposit {
          background: #198754;
          color: white;
          border: none;
          padding: 0.75rem 2rem;
          border-radius: 8px;
          font-weight: 500;
          transition: all 0.3s ease;
      }

      .btn-deposit:hover {
          background: #115c39;
          color: white;
          transform: translateY(-2px);
          box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
      }

      .loan-detail-item {
          margin-bottom: 1rem;
          padding-bottom: 1rem;
          border-bottom: 1px solid #eee;
      }

      .loan-detail-label {
          font-weight: 600;
          color: #555;
      }

      .loan-detail-value {
          color: #333;
      }

      .payment-schedule th {
          background-color: #f8f9fa;
      }

      .status-badge {
          font-size: 0.8rem;
          padding: 0.35rem 0.65rem;
      }

      .btn-view {
          background-color: #6c757d;
          color: white;
      }

      .btn-view:hover {
          background-color: #5a6268;
          color: white;
          
      }
      .modal {
        z-index: 99999 !important;
    }
    .modal-backdrop {
        z-index: 9999 !important;
        
    }

    .swal2-container {
        z-index: 99999 !important;
    }

    .dropdown-menu {
        z-index: 10000 !important;
        }
        .navbar-dropdown {
        z-index: 10001 !important;
        }
    
        .receipt-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.navbar {
              padding-top: 0 !important;
              margin-top: 0 !important;
            }

.receipt-title {
    font-weight: 700;
    color: #00563B;
    margin-bottom: 5px;
}

.receipt-subtitle {
    color: #666;
    margin-bottom: 20px;
}

.receipt-details table {
    width: 100%;
    margin-bottom: 20px;
}

.receipt-details th {
    background-color: #f8f9fa !important;
}

.receipt-notes {
    font-size: 0.9rem;
    color: #666;
}

@media print {
    body * {
        visibility: hidden;
    }
    .receipt-container, .receipt-container * {
        visibility: visible;
    }
    .receipt-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
        box-shadow: none;
    }
    .modal-footer {
        display: none !important;
    }
}

    
  </style>
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo me-5" href="index.php"><img src="../dist/assets/images/logo.png" class="me-2"
            alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/pmpc-logo.png"
            alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../dist/assets/images/user/<?php echo htmlspecialchars($_SESSION['uploadID']); ?>"
                alt="profile" />
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="settings.php">
                <i class="ti-settings text-primary"></i> Settings </a>
              <a class="dropdown-item" href="logout.php">
                <i class="ti-power-off text-primary"></i> Logout </a>
            </div>
          </li>
          <li class="nav-item nav-settings d-none d-lg-flex">
            <a class="nav-link" href="#">
              <i class="icon-ellipsis"></i>
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
          data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
                <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fa-solid fa-house"></i>
                    <span class="menu-title">Home</span>
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="home.php">
                    <i class="fa-solid fa-gauge"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="services.php">
              <i class="fa-brands fa-slack"></i>
              <span class="menu-title">Services</span>
            </a>
          </li>
                    <li class="nav-item">
            <a class="nav-link" href="appointments.php">
              <i class="fa-solid fa-calendar"></i>
              <span class="menu-title">Appointments</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="inbox.php">
              <i class="fa-solid fa-comment"></i>
              <span class="menu-title">Inbox</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="settings.php">
              <i class="fas fa-gear"></i>
              <span class="menu-title">Settings</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <?php
          // Check if the "success" parameter is set in the URL
          if (isset($_GET['success']) && $_GET['success'] == 1) {
              echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire({
                      icon: "success",
                      title: "You Successfully Set Up An Appointment",
                      showConfirmButton: false,
                      timer: 1500
                  });
              });
              </script>';
          }
          if (isset($_GET['success']) && $_GET['success'] == 2) {
              echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire({
                      icon: "success",
                      title: "You Have Successfully Set Up An Appointment",
                      showConfirmButton: false,
                      timer: 1500
                  });
              });
              </script>';
          }

          require_once '../connection/config.php';

          $user_id = $_SESSION['user_id'];
          $full_name = $_SESSION['full_name'];

        // Get user's total savings from the users table
        $savings_query = "SELECT COALESCE(savings, 0) as total_savings FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($savings_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $savings_result = $stmt->get_result();
        $total_savings = $savings_result->fetch_assoc()['total_savings'];

        // Calculate savings with interest (1.5% annual rate)
        $savings_interest_rate = 0.015;
        $total_savings_with_interest = $total_savings * (1 + $savings_interest_rate);


          // Calculate savings percentage this month
          $monthly_savings_query = "SELECT COALESCE(SUM(amount), 0) as monthly_savings 
                                  FROM savings 
                                  WHERE MemberID = ? 
                                  AND Status = 'Approved' 
                                  AND MONTH(TransactionDate) = MONTH(CURRENT_DATE())
                                  AND YEAR(TransactionDate) = YEAR(CURRENT_DATE())";
          $stmt = $conn->prepare($monthly_savings_query);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $monthly_result = $stmt->get_result();
          $monthly_savings = $monthly_result->fetch_assoc()['monthly_savings'];
          $savings_percentage = ($total_savings > 0) ? ($monthly_savings / $total_savings) * 100 : 0;

          // Get total approved share capital and calculate interest
          $share_capital_query = "SELECT COALESCE(SUM(amount), 0) as total_share_capital 
                              FROM share_capital 
                              WHERE MemberID = ? AND Status = 'Approved'";
          $stmt = $conn->prepare($share_capital_query);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $share_result = $stmt->get_result();
          $total_share_capital = $share_result->fetch_assoc()['total_share_capital'];
          
          // Calculate share capital with interest (5% annual rate)
          $share_capital_interest_rate = 0.05;
          $total_share_capital_with_interest = $total_share_capital * (1 + $share_capital_interest_rate);

          // Calculate share capital percentage this month
          $monthly_share_query = "SELECT COALESCE(SUM(amount), 0) as monthly_share 
                              FROM share_capital 
                              WHERE MemberID = ? 
                              AND Status = 'Approved' 
                              AND MONTH(TransactionDate) = MONTH(CURRENT_DATE())
                              AND YEAR(TransactionDate) = YEAR(CURRENT_DATE())";
          $stmt = $conn->prepare($monthly_share_query);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $monthly_result = $stmt->get_result();
          $monthly_share = $monthly_result->fetch_assoc()['monthly_share'];
          $share_percentage = ($total_share_capital > 0) ? ($monthly_share / $total_share_capital) * 100 : 0;

          // Get active services
          $services_query = "SELECT service_name 
                          FROM transactions 
                          WHERE user_id = ? AND payment_status = 'In Progress'";
          $stmt = $conn->prepare($services_query);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $services_result = $stmt->get_result();
          $active_services = [];
          while($row = $services_result->fetch_assoc()) {
              $active_services[] = $row['service_name'];
          }

          // Get loan eligibility and approval status
          $loan_query = "SELECT Eligibility, Status  
                          FROM loanapplication 
                          WHERE userID = ? 
                          ORDER BY LoanID DESC LIMIT 1";

          $stmt = $conn->prepare($loan_query);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $loan_result = $stmt->get_result();
          $loan_data = $loan_result->fetch_assoc();

          $loan_eligibility = $loan_data['Eligibility'] ?? 'N/A';
          $approval_status = $loan_data['Status'] ?? 'N/A';

          // Get last transaction
          $last_transaction_query = "SELECT amount, created_at 
                                  FROM transactions 
                                  WHERE user_id = ? 
                                  ORDER BY created_at DESC 
                                  LIMIT 1";
          $stmt = $conn->prepare($last_transaction_query);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $last_transaction = $stmt->get_result()->fetch_assoc();
          ?>
          <div class="container">
              <div class="row">
                  <!-- Savings Card -->
                  <div class="col-md-3">
                      <div class="dashboard-card clickable" data-bs-toggle="modal" data-bs-target="#savingsModal">
                          <div class="card-body">
                              <i class="fas fa-piggy-bank card-icon"></i>
                              <h5 class="card-title">Savings</h5>
                              <div class="amount">₱<?php echo number_format($total_savings_with_interest, 2); ?></div>
                              <div class="trend">
                                  <i class="fas fa-arrow-<?php echo $savings_percentage > 0 ? 'up' : 'down'; ?>"></i>
                                  <span><?php echo number_format($savings_percentage, 1); ?>% this month</span>
                              </div>
                          </div>
                          <div class="card-footer text-center">
                              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#savingsPassModal">
                                  <i class="fas fa-chart-line me-2"></i>View Details
                              </button>
                          </div>
                      </div>
                  </div>

                  <!-- Share Capital Card -->
                  <div class="col-md-3">
                      <div class="dashboard-card clickable" data-bs-toggle="modal" data-bs-target="#shareCapitalModal">
                          <div class="card-body">
                              <i class="fas fa-coins card-icon"></i>
                              <h5 class="card-title">Share Capital</h5>
                              <div class="amount">₱<?php echo number_format($total_share_capital_with_interest, 2); ?></div>
                              <div class="trend">
                                  <i class="fas fa-arrow-<?php echo $share_percentage > 0 ? 'up' : 'down'; ?>"></i>
                                  <span><?php echo number_format($share_percentage, 1); ?>% this month</span>
                              </div>
                          </div>
                          <div class="card-footer text-center">
                              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#shareCapitalPassModal">
                                  <i class="fas fa-chart-line me-2"></i>View Details
                              </button>
                          </div>
                      </div>
                  </div>

                  <!-- Active Services Card -->
                  <div class="col-md-3">
                      <div class="dashboard-card">
                          <div class="card-body">
                              <i class="fas fa-heartbeat card-icon"></i>
                              <h5 class="card-title">Active Services</h5>
                              <?php foreach($active_services as $service): ?>
                              <div class="d-flex align-items-center mb-2">
                                  <i class="fas fa-check-circle text-success me-2"></i>
                                  <span><?php echo htmlspecialchars($service); ?></span>
                              </div>
                              <?php endforeach; ?>
                              <?php if(empty($active_services)): ?>
                              <div class="d-flex align-items-center mb-2">
                                  <span>No active services</span>
                              </div>
                              <?php endif; ?>
                          </div>
                          <div class="card-footer">
                              <i class="fas fa-clipboard-list me-2"></i>Current Services
                          </div>
                      </div>
                  </div>

                  <!-- Loan Application Card -->
                  <div class="col-md-3">
                      <div class="dashboard-card">
                          <div class="card-body">
                              <i class="fas fa-file-invoice-dollar card-icon"></i>
                              <h5 class="card-title">Loan Application</h5>
                              <div class="d-flex flex-column">
                                  <span class="mb-2">Eligibility: <?php echo htmlspecialchars($loan_eligibility); ?></span>
                                  <span>Status: <?php echo htmlspecialchars($approval_status); ?></span>
                              </div>
                          </div>
                          <div class="card-footer">
                              <i class="fas fa-info-circle me-2"></i>Loan Status
                          </div>
                      </div>
                  </div>
              </div>

<!-- Savings Modal -->
<div class="modal fade" id="savingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-piggy-bank me-2"></i>
                    Savings Account Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="savingsForm" action="add_savings.php" method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-label">Current Balance</div>
                                <div class="stat-value">₱<?php echo number_format($total_savings_with_interest, 2); ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-label">Interest Rate</div>
                                <div class="stat-value">₱<?php echo number_format($total_savings_with_interest - $total_savings, 2); ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="stat-label">Last Transaction</div>
                                <div class="stat-value">₱<?php echo number_format($last_transaction['amount'], 2); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="deposit-section">
                        <h6 class="section-title">Set Appointment</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Transaction Type</label>
                                    <select name="transaction_type" class="form-select" required>
                                        <option value="">Select type</option>
                                        <option value="deposit">Deposit</option>
                                        <option value="withdrawal">Withdrawal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" name="amount" class="form-control" placeholder="Enter amount" min="0.01" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Appointment Date</label>
                                    <input type="date" name="appointment_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Notes (Optional)</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Add any additional notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" form="savingsForm" class="btn btn-deposit">
                            <i class="fas fa-calendar-check me-2"></i>Set Appointment
                        </button>
                        <a href="download_statement.php?download_statement=1" class="btn btn-custom">
                            <i class="fas fa-download me-2"></i>Download Statement
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


              <!-- Share Capital Modal -->
              <div class="modal fade" id="shareCapitalModal" tabindex="-1">
                  <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title">
                                  <i class="fas fa-coins me-2"></i>
                                  Share Capital Details
                              </h5>
                              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                              <form id="shareCapitalForm" action="add_sharecapital.php" method="post">
                                  <div class="row">
                                      <div class="col-md-4">
                                          <div class="stat-card">
                                              <div class="stat-label">Total Share Capital</div>
                                              <div class="stat-value">₱<?php echo number_format($total_share_capital_with_interest, 2); ?></div>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="stat-card">
                                              <div class="stat-label">Number of Shares</div>
                                              <div class="stat-value"><?php echo floor($total_share_capital / 100); ?></div>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="stat-card">
                                              <div class="stat-label">Dividend Rate</div>
                                              <div class="stat-value">₱<?php echo number_format($total_share_capital_with_interest - $total_share_capital, 2); ?></div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="deposit-section">
                                      <h6 class="section-title">Add Share Capital</h6>
                                      <div class="row">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Amount</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" name="amount" class="form-control" placeholder="Enter amount" min="0.01" step="0.01" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Appointment Date</label>
                                            <input type="date" name="appointment_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                          <div class="col-md-12">
                                              <div class="form-group">
                                                  <label class="form-label">Notes (Optional)</label>
                                                  <textarea name="notes" class="form-control" rows="2" placeholder="Add any additional notes"></textarea>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="submit" form="shareCapitalForm" class="btn btn-deposit">
                                          <i class="fas fa-plus-circle me-2"></i>Set an Appointment
                                      </button>
                                      <a href="download_report.php?download_report=1" class="btn btn-custom">
                                          <i class="fas fa-file-pdf me-2"></i>Download Report
                                      </a>
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
              
              <?php
              date_default_timezone_set('Asia/Manila');
              
              $loggedInUserId = $_SESSION['user_id'];
              $fullName = $_SESSION['full_name'];
              
              $savingsSql = "
              SELECT 
                  s.TransactionDate,
                  s.Amount,
                  s.Type,
                  t.control_number,
                  t.signature
              FROM savings s
              LEFT JOIN transactions t 
                  ON t.SavingsID = s.SavingsID
                  AND t.payment_status = 'Completed'
              WHERE s.MemberID = ?
                AND s.Status = 'Approved'
              ORDER BY s.TransactionDate ASC
          ";
          
          
          $savingsStmt = $conn->prepare($savingsSql);
          $savingsStmt->bind_param("s", $loggedInUserId);
          $savingsStmt->execute();
          $savingsResult = $savingsStmt->get_result();
          
              
              // Modified Share Capital query to get distinct transactions
              $shareCapitalSql = "
                  SELECT DISTINCT
                      t.created_at as TransactionDate,
                      t.amount as Amount,
                      t.signature,
                      t.control_number
                  FROM transactions t
                  WHERE t.user_id = ? 
                  AND t.service_name = 'Share Capital Deposit'
                  AND t.payment_status = 'Completed'
                  ORDER BY t.created_at ASC";
              
              $shareCapitalStmt = $conn->prepare($shareCapitalSql);
              $shareCapitalStmt->bind_param("s", $loggedInUserId);
              $shareCapitalStmt->execute();
              $shareCapitalResult = $shareCapitalStmt->get_result();
              ?>

<!-- Savings Passbook Modal -->
<div class="modal fade" id="savingsPassModal" tabindex="-1" aria-labelledby="savingsPassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="savingsPassModalLabel">Savings Passbook</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p><strong>Member No.:</strong> <?php echo htmlspecialchars($loggedInUserId); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($fullName); ?></p>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>OR/CV No.</th>
                                <th>Received (DR)</th>
                                <th>Interest</th>
                                <th>Withdrawn (CR)</th>
                                <th>Balance</th>
                                <th>Cashier's Initial</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalBalance = 0;
                            while ($row = $savingsResult->fetch_assoc()) {
                                $type = $row['Type'];
                                $amount = $row['Amount'];
                                $received = 0;
                                $withdrawn = 0;
                                $interest = 0;

                                if ($type === 'Deposit') {
                                    $interest = $amount * 0.0135;
                                    $received = $amount;
                                    $totalBalance += $received + $interest;
                                } elseif ($type === 'Withdrawal') {
                                    $withdrawn = $amount;
                                    $totalBalance -= $withdrawn;
                                }

                                $date = date('M. d, Y', strtotime($row['TransactionDate']));
                                $controlNumber = htmlspecialchars($row['control_number']);
                                $cashierInitial = htmlspecialchars($row['signature']);
                            ?>
                                <tr>
                                    <td><?php echo $date; ?></td>
                                    <td><?php echo $controlNumber; ?></td>
                                    <td>₱<?php echo number_format($received, 2); ?></td>
                                    <td>₱<?php echo number_format($interest, 2); ?></td>
                                    <td>₱<?php echo number_format($withdrawn, 2); ?></td>
                                    <td>₱<?php echo number_format($totalBalance, 2); ?></td>
                                    <td><?php echo $cashierInitial; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


              <!-- Share Capital Passbook Modal -->
              <div class="modal fade" id="shareCapitalPassModal" tabindex="-1" aria-labelledby="shareCapitalPassModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="shareCapitalPassModalLabel">Share Capital Passbook</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <div class="mb-3">
                                  <p><strong>Member No.:</strong> <?php echo htmlspecialchars($loggedInUserId); ?></p>
                                  <p><strong>Name:</strong> <?php echo htmlspecialchars($fullName); ?></p>
                              </div>
                              <div class="table-responsive">
                                  <table class="table table-bordered text-center">
                                      <thead>
                                          <tr>
                                              <th>Date</th>
                                              <th>OR/CV No.</th>
                                              <th>Received (DR)</th>
                                              <th>Dividend</th>
                                              <th>Withdrawn (CR)</th>
                                              <th>Balance</th>
                                              <th>Cashier's Initial</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php 
                                          $totalBalance = 0;
                                          while ($row = $shareCapitalResult->fetch_assoc()) { 
                                              $dividend = $row['Amount'] * 0.05;
                                              $currentAmount = $row['Amount'] + $dividend;
                                              $totalBalance += $currentAmount;
                                          ?>
                                              <tr>
                                                  <td><?php echo date('M. d, Y', strtotime($row['TransactionDate'])); ?></td>
                                                  <td><?php echo htmlspecialchars($row['control_number'] ?? 'N/A'); ?></td>
                                                  <td>₱<?php echo number_format($row['Amount'], 2); ?></td>
                                                  <td>₱<?php echo number_format($dividend, 2); ?></td>
                                                  <td>₱0.00</td>
                                                  <td>₱<?php echo number_format($totalBalance, 2); ?></td>
                                                  <td><?php echo htmlspecialchars($row['signature'] ?? 'N/A'); ?></td>
                                              </tr>
                                          <?php } ?>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                      </div>
                  </div>
              </div>
              
              <?php
              // Get user_id from session
              $user_id = $_SESSION['user_id'];

              // Fetch the search query and page number
              $search = isset($_GET['search']) ? $_GET['search'] : '';
              $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
              $limit = 10;
              $offset = ($page - 1) * $limit;

              $sql = "SELECT 
              t.transaction_id, 
              t.service_name, 
              t.user_id, 
              t.payment_status, 
              t.created_at, 
              t.amount, 
              t.control_number, 
              t.updated_at, 
              t.signature 
          FROM transactions t 
          WHERE t.user_id = ?
          AND t.payment_status = 'Completed' 
          AND (
              t.transaction_id LIKE ? 
              OR t.service_name LIKE ? 
              OR t.payment_status LIKE ? 
              OR t.control_number LIKE ?
          ) 
          ORDER BY t.created_at DESC 
          LIMIT ? OFFSET ?";
  
            $stmt = $conn->prepare($sql);
            $search_param = "%" . $search . "%";
            
            // Add an extra 's' for control_number search
            $stmt->bind_param("sssssii", 
                $user_id, 
                $search_param, 
                $search_param, 
                $search_param, 
                $search_param,  // added for control_number
                $limit, 
                $offset
            );
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            

              $loan_sql = "SELECT 
                  ch.LoanID,
                  ch.AmountRequested,
                  ch.loanable_amount,
                  ch.LoanType,
                  ch.ApprovalStatus,
                  ch.PayableAmount,
                  ch.PayableDate,
                  ch.InterestRate,
                  la.ModeOfPayment,
                  COALESCE(
                      (SELECT SUM(amount) 
                      FROM transactions t
                      WHERE t.user_id = ch.MemberID 
                      AND t.service_name IN ('Regular Loan Payment', 'Collateral Loan Payment')
                      ), 0
                  ) as total_paid_amount
              FROM credit_history ch
              LEFT JOIN loanapplication la ON ch.LoanID = la.LoanID
              WHERE ch.MemberID = ? 
              AND ch.ApprovalStatus IN ('In Progress', 'Approved')
              GROUP BY ch.LoanID";
              
              $loan_stmt = $conn->prepare($loan_sql);
              $loan_stmt->bind_param("i", $user_id);
              $loan_stmt->execute();
              $loan_result = $loan_stmt->get_result();

              // Fetch the loan details
              $loans_sql = "SELECT 
                  LoanID, 
                  AmountRequested, 
                  LoanType, 
                  LoanTerm, 
                  ModeOfPayment, 
                  userID, 
                  DateOfLoan, 
                  application_date,
                  Status 
                  FROM loanapplication
                  WHERE userID = ? AND reference3_contact_no IS NOT NULL";

              $loans_stmt = $conn->prepare($loans_sql);
              $loans_stmt->bind_param("i", $user_id);
              $loans_stmt->execute();
              $loans_result = $loans_stmt->get_result();

              // Count query for pagination
              $count_sql = "SELECT COUNT(*) as total 
                              FROM transactions
                              WHERE user_id = ?
                              AND payment_status = 'Completed'
                              AND (transaction_id LIKE ? 
                              OR service_name LIKE ? 
                              OR payment_status LIKE ?)";

              $count_stmt = $conn->prepare($count_sql);
              $count_stmt->bind_param("isss", 
                  $user_id,
                  $search_param, 
                  $search_param, 
                  $search_param
              );
              $count_stmt->execute();
              $count_result = $count_stmt->get_result();
              $total_rows = $count_result->fetch_assoc()['total'];
              $total_pages = ceil($total_rows / $limit);
              ?>

              <div class="container mt-4">
                  <!-- Search Bar -->
                  <div class="card mb-4">
                      <div class="card-body">
                          <form id="searchForm" method="GET" class="position-relative">
                              <div class="input-group">
                                  <input type="text" 
                                      id="searchInput" 
                                      name="search" 
                                      class="form-control form-control-lg" 
                                      placeholder="Search Transaction (Service name, Receipt No., Status...)"
                                      value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                  <button type="button" 
                                          id="clearButton" 
                                          class="btn btn-outline-secondary">
                                      <i class="fas fa-times"></i>
                                  </button>
                                  <button type="submit" class="btn btn-primary">
                                      <i class="fas fa-search"></i>
                                  </button>
                              </div>
                          </form>
                      </div>
                  </div>
                  <div class="card mb-4">
                      <div class="card-header">
                          Loan Applications
                      </div>
                      <div class="card-body">
                          <?php if ($loans_result->num_rows > 0): ?>
                              <div class="table-responsive">
                                  <table class="table table-hover">
                                      <thead class="table-light">
                                          <tr>
                                              <th>Loan ID</th>
                                              <th>Amount Requested</th>
                                              <th>Loan Type</th>
                                              <th>Loan Term</th>
                                              <th>Mode of Payment</th>
                                              <th>Date of Loan</th>
                                              <th>Date of Application</th>  
                                              <th>Status</th>
                                              <th>Action</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php while($loans = $loans_result->fetch_assoc()): ?>
                                              <tr>
                                                  <td><?php echo htmlspecialchars($loans['LoanID']); ?></td>
                                                  <td>₱<?php echo number_format($loans['AmountRequested'], 2); ?></td>
                                                  <td><?php echo htmlspecialchars($loans['LoanType']); ?></td>
                                                  <td><?php echo htmlspecialchars($loans['LoanTerm']); ?></td>
                                                  <td><?php echo htmlspecialchars($loans['ModeOfPayment']); ?></td>
                                                  <td><?php echo date('M d, Y', strtotime($loans['DateOfLoan'])); ?></td>
                                                  <td><?php echo date('M d, Y', strtotime($loans['application_date'])); ?></td>
                                                  <td><?php echo htmlspecialchars($loans['Status']); ?></td>
                                                  <td>
                                                  <?php if ($loans['Status'] == 'In Progress'): ?>
                                                      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelLoanModal" data-loan-id="<?php echo $loans['LoanID']; ?>">
                                                          Cancel Loan
                                                      </button>
                                                  <?php elseif ($loans['Status'] == 'Cancelled'): ?>
                                                      <span class="badge bg-danger">Cancelled</span>
                                                  <?php else: ?>
                                                      <span class="text-muted">N/A</span>
                                                  <?php endif; ?>
                                                  </td>
                                              </tr>
                                          <?php endwhile; ?>
                                      </tbody>
                                  </table>
                              </div>
                          <?php else: ?>
                              <p>No in progress loan found.</p>
                          <?php endif; ?>
                      </div>
                  </div>

                    <!-- Cancel Loan Modal -->
                    <div class="modal fade" id="cancelLoanModal" tabindex="-1" aria-labelledby="cancelLoanModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="cancelLoanModalLabel">Cancel Loan Application</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Important:</strong> You can only cancel your loan application within 3 days of submission. After this period, cancellation will no longer be possible.
                                    </div>
                                    <p>Are you sure you want to cancel this loan application? This action cannot be undone.</p>
                                    <div id="loanDetailsContainer"></div>
                                </div>
                                <div class="modal-footer">
                                    <form id="cancelLoanForm" action="cancel_loan.php" method="POST">
                                        <input type="hidden" id="loanID" name="loanID" value="">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger" id="cancelLoanBtn">
                                            <i class="fas fa-times-circle me-2"></i>Cancel Loan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Loans -->
                    <div class="card mb-4">
                        <div class="card-header">
                            Active Loans
                        </div>
                        <div class="card-body">
                            <?php if ($loan_result->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Loan ID</th>
                                                <th>Amount Requested</th>
                                                <th>Loan Term</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Mode of Payment</th>
                                                <th>Payable Amount</th>
                                                <th>Payable Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            // Reset the result set pointer
                                            mysqli_data_seek($loan_result, 0);
                                            while($loan = $loan_result->fetch_assoc()): 
                                                // Get loan term from loanapplication table
                                                $loanTermSql = "SELECT LoanTerm FROM loanapplication WHERE LoanID = ?";
                                                $loanTermStmt = $conn->prepare($loanTermSql);
                                                $loanTermStmt->bind_param("i", $loan['LoanID']);
                                                $loanTermStmt->execute();
                                                $loanTermResult = $loanTermStmt->get_result();
                                                $loanTerm = $loanTermResult->fetch_assoc()['LoanTerm'];
                                                
                                                // Get the current balance from credit_history
                                                $balanceSql = "SELECT Balance FROM credit_history WHERE LoanID = ?";
                                                $balanceStmt = $conn->prepare($balanceSql);
                                                $balanceStmt->bind_param("i", $loan['LoanID']);
                                                $balanceStmt->execute();
                                                $balanceResult = $balanceStmt->get_result();
                                                $balance = $balanceResult->fetch_assoc()['Balance'];
                                                
                                                // Calculate total payment (AmountRequested + interest)
                                                $total_payment = $loan['AmountRequested'] + ($loan['AmountRequested'] * ($loan['InterestRate'] / 100));
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($loan['LoanID']); ?></td>
                                                    <td>₱<?php echo number_format($loan['AmountRequested'], 2); ?></td>
                                                    <td><?php echo htmlspecialchars($loanTerm); ?> months</td>
                                                    <td><?php echo htmlspecialchars($loan['LoanType']); ?></td>
                                                    <td><?php echo htmlspecialchars($loan['ApprovalStatus']); ?></td>
                                                    <td><?php echo htmlspecialchars($loan['ModeOfPayment']); ?></td>
                                                    <td>₱<?php echo number_format($loan['PayableAmount'], 2); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($loan['PayableDate'])); ?></td>
                                                    <td>
                                                    <?php if ($balance > 0): ?>
                                                        <button type="button" class="btn btn-primary btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#payModal<?php echo $loan['LoanID']; ?>">
                                                            Pay
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Fully Paid</span>
                                                    <?php endif; ?>
                                                    
                                                    <!-- View Payment History Button -->
                                                    <button type="button" class="btn btn-info btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#historyModal<?php echo $loan['LoanID']; ?>">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                    
                                                    <!-- View Loan Details Button -->
                                                    <button type="button" class="btn btn-view btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewLoanModal<?php echo $loan['LoanID']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p>No active loans found.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                  <!-- Payment History Modals -->
                  <?php 
                  // Reset the result set pointer
                  mysqli_data_seek($loan_result, 0);
                  while($loan = $loan_result->fetch_assoc()): 
                      // Get payment history for this specific loan
                      $paymentHistorySql = "SELECT 
                            lp.payment_id, 
                            lp.payment_date, 
                            lp.amount_paid, 
                            lp.receipt_number,
                            lp.payment_status, 
                            lp.days_late,
                            CONCAT(u.first_name, ' ', u.last_name) as recorded_by,
                            ch.TotalPayment
                        FROM loan_payments lp
                        LEFT JOIN users u ON lp.recorded_by = u.user_id
                        LEFT JOIN credit_history ch ON lp.LoanID = ch.LoanID
                        WHERE lp.LoanID = ?
                        ORDER BY lp.payment_date DESC";

                      
                      $paymentStmt = $conn->prepare($paymentHistorySql);
                      $paymentStmt->bind_param("i", $loan['LoanID']);
                      $paymentStmt->execute();
                      $paymentHistory = $paymentStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                  ?>
                      <div class="modal fade" id="historyModal<?php echo $loan['LoanID']; ?>" tabindex="-1" aria-labelledby="historyModalLabel<?php echo $loan['LoanID']; ?>" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                  <div class="modal-header bg-primary text-white">
                                      <h5 class="modal-title" id="historyModalLabel<?php echo $loan['LoanID']; ?>">
                                          Payment History for Loan #<?php echo $loan['LoanID']; ?>
                                      </h5>
                                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                  <?php if (!empty($paymentHistory)): ?>
                                      <div class="table-responsive">
                                          <table class="table table-striped">
                                              <thead>
                                                  <tr>
                                                      <th>Date</th>
                                                      <th>Amount Paid</th>
                                                      <th>Receipt No.</th>
                                                      <th>Status</th>
                                                      <th>Days Late</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                  <?php foreach ($paymentHistory as $payment): ?>
                                                      <tr>
                                                          <td><?= date('M d, Y h:i A', strtotime($payment['payment_date'])) ?></td>
                                                          <td>₱<?= number_format($payment['amount_paid'], 2) ?></td>
                                                          <td><?= htmlspecialchars($payment['receipt_number']) ?></td>
                                                          <td>
                                                              <span class="badge bg-<?= 
                                                                  ($payment['payment_status'] === 'Completed') ? 'success' : 
                                                                  ($payment['payment_status'] === 'Late' ? 'danger' : 'warning')
                                                              ?>">
                                                                  <?= $payment['payment_status'] ?>
                                                              </span>
                                                          </td>
                                                          <td>
                                                              <?php 
                                                              if (isset($payment['days_late']) && $payment['days_late'] > 0) {
                                                                  echo htmlspecialchars($payment['days_late']) . ' days';
                                                              } else {
                                                                  echo 'On time';
                                                              }
                                                              ?>
                                                          </td>
                                                      </tr>
                                                  <?php endforeach; ?>
                                              </tbody>
                                          </table>
                                      </div>
                                  <?php else: ?>
                                      <p class="text-muted">No payment history found for this loan.</p>
                                  <?php endif; ?>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              </div>
                          </div>
                      </div>
                  </div>

                        <!-- Loan Details Modal -->
                        <div class="modal fade" id="viewLoanModal<?php echo $loan['LoanID']; ?>" tabindex="-1" aria-labelledby="viewLoanModalLabel<?php echo $loan['LoanID']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="viewLoanModalLabel<?php echo $loan['LoanID']; ?>">
                                            Loan Details - #<?php echo $loan['LoanID']; ?>
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        // Get detailed loan information from credit_history
                                        $loanDetailSql = "SELECT 
                                                            ch.AmountRequested,
                                                            ch.InterestRate,
                                                            ch.LoanTerm,
                                                            ch.TotalPayment,
                                                            ch.Balance,
                                                            ch.PayableAmount,
                                                            ch.PayableDate,
                                                            la.LoanType,
                                                            la.ModeOfPayment,
                                                            la.Status
                                                        FROM credit_history ch
                                                        JOIN loanapplication la ON ch.LoanID = la.LoanID
                                                        WHERE ch.LoanID = ?";
                                        $loanDetailStmt = $conn->prepare($loanDetailSql);
                                        $loanDetailStmt->bind_param("i", $loan['LoanID']);
                                        $loanDetailStmt->execute();
                                        $loanDetails = $loanDetailStmt->get_result()->fetch_assoc();

                                        // Correct simple interest calculation
                                        $principal = $loanDetails['AmountRequested'];
                                        $rate = $loanDetails['InterestRate']; // Annual rate
                                        $time = $loanDetails['LoanTerm'] / 12; // Convert months to years

                                        $totalInterest = $principal * $rate * $time;
                                        $totalLoanAmount = $principal + $totalInterest;
                                        $annualInterest = $principal * $rate; 
                                        ?>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="loan-detail-item">
                                                    <div class="loan-detail-label">Loan Type</div>
                                                    <div class="loan-detail-value"><?php echo htmlspecialchars($loanDetails['LoanType']); ?></div>
                                                </div>

                                                <div class="loan-detail-item">
                                                    <div class="loan-detail-label">Amount Requested</div>
                                                    <div class="loan-detail-value">₱<?php echo number_format($loanDetails['AmountRequested'], 2); ?></div>
                                                </div>

                                                <div class="loan-detail-item">
                                                    <div class="loan-detail-label">Interest Rate</div>
                                                    <div class="loan-detail-value"><?php echo htmlspecialchars($loanDetails['InterestRate']); ?>%</div>
                                                </div>

                                                <div class="loan-detail-item">
                                                    <div class="loan-detail-label">Loan Term</div>
                                                    <div class="loan-detail-value"><?php echo htmlspecialchars($loanDetails['LoanTerm']); ?> months</div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="loan-detail-item">
                                                    <div class="loan-detail-label">Mode of Payment</div>
                                                    <div class="loan-detail-value"><?php echo htmlspecialchars($loanDetails['ModeOfPayment']); ?></div>
                                                </div>

                                                <div class="loan-detail-item">
                                                    <div class="loan-detail-label">Status</div>
                                                    <div class="loan-detail-value">
                                                        <span class="badge bg-<?php echo $loanDetails['Status'] === 'Approved' ? 'success' : 'warning'; ?>">
                                                            <?php echo htmlspecialchars($loanDetails['Status']); ?>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="loan-detail-item">
                                                    <div class="loan-detail-label">Amount Payable</div>
                                                    <div class="loan-detail-value">₱<?php echo number_format($loanDetails['PayableAmount'], 2); ?></div>
                                                </div>

                                                <div class="loan-detail-item">
                                                    <div class="loan-detail-label">Next Payment Date</div>
                                                    <div class="loan-detail-value"><?php echo date('M d, Y', strtotime($loanDetails['PayableDate'])); ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5 class="mb-3">Financial Summary</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Total Loan Amount (with interest)</th>
                                                                <th>Amount Paid</th>
                                                                <th>Remaining Balance</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>₱<?php echo number_format($totalLoanAmount, 2); ?></td>
                                                                <td>₱<?php echo number_format($loanDetails['TotalPayment'], 2); ?></td>
                                                                <td>₱<?php echo number_format($loanDetails['Balance'], 2); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Interest Calculation Breakdown -->
                                                <div class="alert alert-info mt-3">
                                                    <h6>Interest Calculation:</h6>
                                                    <p>
                                                        <strong>Principal:</strong> ₱<?php echo number_format($principal, 2); ?><br>
                                                        <strong>Annual Interest (<?php echo $loanDetails['InterestRate']; ?>%):</strong> ₱<?php echo number_format($annualInterest, 2); ?><br>
                                                        <strong>Loan Term:</strong> <?php echo $loanDetails['LoanTerm']; ?> months (<?php echo number_format($time, 1); ?> years)<br>
                                                        <strong>Total Interest:</strong> ₱<?php echo number_format($totalInterest, 2); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- Payment Modal -->
                    <div class="modal fade" id="payModal<?php echo $loan['LoanID']; ?>" tabindex="-1" aria-labelledby="payModalLabel<?php echo $loan['LoanID']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="payModalLabel<?php echo $loan['LoanID']; ?>">Make Payment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="process_payment.php" method="POST" id="paymentForm<?php echo $loan['LoanID']; ?>">
                                        <input type="hidden" name="loan_id" value="<?php echo $loan['LoanID']; ?>">
                                        <input type="hidden" name="loan_type" value="<?php echo htmlspecialchars($loan['LoanType']); ?>">
                                        <input type="hidden" name="ModeOfPayment" value="<?php echo htmlspecialchars($loan['ModeOfPayment']); ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Mode of Payment</label>
                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($loan['ModeOfPayment']); ?>" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Payable Amount</label>
                                            <input type="text" class="form-control" value="₱<?php echo number_format($loanDetails['PayableAmount'], 2); ?>" readonly>
                                            <input type="hidden" name="payable_amount" value="<?php echo $loanDetails['PayableAmount']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Payable Date</label>
                                            <input type="text" class="form-control" value="<?php echo date('M d, Y', strtotime($loanDetails['PayableDate'])); ?>" readonly>
                                            <input type="hidden" name="payable_date" value="<?php echo $loanDetails['PayableDate']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Total Loan Amount (with interest)</label>
                                            <input type="text" class="form-control" value="₱<?php echo number_format($totalLoanAmount, 2); ?>" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Total Paid Amount</label>
                                            <input type="text" class="form-control" value="₱<?php echo number_format($loanDetails['TotalPayment'], 2); ?>" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Remaining Balance</label>
                                            <input type="text" class="form-control" value="₱<?php echo number_format($loanDetails['Balance'], 2); ?>" readonly>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Confirm Payment</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                  <?php endwhile; ?>

                  <!-- Transactions -->
                  <div class="card">
                      <div class="card-body">
                          <div class="table-responsive">
 <!-- In your transactions table -->
<table class="table table-hover">
    <thead class="table-light">
        <tr>
            <th>Transaction ID</th>
            <th>Service Name</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                <td><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                <td>
                    <?php if($row['payment_status'] === 'Completed'): ?>
                        <button class="btn btn-sm btn-primary view-receipt-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#receiptModal"
                                data-transaction-id="<?php echo $row['transaction_id']; ?>"
                                data-service-name="<?php echo htmlspecialchars($row['service_name']); ?>"
                                data-amount="<?php echo isset($row['amount']) ? $row['amount'] : '0.00'; ?>"
                                data-control-number="<?php echo isset($row['control_number']) ? $row['control_number'] : 'N/A'; ?>"
                                data-updated-at="<?php echo isset($row['updated_at']) ? date('M d, Y h:i A', strtotime($row['updated_at'])) : date('M d, Y h:i A', strtotime($row['created_at'])); ?>"
                                data-signature="<?php echo isset($row['signature']) ? htmlspecialchars($row['signature']) : ''; ?>">
                            <i class="fas fa-receipt"></i> View Receipt
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
                          </div>
                          <br>
                          <!-- Pagination -->
                          <nav aria-label="Page navigation">
                              <ul class="pagination justify-content-center">
                                  <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                      <a class="page-link" href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page - 1; ?>" aria-label="Previous">
                                          <span aria-hidden="true">&laquo; </span>
                                      </a>
                                  </li>
                                  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                      <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                          <a class="page-link" href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                      </li>
                                  <?php endfor; ?>
                                  <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                      <a class="page-link" href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page + 1; ?>" aria-label="Next">
                                          <span aria-hidden="true"> &raquo;</span>
                                      </a>
                                  </li>
                              </ul>
                          </nav>
                      </div>

 <!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="receiptModalLabel">Transaction Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="receipt-container">
                    <div class="receipt-header text-center mb-4">
                        <img src="../dist/assets/images/logo.png" alt="Company Logo" class="receipt-logo mb-2" style="height: 80px;">
                        <h3 class="receipt-title">OFFICIAL RECEIPT</h3>
                        <p class="receipt-subtitle">Paschal Credit Cooperative</p>
                    </div>
                    
                    <div class="receipt-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Receipt No:</strong> <span id="receiptControlNumber">N/A</span></p>
                                <p><strong>Transaction ID:</strong> <span id="receiptTransactionId">N/A</span></p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p><strong>Date:</strong> <span id="receiptDate">N/A</span></p>
                                <p><strong>Member ID:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
                            </div>
                        </div>
                        
                        <div class="receipt-details mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Description</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="receiptServiceName">N/A</td>
                                        <td id="receiptAmount">₱0.00</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td><strong>TOTAL</strong></td>
                                        <td id="receiptTotal">₱0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="receipt-footer mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Member Name:</strong></p>
                                    <p><?php echo htmlspecialchars($full_name); ?></p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <p class="mb-1"><strong>Processed By:</strong></p>
                                    <p>Paschal Credit Cooperative</p>
                                    <div id="receiptSignatureContainer" class="signature mt-2" style="display:none;">
                                        <img id="receiptSignature" src="" alt="Signature" style="height: 50px;">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="receipt-notes mt-4 pt-3 border-top">
                                <p class="text-muted small mb-1">This is an official receipt from Paschal Credit Cooperative.</p>
                                <p class="text-muted small">Please keep this receipt for your records.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
                  </div>
              </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024-2050. <a
                href="" target="_blank">Paschal</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"></span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- jQuery first, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Add SweetAlert script -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

  <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../dist/assets/vendors/chart.js/chart.umd.js"></script>
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../dist/assets/js/off-canvas.js"></script>
  <script src="../dist/assets/js/template.js"></script>
  <script src="../dist/assets/js/settings.js"></script>
  <script src="../dist/assets/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="../dist/assets/js/jquery.cookie.js" type="text/javascript"></script>
  <script src="../dist/assets/js/dashboard.js"></script>

  <script>
$(document).ready(function() {
    // Store loan application dates in a data attribute when page loads
    <?php
    // Fetch all loan application dates for the user
    $loanDatesQuery = "SELECT LoanID, application_date FROM loanapplication WHERE userID = ?";
    $stmt = $conn->prepare($loanDatesQuery);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $loanDatesResult = $stmt->get_result();
    $loanDates = [];
    while ($row = $loanDatesResult->fetch_assoc()) {
        $loanDates[$row['LoanID']] = $row['application_date'];
    }
    ?>
    
    // Convert PHP array to JS object
    var loanDates = <?php echo json_encode($loanDates); ?>;
    
    // Set loan ID for cancel modal and check cancellation eligibility
    $('[data-bs-target="#cancelLoanModal"]').on('click', function() {
        var loanID = $(this).data('loan-id');
        $('#loanID').val(loanID);
        
        // Get application date from our stored data
        var applicationDate = new Date(loanDates[loanID]);
        var currentDate = new Date();
        var timeDiff = currentDate - applicationDate;
        var daysPassed = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
        
        // Format date for display
        var formattedDate = applicationDate.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
        
        // Update modal content
        var detailsHtml = '<div class="mb-3">' +
            '<strong>Application Date:</strong> ' + formattedDate + '<br>' +
            '<strong>Days Since Application:</strong> ' + daysPassed + ' days' +
            '</div>';
        
        $('#loanDetailsContainer').html(detailsHtml);
        
        // Disable button if more than 3 days have passed
        if (daysPassed > 3) {
            $('#cancelLoanBtn').prop('disabled', true)
                .attr('title', 'Cancellation period (3 days) has expired');
        } else {
            $('#cancelLoanBtn').prop('disabled', false)
                .removeAttr('title');
        }
    });
    
    // Reset modal when closed
    $('#cancelLoanModal').on('hidden.bs.modal', function() {
        $('#loanDetailsContainer').empty();
        $('#cancelLoanBtn').prop('disabled', false).removeAttr('title');
    });
    
    // Handle cancel loan form submission
    $('#cancelLoanForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        Swal.fire({
            title: 'Confirm Cancellation',
            text: "Are you sure you want to cancel this loan application? This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            Swal.fire(
                                'Cancelled!',
                                'Your loan application has been cancelled.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'An error occurred while processing your request.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>

<script>
$(document).ready(function() {
    // Handle receipt view button click
    $(document).on('click', '.view-receipt-btn', function() {
        // Get all data attributes
        const transactionData = {
            id: $(this).data('transaction-id'),
            service: $(this).data('service-name'),
            amount: parseFloat($(this).data('amount')) || 0,
            controlNumber: $(this).data('control-number'),
            date: $(this).data('updated-at'),
            signature: $(this).data('signature')
        };

        // Populate modal with data
        $('#receiptTransactionId').text(transactionData.id);
        $('#receiptServiceName').text(transactionData.service);
        $('#receiptAmount').text('₱' + transactionData.amount.toFixed(2));
        $('#receiptTotal').text('₱' + transactionData.amount.toFixed(2));
        $('#receiptControlNumber').text(transactionData.controlNumber);
        $('#receiptDate').text(transactionData.date);
        
        // Handle signature if exists
        if(transactionData.signature && transactionData.signature !== '') {
            $('#receiptSignature').attr('src', '../dist/assets/images/signatures/' + transactionData.signature);
            $('#receiptSignatureContainer').show();
        } else {
            $('#receiptSignatureContainer').hide();
        }
    });
    
    // Print receipt function
    window.printReceipt = function() {
        const printContent = $('.receipt-container').html();
        const originalContent = $('body').html();
        
        $('body').html(printContent);
        window.print();
        $('body').html(originalContent);
        
        // Re-open the modal after printing
        $('#receiptModal').modal('show');
    };
});
</script>

  <script>
  $(document).ready(function() {
      // Initialize modals
      $('.modal').modal({show: false});
      
      // Handle payment form submission
      $('[id^="paymentForm"]').on('submit', function(e) {
          e.preventDefault();
          var form = $(this);
          var submitBtn = form.find('button[type="submit"]');
          var originalText = submitBtn.html();
          
          submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
          submitBtn.prop('disabled', true);
          
          $.ajax({
    url: form.attr('action'),
    type: 'POST',
    data: form.serialize(),
    dataType: 'json',
    success: function(data) {
        if (data.success) {
            // Close the payment modal first
            form.closest('.modal').modal('hide');
            
            // Remove any remaining modal backdrop
            $('.modal-backdrop').remove();
            
            Swal.fire({
                icon: 'success',
                title: 'Payment Scheduled',
                text: data.message,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                timer: 5000, // Auto-close after 5 seconds (optional)
                timerProgressBar: true // Show progress bar (optional)
            }).then(() => {
                // Refresh after user clicks OK or timer completes
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'OK'
            });
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        }
    },
    error: function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while processing your payment',
            confirmButtonText: 'OK'
        });
        submitBtn.html(originalText);
        submitBtn.prop('disabled', false);
    }
});
      });
      
      // Clear search functionality
      $('#clearButton').on('click', function() {
          $('#searchInput').val('');
          $('#searchForm').submit();
      });

      // Set loan ID for cancel modal
      $('[data-bs-target="#cancelLoanModal"]').on('click', function() {
          var loanID = $(this).data('loan-id');
          $('#loanID').val(loanID);
      });
  });
  </script>
</body>
</html>