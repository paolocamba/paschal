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
    <title>Transaction | Admin</title>
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
            <a class="navbar-brand brand-logo me-5" href="index.php"><img src="../dist/assets/images/logo.png"
                    class="me-2" alt="logo" /></a>
            <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/logo.png"
                    alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="icon-menu"></span>
            </button>

            <ul class="navbar-nav mr-lg-2">
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
            .table-responsive {
                overflow-x: auto;
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
            }
            
            .sort-btn {
                cursor: pointer;
                transition: all 0.3s;
            }
            
            .sort-btn:hover {
                background-color: #f8f9fa;
            }
            
            .sort-btn.active {
                color: #00563B;
                font-weight: bold;
            }

            .receipt-section {
                margin-bottom: 15px;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #f9f9f9;
            }

            .receipt-item {
                margin-bottom: 8px;
                font-size: 14px;
            }

            .receipt-item strong {
                font-weight: bold;
            }

            .text-primary {
                color: #007bff;
            }

            .font-weight-bold {
                font-weight: 700;
            }

            .modal-footer {
                text-align: center;
            }

            .card-body {
                padding: 15px;
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
                <?php
                // Check if the "success" parameter is set in the URL
                if (isset($_GET['success']) && $_GET['success'] == 1) {
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "success",
                                title: "Transactions Updated Successfully",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        });
                    </script>';
                }
                ?>

                <?php
                include '../connection/config.php';

                $search = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '';
                $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
                $page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
                $limit = 10;
                $offset = ($page - 1) * $limit;

                // Determine sort order
                $order_by = "ORDER BY t.created_at DESC"; // Default: newest first
                if ($sort === 'oldest') {
                    $order_by = "ORDER BY t.created_at ASC";
                } elseif ($sort === 'amount_high') {
                    $order_by = "ORDER BY t.amount DESC";
                } elseif ($sort === 'amount_low') {
                    $order_by = "ORDER BY t.amount ASC";
                }

                // Add query to fetch services BEFORE using it in the modal
                $services_query = "SELECT id, name FROM services";
                $services_result = $conn->query($services_query);

                try {
                    $sql = "SELECT
                                u.id,
                                u.first_name,
                                u.last_name,
                                u.email,
                                u.user_id,
                                u.certificate_no,
                                t.transaction_id,
                                t.amount,
                                t.control_number,
                                t.service_name,
                                s.name as services_name,
                                t.created_at,
                                t.updated_at,
                                t.payment_status   
                            FROM transactions t
                            JOIN users u ON t.user_id = u.user_id
                            LEFT JOIN services s ON s.name = t.service_name
                            WHERE (u.first_name LIKE ?
                                OR u.last_name LIKE ?
                                OR u.email LIKE ?
                                OR u.certificate_no LIKE ?)
                            $order_by
                            LIMIT ? OFFSET ?";

                    $stmt = $conn->prepare($sql);
                    $search_param = "%" . $search . "%";
                    $stmt->bind_param("ssssii", $search_param, $search_param, $search_param, $search_param, $limit, $offset);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Add count query for pagination
                    $count_sql = "SELECT COUNT(*) as total 
                                FROM transactions t
                                JOIN users u ON t.user_id = u.user_id
                                LEFT JOIN services s ON s.name = t.service_name
                                WHERE (u.first_name LIKE ?
                                    OR u.last_name LIKE ?
                                    OR u.email LIKE ?
                                    OR u.certificate_no LIKE?)";
                    $count_stmt = $conn->prepare($count_sql);
                    $count_stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
                    $count_stmt->execute();
                    $count_result = $count_stmt->get_result();
                    $total_rows = $count_result->fetch_assoc()['total'];
                    $total_pages = ceil($total_rows / $limit);

                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                    exit;
                }
                ?>

                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <p class="card-title mb-0">Transaction</p>
                                    <div class="ml-auto">
                                        <button class="btn btn-primary mb-3" style="background-color: #03C03C;" onclick="window.location.href='generate_alltrans_pdf.php'">
                                            Generate Transaction Data
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <form method="GET" action="" class="form-inline" id="searchForm">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="search" id="searchInput" class="form-control"
                                                    value="<?php echo htmlspecialchars($search); ?>"
                                                    placeholder="Search Transaction">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" id="clearButton"
                                                        style="padding:10px;">&times;</button>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary mb-2 mr-3"
                                                style="background:#03C03C;">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-6 text-right">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-sort"></i> Sort By
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item <?php echo $sort === 'newest' ? 'active' : ''; ?>" 
                                            href="?search=<?php echo urlencode($search); ?>&sort=newest">
                                                <i class="fas fa-sort-amount-down mr-2"></i> Newest
                                            </a>
                                            <a class="dropdown-item <?php echo $sort === 'oldest' ? 'active' : ''; ?>" 
                                            href="?search=<?php echo urlencode($search); ?>&sort=oldest">
                                                <i class="fas fa-sort-amount-up mr-2"></i> Oldest
                                            </a>
                                            <a class="dropdown-item <?php echo $sort === 'amount_high' ? 'active' : ''; ?>" 
                                            href="?search=<?php echo urlencode($search); ?>&sort=amount_high">
                                                <i class="fas fa-sort-numeric-down mr-2"></i> Amount (High to Low)
                                            </a>
                                            <a class="dropdown-item <?php echo $sort === 'amount_low' ? 'active' : ''; ?>" 
                                            href="?search=<?php echo urlencode($search); ?>&sort=amount_low">
                                                <i class="fas fa-sort-numeric-up mr-2"></i> Amount (Low to High)
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="table-responsive">
                                <table class="table table-striped table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Date & Time</th>
                                            <th>OR No.</th>
                                            <th>Name</th>
                                            <th>Service</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Certificate No.</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td>
                                                    <?php 
                                                    $date = new DateTime($row['created_at']);
                                                    echo $date->format('M d, Y h:i A'); 
                                                    ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['control_number'] ?? ''); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($row['first_name'] ?? ''); ?> 
                                                    <?php echo htmlspecialchars($row['last_name'] ?? ''); ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($row['email'] ?? ''); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['services_name'] ?? ''); ?></td>
                                                <td>₱<?php echo number_format((float)($row['amount'] ?? 0), 2); ?></td>
                                                <td>
                                                <span class="badge badge-pill 
                                                    <?php echo $row['payment_status'] === 'Completed' ? 'badge-success text-white' : 
                                                        ($row['payment_status'] === 'In Progress' ? 'badge-warning text-dark' : 'badge-secondary text-white'); ?>"
                                                    style="font-size: 0.9rem; padding: 0.5em 0.75em; font-weight: 500; letter-spacing: 0.5px;">
                                                    <?php echo htmlspecialchars($row['payment_status']); ?>
                                                </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['certificate_no'] ?? ''); ?></td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewApplicationModal<?php echo $row['transaction_id']; ?>">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editApplicationModal<?php echo $row['transaction_id']; ?>">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <a href="generate_transaction_pdf.php?transaction_id=<?php echo $row['transaction_id']; ?>" class="btn btn-success btn-sm">
                                                        <i class="fa-solid fa-file-pdf"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                                <!-- Edit Application Modal -->
                                                <div class="modal fade" id="editApplicationModal<?php echo $row['transaction_id']; ?>" 
                                                    tabindex="-1" aria-labelledby="editApplicationModalLabel<?php echo $row['transaction_id']; ?>" 
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editApplicationModalLabel<?php echo $row['transaction_id']; ?>">Edit Transaction</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="edit_transaction.php" method="POST">
                                                                    <input type="hidden" name="user_id" value="<?php echo $row['transaction_id']; ?>">

                                                                        <!-- Add this hidden field for loan_id -->
                                                                    <?php if (strpos($row['service_name'], 'Loan') !== false): ?>
                                                                        <?php 
                                                                        // Get loan_id from loan_payments if it exists
                                                                        $loan_id_query = "SELECT LoanID FROM loan_payments WHERE transaction_id = ?";
                                                                        $stmt = $conn->prepare($loan_id_query);
                                                                        $stmt->bind_param("i", $row['transaction_id']);
                                                                        $stmt->execute();
                                                                        $loan_id_result = $stmt->get_result();
                                                                        $loan_id = $loan_id_result->num_rows > 0 ? $loan_id_result->fetch_assoc()['LoanID'] : 0;
                                                                        ?>
                                                                        <input type="hidden" name="loan_id" value="<?php echo $loan_id; ?>">
                                                                    <?php endif; ?>

                                                                    <div class="mb-3">
                                                                        <label for="service<?php echo $row['transaction_id']; ?>" class="form-label">Service</label>
                                                                        <select class="form-control" id="service<?php echo $row['transaction_id']; ?>" name="service" required>
                                                                            <option value="">Select service</option>
                                                                            <?php
                                                                            mysqli_data_seek($services_result, 0);
                                                                            while ($service = $services_result->fetch_assoc()): ?>
                                                                                <option value="<?php echo $service['id']; ?>"
                                                                                    <?php echo ($row['service_name'] == $service['name']) ? 'selected' : ''; ?>>
                                                                                    <?php echo htmlspecialchars($service['name']); ?>
                                                                                </option>
                                                                            <?php endwhile; ?>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="amount">Payment</label>
                                                                        <input type="number" class="form-control"
                                                                            name="amount"
                                                                                        value="<?php echo $row['amount']; ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="amount">Control Number</label>
                                                                        <input type="number" class="form-control"
                                                                            name="control_number"
                                                                                        value="<?php echo $row['control_number']; ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="payment_status<?php echo $row['transaction_id']; ?>" class="form-label">Payment Status</label>
                                                                        <select class="form-control" id="payment_status<?php echo $row['transaction_id']; ?>" name="payment_status" required>
                                                                            <option value="In Progress" <?php echo ($row['payment_status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                                            <option value="Completed" <?php echo ($row['payment_status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- View Modal -->
                                                <div class="modal fade" id="viewApplicationModal<?php echo $row['transaction_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewApplicationModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-info text-white">
                                                                <h5 class="modal-title" id="viewApplicationModalLabel<?php echo $row['transaction_id']; ?>">
                                                                    Transaction Details - <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                                                </h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            <div class="row">
                                                                <!-- Left Column: Personal Information -->
                                                                <div class="col-md-6">
                                                                    <div class="receipt-section">
                                                                        <h5 class="text-center mb-3 text-primary">Receipt</h5>
                                                                        <div class="receipt-item">
                                                                            <strong>OR No.:</strong> <span><?php echo htmlspecialchars($row['control_number']); ?></span>
                                                                        </div>
                                                                        <div class="receipt-item">
                                                                            <strong>Certificate No.:</strong> <span><?php echo htmlspecialchars($row['certificate_no']); ?></span>
                                                                        </div>
                                                                        <div class="receipt-item">
                                                                            <strong>First Name:</strong> <span><?php echo htmlspecialchars($row['first_name']); ?></span>
                                                                        </div>
                                                                        <div class="receipt-item">
                                                                            <strong>Last Name:</strong> <span><?php echo htmlspecialchars($row['last_name']); ?></span>
                                                                        </div>
                                                                        <div class="receipt-item">
                                                                            <strong>Email:</strong> <span><?php echo htmlspecialchars($row['email']); ?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Right Column: Membership Details -->
                                                                <div class="col-md-6">
                                                                    <div class="receipt-section">
                                                                        <div class="receipt-item">
                                                                            <strong>Service Name:</strong> <span><?php echo htmlspecialchars($row['services_name']); ?></span>
                                                                        </div>
                                                                        <div class="receipt-item">
                                                                            <strong>Payment Status:</strong> <span><?php echo htmlspecialchars($row['payment_status']); ?></span>
                                                                        </div>
                                                                        <div class="receipt-item">
                                                                            <strong>Payment Completed:</strong> <span><?php echo date('g:i A, F d, Y', strtotime($row['updated_at'])); ?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Total Summary Row -->
                                                            <div class="row mt-4">
                                                                <div class="col-12">
                                                                    <div class="card bg-light">
                                                                        <div class="card-body">
                                                                            <h6 class="text-primary mb-3">Total Summary</h6>
                                                                            <div class="form-group mb-0">
                                                                                <label><strong>Total Amount</strong></label>
                                                                                <input type="text" class="form-control font-weight-bold" value="₱<?php echo number_format((float)$row['amount'], 2); ?>" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Modal Footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>

                                            <?php endwhile; ?>
                                            <?php if ($result->num_rows == 0): ?>
                                                <tr>
                                                    <td colspan="10">No transactions found</td>
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
                                                href="?search=<?php echo htmlspecialchars($search); ?>&sort=<?php echo $sort; ?>&page=<?php echo $page - 1; ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo; </span>
                                            </a>
                                        </li>
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                <a class="page-link"
                                                    href="?search=<?php echo htmlspecialchars($search); ?>&sort=<?php echo $sort; ?>&page=<?php echo $i; ?>"
                                                    style="background:color:#00563B !important;"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?search=<?php echo htmlspecialchars($search); ?>&sort=<?php echo $sort; ?>&page=<?php echo $page + 1; ?>"
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

                <br><br><br><br><br><br><br>

                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
                            2024-2050. <a href="" target="_blank">Paschal</a>. All rights reserved.</span>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var searchForm = document.getElementById('searchForm');
            var searchInput = document.getElementById('searchInput');
            var clearButton = document.getElementById('clearButton');

            clearButton.addEventListener('click', function () {
                searchInput.value = '';
                searchForm.submit();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to all edit buttons
            document.querySelectorAll('[data-toggle="modal"]').forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-target');
                    const modal = document.querySelector(modalId);
                    if (modal) {
                        const serviceSelect = modal.querySelector('#service');
                        updateFeeInputs(serviceSelect, modalId);
                    }
                });
            });

            // Add change event listeners to all service selects
            document.querySelectorAll('.modal select[name="service"]').forEach(select => {
                select.addEventListener('change', function() {
                    const modalId = '#' + this.closest('.modal').id;
                    updateFeeInputs(this, modalId);
                });
            });
        });

        function updateFeeInputs(selectElement, modalId) {
            const modal = document.querySelector(modalId);
            if (!modal) return;

            // Hide all fee containers in this specific modal
            const feeContainers = modal.querySelectorAll('[id$="-container"]');
            feeContainers.forEach(container => {
                container.style.display = 'none';
            });

            // Get the selected service name
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            if (!selectedOption) return;
            
            const serviceName = selectedOption.text;

            // Show the appropriate fee input based on the selected service
            const containerMap = {
                'Membership Payment': 'membershipfee',
                'Life Insurance': 'lifeinsurance-fee',
                'Regular Loan': 'regular-fee',
                'Collateral Loan': 'collateral-fee',
                'Health Card': 'health-fee',
                'Medical Consultation': 'medical-fee',
                'Laboratory': 'lab-fee',
                'Rice': 'rice-fee',
                'Space Rent': 'space-fee',
                'X-RAY': 'xray-fee',
                'Hilot Healom': 'hilot-fee'
            };

            const containerId = containerMap[serviceName];
            if (containerId) {
                const container = modal.querySelector(`[id^="${containerId}"]`);
                if (container) {
                    container.style.display = 'block';
                }
            }
        }
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