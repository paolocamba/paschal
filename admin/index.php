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
    <title>Dashboard | Admin</title>
    
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

                /* Add these styles to your existing CSS */
        
        /* Card Styles */
        .dashboard-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .card-icon-container {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .card-icon {
            font-size: 1.8rem;
        }
        
        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        
        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .card-link {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .card-link:hover {
            transform: translateY(-2px);
        }
        
        /* Color variations for each card */
        .announcements-card .card-icon-container {
            background-color: rgba(3, 192, 60, 0.1);
        }
        
        .announcements-card .card-icon {
            color: #03C03C;
        }
        
        .announcements-card .card-link {
            background-color: #03C03C;
            color: white;
        }
        
        .announcements-card .card-link:hover {
            background-color: #029e3b;
        }
        
        .inbox-card .card-icon-container {
            background-color: rgba(23, 162, 184, 0.1);
        }
        
        .inbox-card .card-icon {
            color: #17a2b8;
        }
        
        .inbox-card .card-link {
            background-color: #17a2b8;
            color: white;
        }
        
        .inbox-card .card-link:hover {
            background-color: #138496;
        }
        
        .events-card .card-icon-container {
            background-color: rgba(220, 53, 69, 0.1);
        }
        
        .events-card .card-icon {
            color: #dc3545;
        }
        
        .events-card .card-link {
            background-color: #dc3545;
            color: white;
        }
        
        .events-card .card-link:hover {
            background-color: #c82333;
        }
        
        .services-card .card-icon-container {
            background-color: rgba(255, 193, 7, 0.1);
        }
        
        .services-card .card-icon {
            color: #ffc107;
        }
        
        .services-card .card-link {
            background-color: #ffc107;
            color: #212529;
        }
        
        .services-card .card-link:hover {
            background-color: #e0a800;
        }
        
        .locations-card .card-icon-container {
            background-color: rgba(108, 117, 125, 0.1);
        }
        
        .locations-card .card-icon {
            color: #6c757d;
        }
        
        .locations-card .card-link {
            background-color: #6c757d;
            color: white;
        }
        
        .locations-card .card-link:hover {
            background-color: #5a6268;
        }
        
        .users-card .card-icon-container {
            background-color: rgba(111, 66, 193, 0.1);
        }
        
        .users-card .card-icon {
            color: #6f42c1;
        }
        
        .users-card .card-link {
            background-color: #6f42c1;
            color: white;
        }
        
        .users-card .card-link:hover {
            background-color: #5a32a3;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-value {
                font-size: 1.8rem;
            }
            
            .card-icon-container {
                width: 50px;
                height: 50px;
            }
            
            .card-icon {
                font-size: 1.5rem;
            }
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
                    <a class="nav-link" href="announcement.php">
                        <i class="fas fa-bullhorn"></i>
                        <span class="menu-title">Announcement</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="inbox.php">
                        <i class="fa-solid fa-comment"></i>
                        <span class="menu-title">Inbox</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">
                        <i class="fas fa-calendar-check"></i>
                        <span class="menu-title">Events</span>
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
                        <span class="menu-title">Staffs</span>
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
                                <h4 class="mb-0">Admin Dashboard</h4>
                            </div>
                            <div class="col-md-4 text-right">
                                <p class="mb-0"><i class="far fa-calendar-alt mr-2"></i><?php echo date('l, F j, Y'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Cards Section -->
    <div class="row">
        <!-- Announcements Card -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card dashboard-card announcements-card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="card-icon-container">
                            <i class="fas fa-bullhorn card-icon"></i>
                        </div>
                        <h4 class="card-title">Announcements</h4>
                        <h2 class="card-value">
                            <?php 
                            $announcementsCount = fetchData($conn, "SELECT COUNT(*) as count FROM announcements");
                            echo $announcementsCount[0]['count'];
                            ?>
                        </h2>
                        <a href="announcement.php" class="card-link">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inbox Card -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card dashboard-card inbox-card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="card-icon-container">
                            <i class="fas fa-comment card-icon"></i>
                        </div>
                        <h4 class="card-title">Inbox Messages</h4>
                        <h2 class="card-value">
                            <?php 
                            $inboxCount = fetchData($conn, "SELECT COUNT(*) as count FROM messages");
                            echo $inboxCount[0]['count'];
                            ?>
                        </h2>
                        <a href="inbox.php" class="card-link">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Card -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card dashboard-card events-card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="card-icon-container">
                            <i class="fas fa-calendar-check card-icon"></i>
                        </div>
                        <h4 class="card-title">Events</h4>
                        <h2 class="card-value">
                            <?php 
                            $eventsCount = fetchData($conn, "SELECT COUNT(*) as count FROM events");
                            echo $eventsCount[0]['count'];
                            ?>
                        </h2>
                        <a href="events.php" class="card-link">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Card -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card dashboard-card services-card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="card-icon-container">
                            <i class="fa-brands fa-slack card-icon"></i>
                            
                        </div>
                        <h4 class="card-title">Services</h4>
                        <h2 class="card-value">
                            <?php 
                            $servicesCount = fetchData($conn, "SELECT COUNT(*) as count FROM services");
                            echo $servicesCount[0]['count'];
                            ?>
                        </h2>
                        <a href="services.php" class="card-link">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Locations Card -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card dashboard-card locations-card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="card-icon-container">
                            <i class="fas fa-location-dot card-icon"></i>
                        </div>
                        <h4 class="card-title">Locations</h4>
                        <h2 class="card-value">
                            <?php 
                            $locationsCount = fetchData($conn, "SELECT COUNT(*) as count FROM locations");
                            echo $locationsCount[0]['count'];
                            ?>
                        </h2>
                        <a href="location.php" class="card-link">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staffs Card -->
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card dashboard-card users-card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="card-icon-container">
                            <i class="fas fa-user-tie card-icon"></i>
                        </div>
                        <h4 class="card-title">Staffs</h4>
                        <h2 class="card-value">
                            <?php 
                            $staffCount = fetchData($conn, "SELECT COUNT(*) as count FROM users WHERE user_type != 'Member'");
                            echo $staffCount[0]['count'];
                            ?>
                        </h2>
                        <a href="users.php" class="card-link">View All</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../dist/assets/js/off-canvas.js"></script>
    <script src="../dist/assets/js/template.js"></script>
    <script src="../dist/assets/js/settings.js"></script>
    <script src="../dist/assets/js/todolist.js"></script>
    <script src="../dist/assets/js/jquery.cookie.js"></script>
</body>
</html>