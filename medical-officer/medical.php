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
    <title>Medical | Medical Officer</title>
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
            <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/pmpc-logo.png"
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
            .page-item.active .page-link{
                background-color: #00563B !important;
                border-color: #00563B !important ;
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
                    <a class="nav-link" href="medical.php">
                        <i class="fa-solid fa-notes-medical"></i>
                        <span class="menu-title">Medical</span>
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
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="row">
                            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                <!-- <h3 class="font-weight-bold">Welcome</h3>
                 <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6> -->
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
                    // Use SweetAlert to show a success message
                    echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "success",
                        title: "Events Added Successfully",
                        showConfirmButton: false,
                        timer: 1500 // Close after 1.5 seconds
                    });
                });
            </script>';
                }
                if (isset($_GET['success']) && $_GET['success'] == 2) {
                    // Use SweetAlert to show a success message
                    echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            icon: "success",
                            title: "Events Updated Successfully",
                            showConfirmButton: false,
                            timer: 1500 // Close after 1.5 seconds
                        });
                    });
                </script>';
                }
                if (isset($_GET['success']) && $_GET['success'] == 3) {
                    // Use SweetAlert to show a success message
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "success",
                                title: "Events Deleted Successfully",
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

                // Get counts for status cards
                $status_sql = "SELECT 
            SUM(CASE WHEN payment_status = 'In Progress' THEN 1 ELSE 0 END) as in_progress_count,
            SUM(CASE WHEN payment_status = 'Completed' THEN 1 ELSE 0 END) as completed_count
            FROM transactions 
            WHERE service_name IN ('Medical Consultation', 'Laboratory', 'X-RAY', 'Hilot Healom', 'Health Card')";
                $status_result = $conn->query($status_sql);
                $status_counts = $status_result->fetch_assoc();

                // Main query with user information
                $sql = "SELECT 
            t.transaction_id,
            t.user_id,
            t.service_name,
            t.control_number,
            t.amount,
            t.payment_status,
            t.created_at,
            u.first_name,
            u.last_name,
            u.certificate_no,
            u.email
            FROM transactions t
            LEFT JOIN users u ON t.user_id = u.user_id 
            WHERE t.service_name IN ('Medical Consultation', 'Laboratory', 'X-RAY', 'Hilot Healom', 'Health Card')
            AND (
                t.transaction_id LIKE ? 
                OR t.user_id LIKE ?
                OR CONCAT(u.first_name, ' ', u.last_name) LIKE ?
                OR t.service_name LIKE ?
                OR t.payment_status LIKE ?
                OR DATE_FORMAT(t.created_at, '%M %d, %Y') LIKE ?
            )
            ORDER BY t.created_at DESC
            LIMIT ? OFFSET ?";

                $stmt = $conn->prepare($sql);
                $search_param = "%" . $search . "%";
                $stmt->bind_param(
                    "ssssssii",
                    $search_param,
                    $search_param,
                    $search_param,
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
            FROM transactions t
            LEFT JOIN users u ON t.user_id = u.user_id
            WHERE t.service_name IN ('Medical Consultation', 'Laboratory', 'X-RAY', 'Hilot Healom', 'Health Card')
            AND (
                t.transaction_id LIKE ? 
                OR t.user_id LIKE ?
                OR CONCAT(u.first_name, ' ', u.last_name) LIKE ?
                OR t.service_name LIKE ?
                OR t.payment_status LIKE ?
                OR DATE_FORMAT(t.created_at, '%M %d, %Y') LIKE ?
            )";

                $count_stmt = $conn->prepare($count_sql);
                $count_stmt->bind_param(
                    "ssssss",
                    $search_param,
                    $search_param,
                    $search_param,
                    $search_param,
                    $search_param,
                    $search_param
                );
                $count_stmt->execute();
                $count_result = $count_stmt->get_result();
                $total_rows = $count_result->fetch_assoc()['total'];
                $total_pages = ceil($total_rows / $limit);

                // Close statements
                $stmt->close();
                $count_stmt->close();
                $conn->close();
                ?>

                <div class="row">
                    <!-- Status Cards -->
                    <div class="col-md-6 mb-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title">In Progress Transactions</h5>
                                <h2 class="card-text"><?php echo $status_counts['in_progress_count']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Completed Transactions</h5>
                                <h2 class="card-text"><?php echo $status_counts['completed_count']; ?></h2>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p class="card-title mb-0">Transactions</p>
                                </div>
                                <div class="mb-3">
                                    <form method="GET" action="" class="form-inline" id="searchForm">
                                        <div class="input-group mb-2 mr-sm-2">
                                            <input type="text" name="search" id="searchInput" class="form-control"
                                                value="<?php echo htmlspecialchars($search); ?>"
                                                placeholder="Search Transactions">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" id="clearButton"
                                                    style="padding:10px;">&times;</button>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mb-2 mr-3"
                                            style="background:#03C03C;"><i
                                                class="fa-solid fa-magnifying-glass"></i></button>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Transaction ID</th>
                                                <th>Member ID</th>
                                                <th>Member Name</th>
                                                <th>Service</th>
                                                <th>Amount</th>
                                                <th>Payment Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                                        <td>
                                                            <?php echo htmlspecialchars($row['payment_status']); ?>
                                                        </td>
                                                        <td><?php echo date('F d, Y h:i A', strtotime($row['created_at'])); ?></td>
                                                        <td>
                                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewApplicationModal<?php echo $row['transaction_id']; ?>">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editApplicationModal<?php echo $row['transaction_id']; ?>">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                        <!--<a href="generate_transaction_pdf.php?transaction_id=<?php echo $row['transaction_id']; ?>" class="btn btn-success btn-sm">
                                                            <i class="fa-solid fa-file-pdf"></i>
                                                        </a>-->
                                                    </td>
                                                    </tr>
                                                    <!-- Edit Application Modal -->
                                                <div class="modal fade" id="editApplicationModal<?php echo $row['transaction_id']; ?>" 
                                                    tabindex="-1" aria-labelledby="editApplicationModalLabel<?php echo $row['transaction_id']; ?>" 
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editApplicationModalLabel<?php echo $row['transaction_id']; ?>">Edit Medical Transaction</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="edit_medical.php" method="POST">
                                                                    <input type="hidden" name="transaction_id" value="<?php echo $row['transaction_id']; ?>">
                                                                    <div class="form-group">
                                                                        <label for="amount">Payment
                                                                            </label>
                                                                        <input type="number" class="form-control"
                                                                            name="amount"
                                                                                        value="<?php echo $row['amount']; ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="amount">Control Number
                                                                            </label>
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
                                                <div class="modal fade" id="viewApplicationModal<?php echo $row['transaction_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewApplicationModalLabel<?php echo $row['transaction_id']; ?>" aria-hidden="true">
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
                                                                        <h6 class="text-primary mb-3">Personal Information</h6>
                                                                        <div class="form-group">
                                                                            <label>OR No.</label>
                                                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['control_number']); ?>" readonly>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Certificate No.</label>
                                                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['certificate_no']); ?>" readonly>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>First Name</label>
                                                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['first_name']); ?>" readonly>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Last Name</label>
                                                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['last_name']); ?>" readonly>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Email</label>
                                                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Right Column: Membership Details -->
                                                                    <div class="col-md-6">
                                                                        <h6 class="text-primary mb-3">Membership Details</h6>
                                                                        <div class="form-group">
                                                                            <label>Service Name</label>
                                                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['service_name']); ?>" readonly>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Type of Payment</label>
                                                                            <input type="text" class="form-control" value="₱<?php echo number_format((float)$row['amount'], 2); ?>" readonly>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Payment Status</label>
                                                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['payment_status']); ?>" readonly>
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
                                                                                    <input type="text" class="form-control font-weight-bold" value="₱<?php echo htmlspecialchars($row['amount']); ?>" readonly>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6">No transactions found</td>
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

                <style>
                    .custom-file-input-wrapper {
                        position: relative;
                        display: flex;
                        align-items: center;
                    }

                    .current-image {
                        max-width: 100px;
                        max-height: 100px;
                        margin-left: 10px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        object-fit: cover;
                    }
                </style>
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
                searchForm.submit(); // Submit the form to reload all records
            });
        });
        $(document).ready(function () {
            // Disable past dates
            var today = new Date().toISOString().split('T')[0];
            $('#eventsDate').attr('min', today);


        });
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('currentImagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
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
    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->
</body>

</html>