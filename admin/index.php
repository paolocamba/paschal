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
$_SESSION['is_logged_in'] = $row['is_logged_in']; // Add this line
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard | Admin</title>
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
</head>

<body>
  <div class="container-scroller">

  </div>
  <!-- partial:partials/_navbar.html -->
  <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
      <a class="navbar-brand brand-logo me-5" href="index.php"><img src="../dist/assets/images/logo.png" class="me-2"
          alt="logo" /></a>
      <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/logo.png"
          alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="icon-menu"></span>
      </button>

      <ul class="navbar-nav mr-lg-2">
        <!--<li class="nav-item nav-search d-none d-lg-block">
        <div class="input-group">
          <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
            <span class="input-group-text" id="search">
              <i class="icon-search"></i>
            </span>
          </div>
          <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
        </div>
      </li>-->
      </ul>
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
    </style>
   <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fa-solid fa-gauge"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="member.php">
                        <i class="fas fa-users"></i>
                        <span class="menu-title">Members</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="loans.php">
                        <i class="fas fa-money-bill"></i>
                        <span class="menu-title">Loans</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="land_appraisal.php">
                        <i class="fa-solid fa-landmark"></i>
                        <span class="menu-title">Land Appraisal</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="appointment.php">
                        <i class="fas fa-regular fa-calendar"></i>
                        <span class="menu-title">Appointments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="transaction.php">
                        <i class="fas fa-right-left"></i>
                        <span class="menu-title">Transaction</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="announcement.php">
                        <i class="fas fa-bullhorn"></i>
                        <span class="menu-title">Announcement</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">
                        <i class="fas fa-calendar-check"></i>
                        <span class="menu-title">Events</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="savings.php">
                        <i class="fa-solid fa-piggy-bank"></i>
                        <span class="menu-title">Savings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sharecapital.php">
                        <i class="fa-solid fa-coins"></i>
                        <span class="menu-title">Share Capital</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">
                        <i class="fa-brands fa-slack"></i>
                        <span class="menu-title">Services</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="location.php">
                        <i class="fa-solid fa-location-dot"></i>
                        <span class="menu-title">Location</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php">
                        <i class="fa-solid fa-users"></i>
                        <span class="menu-title">Users</span>
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
                <!-- <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6> -->
              </div>
              <div class="col-12 col-xl-4">
                <div class="justify-content-end d-flex">
                  <div class="dropdown flex-md-grow-1 flex-xl-grow-0">


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <style>
          .people {
            border-radius: 20px;
          }
        </style>
        <div class="row">
          <div class="col-md-6 grid-margin stretch-card">
            <div class="card" style="background: #03C03C;">
              <div class="card mt-auto">
                <img src="../dist/assets/images/paschal.png" alt="people" class="people">
                <div class="weather-info">
                  <div class="d-flex">
                    <div>
                      <h2 class="mb-0 font-weight-normal"><sup></sup></h2>
                    </div>
                    <div class="ms-2">
                      <h4 class="location font-weight-normal"></h4>
                      <h6 class="font-weight-normal"></h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 grid-margin transparent">
              <div class="row">
                  <!-- Total Appointments -->
                  <div class="col-md-6 mb-4 stretch-card transparent">
                      <div class="card card" style="background: #03C03C;">
                          <div class="card-body">
                              <p class="mb-4 text-white">Total Number of Appointments</p>
                              <p class="fs-30 mb-2 text-white">
                                  <?php
                                  include('../connection/config.php');
                                  $result = mysqli_query($conn, "SELECT COUNT(*) AS total_appointments FROM appointments");
                                  $row = mysqli_fetch_assoc($result);
                                  echo $row['total_appointments'];
                                  ?>
                              </p>
                          </div>
                      </div>
                  </div>
                  <!-- Total Active Loans -->
                  <div class="col-md-6 mb-4 stretch-card transparent">
                      <div class="card card" style="background: #03C03C;">
                          <div class="card-body">
                              <p class="mb-4 text-white">Total Number of Active Loans</p>
                              <p class="fs-30 mb-2 text-white">
                                  <?php
                                  $result = mysqli_query($conn, "SELECT COUNT(*) AS total_active_loans FROM credit_history WHERE ApprovalStatus = 'Approved'");
                                  $row = mysqli_fetch_assoc($result);
                                  echo $row['total_active_loans'];
                                  ?>
                              </p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>


            
            </div>
          </div>
          <style>
            /* Preview thumbnail styles */


            /* Modal styles */
            .modal-overlay {
              position: fixed;
              bottom: 20px;
              left: 80%;
              z-index: 1000;
              display: none;
            }

            .modal-container {
              background: white;
              border-radius: 8px;
              box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
              width: 350px;
              max-width: 90vw;
              border: 1px solid #ddd;
            }

            .modal-header {
              display: flex;
              justify-content: space-between;
              align-items: center;
              padding: 15px;
              border-bottom: 1px solid #eee;
            }

            .modal-close {
              cursor: pointer;
              font-size: 24px;
            }

            .modal-body {
              padding: 15px;
            }

            select,
            textarea {
              width: 100%;
              margin-bottom: 15px;
              padding: 8px;
              border: 1px solid #ddd;
              border-radius: 4px;
            }

            .dashboard-card {
              box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
              border-radius: 10px;
              margin-bottom: 20px;
            }

            .chart-container {
              width: 100%;
              max-width: 500px;
              /* Adjust the maximum width as needed */
              height: 300px;
              /* Adjust the height as needed */
              margin: 0 auto;
              /* Center the chart horizontally */
            }

            #memberStatusChart {
              width: 100%;
              height: 100%;
            }

            .metric-card {
              background-color: #ffffff;
              border-left: 4px solid;
              padding: 15px;
              margin-bottom: 15px;
            }

            /* Responsive styles */
            @media (max-width: 767px) {
              .modal-overlay {
                left: 60%;
                transform: translateX(-50%);
              }

              .modal-container {
                width: 90%;
              }

              select,
              textarea {
                font-size: 16px;
              }


            }
          </style>
          <?php

          include '../connection/config.php';

          // Function to safely fetch data
          function fetchData($conn, $query, $params = [])
          {
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

          // Fetch Membership Status Data
          $membershipStatusQuery = "
              SELECT 
                  membership_status, 
                  COUNT(*) as count 
              FROM users 
              WHERE user_type = 'Member' 
              GROUP BY membership_status
          ";
          $membershipStatusData = fetchData($conn, $membershipStatusQuery);

          // Fetch Application Status Data
          $applicationStatusQuery = "
              SELECT 
                  status, 
                  COUNT(*) as count 
              FROM member_applications 
              GROUP BY status
          ";
          $applicationStatusData = fetchData($conn, $applicationStatusQuery);

          // Additional Metrics
          $totalMembersQuery = "
              SELECT 
                  COUNT(*) as total_members,
                  SUM(CASE WHEN membership_status = 'Active' THEN 1 ELSE 0 END) as active_members
              FROM users
              WHERE user_type = 'Member'
          ";
          $totalMembersData = fetchData($conn, $totalMembersQuery)[0];

          // Application Progress Metrics
          $applicationProgressQuery = "
              SELECT 
                  status, 
                  COUNT(*) as count,
                  ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM member_applications), 2) as percentage
              FROM member_applications
              GROUP BY status
          ";
          $applicationProgressData = fetchData($conn, $applicationProgressQuery);
          ?>
          <div class="container-fluid py-4">
            <div class="row">
              <div class="col-12">
                <h1 class="text-center mb-4">Member Analytics</h1>
              </div>
            </div>

            <!-- Quick Metrics Row -->
            <div class="row mb-4">
              <div class="col-md-4">
                <div class="metric-card border-primary">
                  <h5>Total Members</h5>
                  <h3 class="text-primary"><?php echo $totalMembersData['total_members']; ?></h3>
                </div>
              </div>
              <div class="col-md-4">
                <div class="metric-card border-success">
                  <h5>Active Members</h5>
                  <h3 class="text-success"><?php echo $totalMembersData['active_members']; ?></h3>
                </div>
              </div>
              <div class="col-md-4">
                <div class="metric-card border-warning">
                  <h5>In Progress Applications</h5>
                  <h3 class="text-warning">
                    <?php
                    $inProgressCount = array_filter($applicationProgressData, function ($item) {
                      return $item['status'] == 'In Progress';
                    });
                    echo !empty($inProgressCount) ? reset($inProgressCount)['count'] : 0;
                    ?>
                  </h3>
                </div>
              </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
              <div class="col-md-6">
                <div class="card dashboard-card">
                  <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Membership Status Distribution</h5>
                  </div>
                  <div class="card-body chart-container">
                    <canvas id="memberStatusChart"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card dashboard-card">
                  <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Application Status Overview</h5>
                  </div>
                  <div class="card-body chart-container">
                    <canvas id="applicationStatusChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
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
          <?php

            // Check if the "success" parameter is set in the URL
            if (isset($_GET['success']) && $_GET['success'] == 1) {
            // Use SweetAlert to show a success message
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "success",
                        title: "Your Message Send Successfully",
                        showConfirmButton: false,
                        timer: 1500 // Close after 1.5 seconds
                    });
                });
            </script>';
            }
            // Check if the "success" parameter is set in the URL
            if (isset($_GET['success']) && $_GET['success'] == 2) {
              // Use SweetAlert to show a success message
              echo '<script>
                  document.addEventListener("DOMContentLoaded", function() {
                      Swal.fire({
                          icon: "success",
                          title: "You Replied Successfully",
                          showConfirmButton: false,
                          timer: 1500 // Close after 1.5 seconds
                      });
                  });
              </script>';
              }
           
             ?>



          <?php
          include '../connection/config.php';

          // Fetch the search query and page number
          $search = isset($_GET['search']) ? $_GET['search'] : '';
          $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
          $limit = 10;
          $offset = ($page - 1) * $limit;


          $user_id = $_SESSION['user_id'];

          $sql = "SELECT 
                        message_id,
                        sender_id,
                        category,
                        message,
                        is_replied,
                        is_read,
                        datesent
                    FROM messages
                    WHERE receiver_id = ? AND
                        (category LIKE ? 
                        OR message LIKE ?
                        OR DATE_FORMAT(datesent, '%M %d, %Y') LIKE ?)
                    LIMIT ? OFFSET ?";

          $stmt = $conn->prepare($sql);
          $search_param = "%" . $search . "%";
          $stmt->bind_param(
            "isssii",
            $user_id,
            $search_param,
            $search_param,
            $search_param,
            $limit,
            $offset
          );
          $stmt->execute();
          $result = $stmt->get_result();

          // Count query for pagination
          $count_sql = "SELECT COUNT(*) as total 
                FROM messages 
                WHERE receiver_id = ? AND
                    (category LIKE ?
                    OR message LIKE ?
                    OR DATE_FORMAT(datesent, '%M %d, %Y') LIKE ?)";

          $count_stmt = $conn->prepare($count_sql);
          $count_stmt->bind_param(
            "isss",
            $user_id,
            $search_param,
            $search_param,
            $search_param
          );
          $count_stmt->execute();
          $count_result = $count_stmt->get_result();
          $total_rows = $count_result->fetch_assoc()['total'];
          $total_pages = ceil($total_rows / $limit);

          // Fetch member users for the message modal
          $member_sql = "SELECT CONCAT(first_name, ' ', last_name) as full_name, user_id 
                        FROM users 
                        WHERE user_type = 'Member'";
          $member_result = $conn->query($member_sql);
          ?>

          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <p class="card-title mb-0">Messages</p>
                    <div class="ml-auto">
                      <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#sendMessageModal">
                        Send Message
                      </button>
                    </div>
                  </div>
                  <div class="mb-3">
                    <form method="GET" action="" class="form-inline" id="searchForm">
                      <div class="input-group mb-2 mr-sm-2">
                        <input type="text" name="search" id="searchInput" class="form-control"
                          value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Messages">
                        <div class="input-group-append">
                          <button type="button" class="btn btn-outline-secondary" id="clearButton"
                            style="padding:10px;">&times;</button>
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary mb-2 mr-3" style="background:#03C03C;"><i
                          class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                      <thead>
                        <tr>
                          <th>Category</th>
                          <th>Message</th>
                          <th>Date</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($result->num_rows > 0): ?>
                          <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                              <td><?php echo htmlspecialchars($row['category']); ?></td>
                              <td><?php echo htmlspecialchars($row['message']); ?></td>
                              <td><?php echo date('F d, Y h:i A', strtotime($row['datesent'])); ?></td>

                              <td>
                                <button class="btn btn-primary btn-sm" data-toggle="modal"
                                  data-target="#viewModal<?php echo $row['message_id']; ?>"><i
                                    class="fa-solid fa-eye"></i></button>
                              </td>
                            </tr>
                            <!-- View Modal -->

                            <div class="modal fade" id="viewModal<?php echo $row['message_id']; ?>" tabindex="-1"
                              role="dialog" aria-labelledby="viewModalLabel<?php echo $row['message_id']; ?>"
                              aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="viewModalLabel<?php echo $row['message_id']; ?>">Message
                                      Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="message-details">
                                      <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                                      <p><strong>Message:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                                      <p><strong>Date Sent:</strong>
                                        <?php echo date('F d, Y h:i A', strtotime($row['datesent'])); ?></p>

                                      <!-- Admin Reply Section -->
                                      <?php if (!empty($row['admin_reply'])): ?>
                                        <hr>
                                        <div class="admin-reply">
                                          <h6>Admin Reply:</h6>
                                          <p><strong>Reply:</strong> <?php echo htmlspecialchars($row['admin_reply']); ?></p>
                                          <p><strong>Reply Date:</strong>
                                            <?php echo htmlspecialchars($row['admin_reply_date']); ?></p>
                                        </div>
                                      <?php endif; ?>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <?php if (empty($row['admin_reply'])): ?>
                                      <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal"
                                        data-target="#replyModal<?php echo $row['message_id']; ?>">
                                        Reply
                                      </button>
                                    <?php else: ?>
                                      <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete(<?php echo $row['message_id']; ?>)">
                                        Delete
                                      </button>
                                    <?php endif; ?>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- Reply Modal -->
                            <div class="modal fade" id="replyModal<?php echo $row['message_id']; ?>" tabindex="-1"
                              role="dialog" aria-labelledby="replyModalLabel<?php echo $row['message_id']; ?>"
                              aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="replyModalLabel<?php echo $row['message_id']; ?>">Reply to
                                      Message</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <form action="reply.php" method="POST">
                                    <div class="modal-body">
                                      <input type="hidden" name="message_id" value="<?php echo $row['message_id']; ?>">
                                      <div class="form-group">
                                        <label for="replyText<?php echo $row['message_id']; ?>">Your Reply</label>
                                        <textarea class="form-control" id="replyText<?php echo $row['message_id']; ?>"
                                          name="admin_reply" rows="4" required></textarea>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                      <button type="submit" class="btn btn-primary">Send Reply</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          <?php endwhile; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="5">No messages found</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                  <br>
                  <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                      <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link"
                          href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page - 1; ?>"
                          aria-label="Previous">
                          <span aria-hidden="true">&laquo; </span>
                        </a>
                      </li>
                      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                          <a class="page-link"
                            href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                      <?php endfor; ?>
                      <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link"
                          href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page + 1; ?>"
                          aria-label="Next">
                          <span aria-hidden="true"> &raquo;</span>
                        </a>
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>

          <!-- Message Modal -->
          <div id="sendMessageModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Send a Message</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="send_message.php" method="POST">
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="recipient">Send to:</label>
                      <select class="form-control" id="recipient" name="recipient" required>
                        <option value="">Select Member</option>
                        <?php while ($member = $member_result->fetch_assoc()): ?>
                          <option value="<?php echo $member['user_id']; ?>">
                            <?php echo htmlspecialchars($member['full_name']); ?>
                          </option>
                        <?php endwhile; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="category">Category:</label>
                      <select class="form-control" id="category" name="category" required>
                        <option value="General Query">General Query</option>
                        <option value="Services">Services</option>
                        <option value="Loan">Loan</option>
                        <option value="Medical">Medical</option>
                        <option value="Membership">Membership</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="message">Message:</label>
                      <textarea class="form-control" id="admin_message" name="admin_message" rows="4" required></textarea>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>




          <br><br><br><br><br><br><br>

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
      document.addEventListener('DOMContentLoaded', function () {
        var searchForm = document.getElementById('searchForm');
        var searchInput = document.getElementById('searchInput');
        var clearButton = document.getElementById('clearButton');

        clearButton.addEventListener('click', function () {
          searchInput.value = '';
          searchForm.submit(); // Submit the form to reload all records
        });
      });
      function confirmDelete(messageId) {
        if (confirm('Are you sure you want to delete this message?')) {
          window.location.href = 'delete_message.php?message_id=' + messageId;
        }
      }
      document.addEventListener('DOMContentLoaded', function () {
        // Membership Status Chart
        var memberStatusCtx = document.getElementById('memberStatusChart').getContext('2d');
        new Chart(memberStatusCtx, {
          type: 'pie',
          data: {
            labels: [
              <?php foreach ($membershipStatusData as $status): ?>
                      '<?php echo $status['membership_status']; ?>',
              <?php endforeach; ?>
            ],
            datasets: [{
              data: [
                <?php foreach ($membershipStatusData as $status): ?>
                          <?php echo $status['count']; ?>,
                <?php endforeach; ?>
              ],
              backgroundColor: [
                '#007bff', '#28a745', '#dc3545', '#ffc107', '#6c757d'
              ]
            }]
          },
          options: {
            title: {
              display: true,
              text: 'Membership Status Distribution'
            }
          }
        });

        // Application Status Chart
        var applicationStatusCtx = document.getElementById('applicationStatusChart').getContext('2d');
        new Chart(applicationStatusCtx, {
          type: 'bar',
          data: {
            labels: [
              <?php foreach ($applicationStatusData as $status): ?>
                      '<?php echo $status['status']; ?>',
              <?php endforeach; ?>
            ],
            datasets: [{
              label: 'Application Status',
              data: [
                <?php foreach ($applicationStatusData as $status): ?>
                          <?php echo $status['count']; ?>,
                <?php endforeach; ?>
              ],
              backgroundColor: [
                '#ffc107', '#28a745', '#dc3545', '#dc3545', '#6c757d'
              ]
            }]
          },
          options: {
            title: {
              display: true,
              text: 'Member Application Status'
            },
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
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
    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->
</body>

</html>