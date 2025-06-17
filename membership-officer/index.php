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

// Membership Status Data
$membershipStatusQuery = "
    SELECT membership_status, COUNT(*) as count 
    FROM users 
    WHERE user_type = 'Member' 
    GROUP BY membership_status
";
$membershipStatusData = fetchData($conn, $membershipStatusQuery);

// Application Status Data
$applicationStatusQuery = "
    SELECT status, COUNT(*) as count 
    FROM member_applications 
    GROUP BY status
";
$applicationStatusData = fetchData($conn, $applicationStatusQuery);

// Total Members Data
$totalMembersQuery = "
    SELECT COUNT(*) as total_members,
           SUM(CASE WHEN membership_status = 'Active' THEN 1 ELSE 0 END) as active_members,
           SUM(CASE WHEN membership_status = 'Pending' THEN 1 ELSE 0 END) as pending_members,
           SUM(CASE WHEN membership_status = 'Expired' THEN 1 ELSE 0 END) as expired_members,
           SUM(CASE WHEN membership_status = 'Suspended' THEN 1 ELSE 0 END) as suspended_members
    FROM users
    WHERE user_type = 'Member'
";
$totalMembersData = fetchData($conn, $totalMembersQuery)[0];

// Application Progress Data
$applicationProgressQuery = "
    SELECT status, COUNT(*) as count,
           ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM member_applications), 2) as percentage
    FROM member_applications
    GROUP BY status
";
$applicationProgressData = fetchData($conn, $applicationProgressQuery);

// Monthly Membership Growth
$monthlyGrowthQuery = "
    SELECT 
        DATE_FORMAT(account_opening_date, '%Y-%m') as month,
        COUNT(*) as new_members
    FROM users
    WHERE user_type = 'Member'
    GROUP BY DATE_FORMAT(account_opening_date, '%Y-%m')
    ORDER BY month DESC
    LIMIT 12
";
$monthlyGrowthData = fetchData($conn, $monthlyGrowthQuery);

// Membership by Type
$membershipTypeQuery = "
    SELECT membership_type, COUNT(*) as count
    FROM users
    WHERE user_type = 'Member'
    GROUP BY membership_type
";
$membershipTypeData = fetchData($conn, $membershipTypeQuery);

// Recent Applications
$recentApplicationsQuery = "
    SELECT a.id, a.user_id, u.first_name, u.last_name, a.status, a.created_at
    FROM member_applications a
    JOIN users u ON a.user_id = u.user_id
    ORDER BY a.created_at DESC
    LIMIT 5
";
$recentApplicationsData = fetchData($conn, $recentApplicationsQuery);

// Top Municipalities
$topMunicipalitiesQuery = "
    SELECT municipality, COUNT(*) as member_count
    FROM users
    WHERE user_type = 'Member'
    GROUP BY municipality
    ORDER BY member_count DESC
    LIMIT 5
";
$topMunicipalitiesData = fetchData($conn, $topMunicipalitiesQuery);

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
    <title>Dashboard | Membership Officer</title>
    
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
        
        .metric-card.total-members {
            border-left-color: var(--primary-color);
        }
        
        .metric-card.active-members {
            border-left-color: var(--success-color);
        }
        
        .metric-card.pending-members {
            border-left-color: var(--warning-color);
        }
        
        .metric-card.expired-members {
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
        
        .recent-applications .badge {
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
        
        .badge-review {
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
                    <a class="nav-link" href="appointment.php">
                        <i class="fas fa-regular fa-calendar"></i>
                        <span class="menu-title">Appointments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="member.php">
                        <i class="fas fa-users"></i>
                        <span class="menu-title">Members</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="inbox.php">
                        <i class="fa-solid fa-comment"></i>
                        <span class="menu-title">Inbox</span>
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
                                <h4 class="mb-0"><?php echo htmlspecialchars($_SESSION['membership_type']); ?> Dashboard</h4>
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
                                <i class="fas fa-users"></i>
                                <h3><?php echo $totalMembersData['total_members']; ?></h3>
                                <p>Total Members</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
                                <i class="fas fa-user-check"></i>
                                <h3><?php echo $totalMembersData['active_members']; ?></h3>
                                <p>Active Members</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #ffc107, #e0a800);">
                                <i class="fas fa-clock"></i>
                                <h3><?php echo $totalMembersData['pending_members']; ?></h3>
                                <p>Pending Members</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card" style="background: linear-gradient(135deg, #dc3545, #bd2130);">
                                <i class="fas fa-user-times"></i>
                                <h3><?php echo $totalMembersData['expired_members'] + $totalMembersData['suspended_members']; ?></h3>
                                <p>Inactive Members</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <!-- Membership Growth Chart -->
                            <div class="card dashboard-card shadow">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0 text-white">Membership Growth (Last 12 Months)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="membershipGrowthChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Recent Applications -->
                            <div class="card dashboard-card shadow">
                                <div class="card-header bg-info text-white">
                                    <h5 class="card-title mb-0 text-white">Recent Applications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentApplicationsData as $application): ?>
                                                <tr>
                                                    <td>#<?php echo $application['id']; ?></td>
                                                    <td><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></td>
                                                    <td>
                                                        <?php 
                                                        $statusClass = '';
                                                        if ($application['status'] == 'Pending') $statusClass = 'badge-pending';
                                                        elseif ($application['status'] == 'Approved') $statusClass = 'badge-approved';
                                                        elseif ($application['status'] == 'Rejected') $statusClass = 'badge-rejected';
                                                        else $statusClass = 'badge-review';
                                                        ?>
                                                        <span class="badge <?php echo $statusClass; ?>"><?php echo $application['status']; ?></span>
                                                    </td>
                                                    <td><?php echo date('M d, Y', strtotime($application['created_at'])); ?></td>
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
                            <!-- Membership Status Distribution -->
                            <div class="card dashboard-card shadow">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title mb-0 text-white">Membership Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="memberStatusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Membership by Type -->
                            <div class="card dashboard-card shadow">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="card-title mb-0 text-white">Membership Types</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="membershipTypeChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
        
                        </div>
                    </div>
                    
<div class="row">
    <!-- Top Municipalities -->
    <div class="col-md-4">
        <div class="card dashboard-card shadow">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title mb-0 text-white">Top Municipalities</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php foreach ($topMunicipalitiesData as $municipality): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($municipality['municipality']); ?>
                        <span class="badge badge-primary badge-pill"><?php echo $municipality['member_count']; ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Application Progress -->
    <div class="col-md-4">
        <div class="card dashboard-card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0 text-white">Application Progress</h5>
            </div>
            <div class="card-body">
                <?php foreach ($applicationProgressData as $progress): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span><?php echo $progress['status']; ?></span>
                        <span><?php echo $progress['percentage']; ?>%</span>
                    </div>
                    <div class="progress progress-thin">
                        <div class="progress-bar 
                            <?php 
                            if ($progress['status'] == 'Approved') echo 'bg-success';
                            elseif ($progress['status'] == 'Pending') echo 'bg-warning';
                            elseif ($progress['status'] == 'Rejected') echo 'bg-danger';
                            else echo 'bg-info';
                            ?>" 
                            role="progressbar" 
                            style="width: <?php echo $progress['percentage']; ?>%" 
                            aria-valuenow="<?php echo $progress['percentage']; ?>" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Application Status Overview -->
    <div class="col-md-4">
        <div class="card dashboard-card shadow">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0 text-white">Application Status Overview</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="applicationStatusChart"></canvas>
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
        // Membership Status Chart
        const memberStatusCtx = document.getElementById('memberStatusChart').getContext('2d');
        new Chart(memberStatusCtx, {
            type: 'doughnut',
            data: {
                labels: [<?php foreach ($membershipStatusData as $status) echo "'{$status['membership_status']}',"; ?>],
                datasets: [{
                    data: [<?php foreach ($membershipStatusData as $status) echo "{$status['count']},"; ?>],
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

        // Application Status Chart
        const applicationStatusCtx = document.getElementById('applicationStatusChart').getContext('2d');
        new Chart(applicationStatusCtx, {
            type: 'bar',
            data: {
                labels: [<?php foreach ($applicationStatusData as $status) echo "'{$status['status']}',"; ?>],
                datasets: [{
                    label: 'Applications',
                    data: [<?php foreach ($applicationStatusData as $status) echo "{$status['count']},"; ?>],
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(220, 53, 69, 0.7)',
                        'rgba(108, 117, 125, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 193, 7, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(108, 117, 125, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} applications`;
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

        // Membership Type Chart
        const membershipTypeCtx = document.getElementById('membershipTypeChart').getContext('2d');
        new Chart(membershipTypeCtx, {
            type: 'pie',
            data: {
                labels: [<?php foreach ($membershipTypeData as $type) echo "'{$type['membership_type']}',"; ?>],
                datasets: [{
                    data: [<?php foreach ($membershipTypeData as $type) echo "{$type['count']},"; ?>],
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

        // Membership Growth Chart
        const growthCtx = document.getElementById('membershipGrowthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: [<?php foreach (array_reverse($monthlyGrowthData) as $month) echo "'" . date('M Y', strtotime($month['month'] . '-01')) . "',"; ?>],
                datasets: [{
                    label: 'New Members',
                    data: [<?php foreach (array_reverse($monthlyGrowthData) as $month) echo "{$month['new_members']},"; ?>],
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
                                return `${context.parsed.y} new members`;
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