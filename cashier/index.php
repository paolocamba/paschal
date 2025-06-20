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
  <!-- plugins:css -->
  <link rel="stylesheet" href="../dist/assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../dist/assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
  <!--FONTAWESOME CSS-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../dist/assets/js/select.dataTables.min.css">
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../dist/assets/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../dist/assets/images/ha.png" />
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

    .page-item.active .page-link {
      background-color: #00563B !important;
      border-color: #00563B !important;
    }

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
                    .navbar {
                    padding-top: 0 !important;
                    margin-top: 0 !important;
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
              <img src="../dist/assets/images/user/<?php echo $_SESSION['uploadID']; ?>" alt="profile" />
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
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Welcome <?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
                </div>
              </div>
            </div>
          </div>

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
              <div class="row">
                  <div class="col-12">
                      <h1 class="text-center mb-4">Transaction Analytics</h1>
                  </div>
              </div>

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
    <!-- plugins:js -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Transaction Status Chart
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
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>