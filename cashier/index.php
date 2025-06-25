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
$sql = "SELECT username, first_name, last_name, mobile, email, street, barangay, municipality, province, uploadID, is_logged_in FROM users WHERE user_id = '$id'";
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
}

// Assign the value of $username and $image to $_SESSION variables
$_SESSION['username'] = $name;
$_SESSION['uploadID'] = $uploadID;
$_SESSION['is_logged_in'] = $row['is_logged_in'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Transaction Analytics | Admin</title>
  
  <!-- CSS -->
  <link rel="stylesheet" href="../dist/assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../dist/assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../dist/assets/js/select.dataTables.min.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../dist/assets/css/style.css">
  <link rel="shortcut icon" href="../dist/assets/images/ha.png" />
  
  <style>
    :root {
        --primary-color: #03C03C;
        --primary-dark: #00563B;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --info-color: #17a2b8;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
    }
    
    .navbar {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    .nav-link i {
        margin-right: 10px;
    }
    
    .btn-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark) !important;
        border-color: var(--primary-dark) !important;
    }
    
    .page-item.active .page-link {
        background-color: var(--primary-dark) !important;
        border-color: var(--primary-dark) !important;
    }
    
    .welcome-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    /* Additional styles from your transaction analytics */
    .dashboard-card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .chart-container {
        width: 100%;
        max-width: 500px;
        height: 300px;
        margin: 0 auto;
    }

    .metric-card {
        background-color: #ffffff;
        border-left: 4px solid;
        padding: 15px;
        margin-bottom: 15px;
    }

    .metric-card h5 {
        font-size: 1rem;
        color: #6c757d;
    }

    .metric-card h3 {
        font-size: 1.75rem;
        margin-top: 0.5rem;
    }

    .border-primary {
        border-color: #007bff !important;
    }

    .border-success {
        border-color: #28a745 !important;
    }

    .border-warning {
        border-color: #ffc107 !important;
    }

    .text-primary {
        color: #007bff !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    /* Full-width card styling */
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    /* Form element styling */
    #reportForm .form-control, 
    #reportForm .form-select {
        height: 45px;
        border: 1px solid #e0e0e0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #reportForm .col-md-4, 
        #reportForm .col-md-3 {
            margin-bottom: 15px;
        }
    }

    /* Remove side padding for full width */
    .content-wrapper {
        padding: 1.5rem 0 !important;
    }

    .main-panel > .content-wrapper {
        padding: 1.5rem 1.5rem !important;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <!-- Stylized Navbar -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo me-5" href="index.php"><img src="../dist/assets/images/logo.png" class="me-2" alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/pmpc-logo.png" alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../dist/assets/images/user/<?php echo htmlspecialchars($_SESSION['uploadID']); ?>" alt="profile" />
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
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- Stylized Sidebar -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="fa-solid fa-gauge"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="transaction.php">
              <i class="fas fa-right-left"></i>
              <span class="menu-title">Transaction</span>
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
      
      <!-- main panel -->
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Stylized Welcome Header -->
          <div class="welcome-header">
            <div class="row">
              <div class="col-12 col-xl-8">
                <h3 class="font-weight-bold">Welcome <?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
                <h4 class="mb-0">Transaction Analytics Dashboard</h4>
              </div>
              <div class="col-12 col-xl-4 text-right">
                <p class="mb-0"><i class="far fa-calendar-alt mr-2"></i><?php echo date('l, F j, Y'); ?></p>
              </div>
            </div>
          </div>

          <!-- REST OF YOUR TRANSACTION ANALYTICS CONTENT GOES HERE -->
          <?php
          // Function to safely fetch data
          function fetchData($conn, $query, $params = []) {
            $stmt = $conn->prepare($query);

            if (!empty($params)) {
              $types = str_repeat('s', count($params));
              $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];

            while ($row = $result->fetch_assoc()) {
              $data[] = $row;
            }

            return $data;
          }

          // Fetch Transaction Data
          function fetchTransactionData($conn) {
              $transactionQuery = "
                  SELECT 
                      payment_status,
                      COUNT(*) as count,
                      SUM(amount) as total_amount
                  FROM transactions
                  WHERE payment_status IN ('In Progress', 'Completed')
                  GROUP BY payment_status
              ";
              return fetchData($conn, $transactionQuery);
          }

          // Fetch Monthly Transaction Totals
          function fetchMonthlyTransactions($conn) {
              $monthlyQuery = "
                  SELECT 
                      DATE_FORMAT(created_at, '%Y-%m') as month,
                      COUNT(*) as transaction_count,
                      SUM(amount) as monthly_total,
                      payment_status
                  FROM transactions
                  WHERE payment_status IN ('In Progress', 'Completed')
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m'), payment_status
                  ORDER BY month DESC
                  LIMIT 6
              ";
              return fetchData($conn, $monthlyQuery);
          }

          // Get transaction data
          $transactionData = fetchTransactionData($conn);
          $monthlyTransactions = fetchMonthlyTransactions($conn);

          // Calculate totals
          $totalAmount = 0;
          $completedAmount = 0;
          $inProgressAmount = 0;

          foreach ($transactionData as $data) {
              if ($data['payment_status'] == 'Completed') {
                  $completedAmount = $data['total_amount'];
              } elseif ($data['payment_status'] == 'In Progress') {
                  $inProgressAmount = $data['total_amount'];
              }
              $totalAmount += $data['total_amount'];
          }
          ?>

          <div class="container-fluid py-4">


              <!-- Quick Metrics Row -->
              <div class="row mb-4">
                  <div class="col-md-4">
                      <div class="metric-card border-primary">
                          <h5>Total Transaction Amount</h5>
                          <h3 class="text-primary">₱<?php echo number_format($totalAmount, 2); ?></h3>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="metric-card border-success">
                          <h5>Completed Transactions</h5>
                          <h3 class="text-success">₱<?php echo number_format($completedAmount, 2); ?></h3>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="metric-card border-warning">
                          <h5>In Progress Transactions</h5>
                          <h3 class="text-warning">₱<?php echo number_format($inProgressAmount, 2); ?></h3>
                      </div>
                  </div>
              </div>

              <!-- Charts Row -->
              <div class="row">
                  <div class="col-md-6">
                      <div class="card dashboard-card">
                          <div class="card-header bg-primary text-white">
                              <h5 class="card-title mb-0">Transaction Status Distribution</h5>
                          </div>
                          <div class="card-body chart-container">
                              <canvas id="transactionStatusChart"></canvas>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="card dashboard-card">
                          <div class="card-header bg-success text-white">
                              <h5 class="card-title mb-0">Monthly Transaction Trends</h5>
                          </div>
                          <div class="card-body chart-container">
                              <canvas id="monthlyTrendChart"></canvas>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Report Form Section -->
          <div class="row mb-4">
              <div class="col-12">
                  <div class="card">
                      <div class="card-body p-4">
                          <h4 class="card-title mb-4"><i class="fas fa-file-alt me-2"></i>Generate Transaction Report</h4>
                          <form id="reportForm" method="post" action="generate_report.php" target="_blank">
                              <div class="row align-items-end">
                                  <!-- Date Range Picker -->
                                  <div class="col-md-4 col-lg-3">
                                      <label class="form-label">From Date</label>
                                      <input type="date" class="form-control" id="startDate" name="startDate" required>
                                  </div>
                                  <div class="col-md-4 col-lg-3">
                                      <label class="form-label">To Date</label>
                                      <input type="date" class="form-control" id="endDate" name="endDate" required>
                                  </div>
                                  
                                  <!-- Report Type Dropdown -->
                                  <div class="col-md-3 col-lg-4">
                                      <label class="form-label">Report Type</label>
                                      <select class="form-select" id="reportType" name="reportType" required>
                                          <option value="">-- Select --</option>
                                          <option value="daily_collection">Transaction Collection</option>
                                          <option value="member_contributions">Member Contributions</option>
                                      </select>
                                  </div>
                                  
                                  <!-- Generate Button -->
                                  <div class="col-md-3 col-lg-2 mt-md-0 mt-2">
                                      <button type="submit" class="btn btn-primary w-100">
                                          <i class="fas fa-file-pdf me-2"></i>Generate
                                      </button>
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>

          <!-- footer -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024-2050. <a href="" target="_blank">Paschal</a>. All rights reserved.</span>
            </div>
          </footer>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="../dist/assets/js/off-canvas.js"></script>
  <script src="../dist/assets/js/template.js"></script>
  <script src="../dist/assets/js/settings.js"></script>
  <script src="../dist/assets/js/todolist.js"></script>
  <script src="../dist/assets/js/jquery.cookie.js"></script>

  <script>
    // Set default date range (current month)
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        
        document.getElementById('startDate').value = formatDate(firstDay);
        document.getElementById('endDate').value = formatDate(today);
        
        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }
    });

    // Transaction Status Chart
    document.addEventListener('DOMContentLoaded', function() {
        const transactionStatusCtx = document.getElementById('transactionStatusChart').getContext('2d');
        new Chart(transactionStatusCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($transactionData, 'payment_status')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($transactionData, 'count')); ?>,
                    backgroundColor: ['#28a745', '#ffc107'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Monthly Trends Chart
        const monthlyData = <?php echo json_encode($monthlyTransactions); ?>;
        const months = [...new Set(monthlyData.map(item => item.month))];
        
        const completedData = months.map(month => {
            const record = monthlyData.find(item => item.month === month && item.payment_status === 'Completed');
            return record ? record.monthly_total : 0;
        });

        const inProgressData = months.map(month => {
            const record = monthlyData.find(item => item.month === month && item.payment_status === 'In Progress');
            return record ? record.monthly_total : 0;
        });

        const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
        new Chart(monthlyTrendCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Completed',
                        data: completedData,
                        backgroundColor: '#28a745',
                        borderWidth: 1
                    },
                    {
                        label: 'In Progress',
                        data: inProgressData,
                        backgroundColor: '#ffc107',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
  </script>
</body>
</html>