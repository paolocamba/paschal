<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php';

// --- Analytics Functions ---
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

// Loan Status Data
$loanStatusQuery = "
    SELECT Status, COUNT(*) as count 
    FROM loanapplication 
    GROUP BY Status
";
$loanStatusData = fetchData($conn, $loanStatusQuery);

// Loan Type Distribution
$loanTypeQuery = "
    SELECT LoanType, COUNT(*) as count 
    FROM loanapplication 
    GROUP BY LoanType
";
$loanTypeData = fetchData($conn, $loanTypeQuery);

// Total Loans Data
$totalLoansQuery = "
    SELECT 
        COUNT(*) as total_loans,
        SUM(CASE WHEN Status = 'Approved' THEN 1 ELSE 0 END) as approved_loans,
        SUM(CASE WHEN Status = 'In Progress' THEN 1 ELSE 0 END) as pending_loans,
        SUM(CASE WHEN Status = 'Disapproved' THEN 1 ELSE 0 END) as rejected_loans,
        SUM(CASE WHEN Status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_loans
    FROM loanapplication
";
$totalLoansData = fetchData($conn, $totalLoansQuery)[0];

// Loan Amount Statistics
$loanAmountQuery = "
    SELECT 
        ROUND(AVG(AmountRequested), 2) as avg_requested,
        ROUND(MAX(AmountRequested), 2) as max_requested,
        ROUND(MIN(AmountRequested), 2) as min_requested,
        ROUND(SUM(AmountRequested), 2) as total_requested,
        ROUND(AVG(loanable_amount), 2) as avg_approved,
        ROUND(SUM(loanable_amount), 2) as total_approved
    FROM loanapplication
    WHERE Status = 'Approved'
";
$loanAmountData = fetchData($conn, $loanAmountQuery)[0];

// Monthly Loan Growth
$monthlyGrowthQuery = "
    SELECT 
        DATE_FORMAT(DateOfLoan, '%Y-%m') as month,
        COUNT(*) as new_loans,
        SUM(AmountRequested) as total_requested,
        SUM(loanable_amount) as total_approved
    FROM loanapplication
    GROUP BY DATE_FORMAT(DateOfLoan, '%Y-%m')
    ORDER BY month DESC
    LIMIT 12
";
$monthlyGrowthData = fetchData($conn, $monthlyGrowthQuery);

// Recent Loan Applications
$recentLoansQuery = "
    SELECT l.LoanID, u.first_name, u.last_name, l.AmountRequested, l.Status, l.DateOfLoan
    FROM loanapplication l
    JOIN users u ON l.userID = u.user_id
    ORDER BY l.DateOfLoan DESC
    LIMIT 5
";
$recentLoansData = fetchData($conn, $recentLoansQuery);

// Top Loan Purposes
$topPurposesQuery = "
    SELECT Purpose, COUNT(*) as loan_count
    FROM loanapplication
    GROUP BY Purpose
    ORDER BY loan_count DESC
    LIMIT 5
";
$topPurposesData = fetchData($conn, $topPurposesQuery);

// Active Loan Portfolio
$activeLoansQuery = "
    SELECT 
        ch.LoanType,
        COUNT(*) as active_loans,
        SUM(ch.Balance) as total_outstanding,
        ROUND(AVG(ch.Balance), 2) as avg_balance,
        ROUND(SUM(ch.Balance)/SUM(la.AmountRequested)*100, 1) as percent_paid
    FROM credit_history ch
    JOIN loanapplication la ON ch.LoanID = la.LoanID
    WHERE ch.Status = 'Active'
    GROUP BY ch.LoanType
";
$activeLoansData = fetchData($conn, $activeLoansQuery);

// Fetch user data
$id = $_SESSION['user_id'];
$sql = "SELECT username, first_name, last_name, mobile, email, street, barangay, municipality, province, uploadID, membership_type, is_logged_in FROM users WHERE user_id = '$id'";
$result = $conn->query($sql);

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
    $username = "Guest";
    $uploadID = "default_image.jpg";
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

$_SESSION['membership_type'] = $row['membership_type'];
$_SESSION['uploadID'] = $uploadID;
$_SESSION['is_logged_in'] = $row['is_logged_in'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard | Loan Officer</title>
    
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
        
        .card-analytics {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }
        
        .card-analytics:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .metric-card {
            border-left: 4px solid;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .metric-card.total-loans {
            border-left-color: var(--primary-color);
        }
        
        .metric-card.approved-loans {
            border-left-color: var(--success-color);
        }
        
        .metric-card.pending-loans {
            border-left-color: var(--warning-color);
        }
        
        .metric-card.rejected-loans {
            border-left-color: var(--danger-color);
        }
        
        .metric-card h5 {
            font-size: 14px;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .metric-card h3 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .dashboard-card {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            border: none;
        }
        
        .dashboard-card .card-header {
            padding: 15px 20px;
            border-bottom: none;
        }
        
        .dashboard-card .card-body {
            padding: 20px;
        }
        
        .people {
            border-radius: 20px;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .recent-loans .badge {
            padding: 6px 10px;
            font-weight: 500;
            font-size: 12px;
        }
        
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-approved {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .badge-cancelled {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        .progress-thin {
            height: 8px;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .welcome-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            border-radius: 10px;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .stat-card i {
            font-size: 36px;
            margin-bottom: 15px;
        }
        
        .stat-card h3 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            font-size: 14px;
            margin-bottom: 0;
            opacity: 0.9;
        }
        
        .amount-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .amount-card h5 {
            font-size: 14px;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }
        
        .amount-card h3 {
            font-size: 22px;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
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
            <!-- sidebar -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fa-solid fa-gauge"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="loan.php">
                        <i class="fa-solid fa-money-bill-wave"></i>
                        <span class="menu-title">Loan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="inbox.php">
                        <i class="fa-solid fa-comment"></i>
                        <span class="menu-title">Inbox</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="land_appraisal.php">
                        <i class="fa-solid fa-landmark"></i>
                        <span class="menu-title">Land Appraisal</span>
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
                    <!-- Welcome Header -->
                    <div class="welcome-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h3 class="font-weight-bold mb-2">Welcome <?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
                                <h4 class="mb-0">Loan Officer Dashboard</h4>
                            </div>
                            <div class="col-md-4 text-right">
                                <p class="mb-0"><i class="far fa-calendar-alt mr-2"></i><?php echo date('l, F j, Y'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #03C03C, #00563B);">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <h3><?php echo $totalLoansData['total_loans']; ?></h3>
                                <p>Total Loans</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                                <i class="fas fa-check-circle"></i>
                                <h3><?php echo $totalLoansData['approved_loans']; ?></h3>
                                <p>Approved Loans</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                                <i class="fas fa-clock"></i>
                                <h3><?php echo $totalLoansData['pending_loans']; ?></h3>
                                <p>Pending Loans</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545, #bd2130);">
                                <i class="fas fa-times-circle"></i>
                                <h3><?php echo $totalLoansData['rejected_loans'] + $totalLoansData['cancelled_loans']; ?></h3>
                                <p>Rejected/Cancelled</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Loan Amount Statistics -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="amount-card">
                                <h5>Avg. Requested Amount</h5>
                                <h3>₱<?php echo number_format($loanAmountData['avg_requested'], 2); ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="amount-card">
                                <h5>Avg. Approved Amount</h5>
                                <h3>₱<?php echo number_format($loanAmountData['avg_approved'], 2); ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="amount-card">
                                <h5>Total Requested</h5>
                                <h3>₱<?php echo number_format($loanAmountData['total_requested'], 2); ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="amount-card">
                                <h5>Total Approved</h5>
                                <h3>₱<?php echo number_format($loanAmountData['total_approved'], 2); ?></h3>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <!-- Loan Growth Chart -->
                            <div class="card dashboard-card shadow">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0 text-white">Loan Applications (Last 12 Months)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="loanGrowthChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Recent Loan Applications -->
                            <div class="card dashboard-card shadow">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0 text-white">Recent Loan Applications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Loan ID</th>
                                                    <th>Borrower</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentLoansData as $loan): ?>
                                                <tr>
                                                    <td>#<?php echo $loan['LoanID']; ?></td>
                                                    <td><?php echo htmlspecialchars($loan['first_name'] . ' ' . $loan['last_name']); ?></td>
                                                    <td>₱<?php echo number_format($loan['AmountRequested'], 2); ?></td>
                                                    <td>
                                                        <?php 
                                                        $statusClass = '';
                                                        if ($loan['Status'] == 'In Progress') $statusClass = 'badge-pending';
                                                        elseif ($loan['Status'] == 'Approved') $statusClass = 'badge-approved';
                                                        elseif ($loan['Status'] == 'Disapproved') $statusClass = 'badge-rejected';
                                                        elseif ($loan['Status'] == 'Cancelled') $statusClass = 'badge-cancelled';
                                                        ?>
                                                        <span class="badge <?php echo $statusClass; ?>"><?php echo $loan['Status']; ?></span>
                                                    </td>
                                                    <td><?php echo date('M d, Y', strtotime($loan['DateOfLoan'])); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-4">
                            <!-- Loan Status Distribution -->
                            <div class="card dashboard-card shadow">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0 text-white">Loan Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="loanStatusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Loan by Type -->
                            <div class="card dashboard-card shadow">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="card-title mb-0 text-white">Loan Types</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="loanTypeChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Top Loan Purposes -->
                        <div class="col-md-4">
                            <div class="card dashboard-card shadow">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="card-title mb-0 text-white">Top Loan Purposes</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($topPurposesData as $purpose): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?php echo htmlspecialchars($purpose['Purpose']); ?>
                                            <span class="badge badge-primary badge-pill"><?php echo $purpose['loan_count']; ?></span>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Active Loan Portfolio -->
                        <div class="col-md-8">
                            <div class="card dashboard-card shadow">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="card-title mb-0 text-white">Active Loan Portfolio</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Loan Type</th>
                                                    <th>Active Loans</th>
                                                    <th>Total Outstanding</th>
                                                    <th>Avg. Balance</th>
                                                    <th>% Paid</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($activeLoansData as $loan): ?>
                                                <tr>
                                                    <td><?php echo $loan['LoanType']; ?></td>
                                                    <td><?php echo $loan['active_loans']; ?></td>
                                                    <td>₱<?php echo number_format($loan['total_outstanding'], 2); ?></td>
                                                    <td>₱<?php echo number_format($loan['avg_balance'], 2); ?></td>
                                                    <td><?php echo $loan['percent_paid']; ?>%</td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                <!-- footer -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024-2050. <a href="" target="_blank">Paschal</a>. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Version 1.0.0</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../dist/assets/vendors/chart.js/chart.umd.js"></script>
    <script src="../dist/assets/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="../dist/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script>
    <script src="../dist/assets/js/dataTables.select.min.js"></script>
    <script src="../dist/assets/js/off-canvas.js"></script>
    <script src="../dist/assets/js/template.js"></script>
    <script src="../dist/assets/js/settings.js"></script>
    <script src="../dist/assets/js/todolist.js"></script>
    <script src="../dist/assets/js/jquery.cookie.js"></script>
    <script src="../dist/assets/js/dashboard.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Loan Status Chart
        const loanStatusCtx = document.getElementById('loanStatusChart').getContext('2d');
        new Chart(loanStatusCtx, {
            type: 'doughnut',
            data: {
                labels: [<?php foreach ($loanStatusData as $status) echo "'{$status['Status']}',"; ?>],
                datasets: [{
                    data: [<?php foreach ($loanStatusData as $status) echo "{$status['count']},"; ?>],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d', '#17a2b8'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });

        // Loan Type Chart
        const loanTypeCtx = document.getElementById('loanTypeChart').getContext('2d');
        new Chart(loanTypeCtx, {
            type: 'pie',
            data: {
                labels: [<?php foreach ($loanTypeData as $type) echo "'{$type['LoanType']}',"; ?>],
                datasets: [{
                    data: [<?php foreach ($loanTypeData as $type) echo "{$type['count']},"; ?>],
                    backgroundColor: [
                        '#03C03C',
                        '#00563B',
                        '#4B9CD3',
                        '#FFD700',
                        '#FF6B6B'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Loan Growth Chart
        const growthCtx = document.getElementById('loanGrowthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: [<?php foreach (array_reverse($monthlyGrowthData) as $month) echo "'" . date('M Y', strtotime($month['month'] . '-01')) . "',"; ?>],
                datasets: [{
                    label: 'New Loan Applications',
                    data: [<?php foreach (array_reverse($monthlyGrowthData) as $month) echo "{$month['new_loans']},"; ?>],
                    backgroundColor: 'rgba(3, 192, 60, 0.2)',
                    borderColor: 'rgba(3, 192, 60, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(3, 192, 60, 1)',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} new loans`;
                            }
                        }
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    } 
                }
            }
        });
    });
    </script>
</body>
</html>