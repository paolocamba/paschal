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
    <title>Share Capital | Admin</title>
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
                        title: "Share Capital Added Successfully",
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
                            title: "Share Capital Updated Successfully",
                            showConfirmButton: false,
                            timer: 1500 // Close after 1.5 seconds
                        });
                    });
                </script>';
                }
             

                ?>
                <!-- Savings Modal -->
                <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="shareModalLabel">New Share Capital Entry</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="add_sharecapital.php" method="POST">
                                    <div class="mb-3">
                                        <label for="memberID" class="form-label">Member Name</label>
                                        <select class="form-control" id="memberID" name="MemberID" required>
                                            <option value="">Select Member</option>
                                            <?php
                                            // Connect to your database
                                            $query = "SELECT user_id, first_name, last_name, email FROM users WHERE user_type = 'Member'";
                                            $result = mysqli_query($conn, $query);
                                            
                                            while($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='" . $row['user_id'] . "'>" . 
                                                    $row['first_name'] . " " . $row['last_name'] . " - " . 
                                                    $row['email'] . 
                                                    "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" step="0.01" class="form-control" id="amount" name="Amount" 
                                            placeholder="Enter amount" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="paymentMethod" class="form-label">Payment Method</label>
                                        <select class="form-control" id="paymentMethod" name="PaymentMethod" required>
                                            <option value="">Select Payment Method</option>
                                            <option value="walkin">Walk-in</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control" id="notes" name="Notes" 
                                                rows="3" placeholder="Enter notes"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control" id="status" name="Status" required>
                                            <option value="In Progress">In Progress</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Disapproved">Disapproved</option>
                                        </select>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save Share Capital</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                    include '../connection/config.php';

                    // Fetch the search query and page number
                    $search = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '';
                    $page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
                    $limit = 10;
                    $offset = ($page - 1) * $limit;

                    try {
                        // Query to fetch savings data with user information
                        $sql = "SELECT sc.*, u.first_name, u.last_name, u.email 
                                FROM share_capital sc
                                JOIN users u ON sc.MemberID = u.user_id
                                WHERE sc.ShareCapitalID LIKE ? 
                                OR u.first_name LIKE ? 
                                OR u.last_name LIKE ?
                                OR sc.Amount LIKE ?
                                OR sc.Status LIKE ?
                                LIMIT ? OFFSET ?";
                        $stmt = $conn->prepare($sql);
                        $search_param = "%" . $search . "%";
                        $stmt->bind_param("sssssii", $search_param, $search_param, $search_param, $search_param, $search_param, $limit, $offset);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Count query for pagination
                        $count_sql = "SELECT COUNT(*) as total 
                                    FROM share_capital sc
                                    JOIN users u ON sc.MemberID = u.user_id
                                    WHERE sc.ShareCapitalID LIKE ? 
                                        OR u.first_name LIKE ? 
                                        OR u.last_name LIKE ?
                                        OR sc.Amount LIKE ?
                                        OR sc.Status LIKE ?";
                        $count_stmt = $conn->prepare($count_sql);
                        $count_stmt->bind_param("sssss", $search_param, $search_param, $search_param, $search_param, $search_param);
                        $count_stmt->execute();
                        $count_result = $count_stmt->get_result();
                        $total_rows = $count_result->fetch_assoc()['total'];
                        $total_pages = ceil($total_rows / $limit);
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                        exit;
                    }
                    ?>

                    <!-- Display Section -->
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                        <p class="card-title mb-0">Share Capital</p>
                                        <div class="ml-auto">
                                            <button class="btn btn-primary mb-3" data-toggle="modal"
                                                data-target="#shareModal">Add Share Capital</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <form method="GET" action="" class="form-inline" id="searchForm">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="search" id="searchInput" class="form-control"
                                                    value="<?php echo htmlspecialchars($search); ?>"
                                                    placeholder="Search Savings">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" id="clearButton"
                                                        style="padding:10px;">&times;</button>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary mb-2 mr-3"
                                                style="background:#03C03C;"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </form>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Member Name</th>
                                                    <th>Amount</th>
                                                    <th>Payment Method</th>
                                                    <th>Notes</th>
                                                    <th>Status</th>
                                                    <th>Transaction Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($result->num_rows > 0): ?>
                                                    <?php while ($row = $result->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                                            <td>₱<?php echo htmlspecialchars($row['Amount']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['PaymentMethod']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['Notes']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['Status']); ?></td>
                                                            <td><?php echo date('F d, Y h:i A', strtotime($row['TransactionDate'])); ?></td>
                                                            <td>
                                                                <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                                    data-target="#editShareCapitalModal<?php echo $row['ShareCapitalID']; ?>">
                                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                                </button>
                                                            </td>
                                                        </tr>

                                                        <!-- Edit Share Capital Modal -->
                                                        <div class="modal fade" id="editShareCapitalModal<?php echo $row['ShareCapitalID']; ?>"
                                                            tabindex="-1" aria-labelledby="editShareCapitalModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="editShareCapitalModalLabel">Edit Share Capital Status</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="edit_sharecapital.php" method="POST">
                                                                            <input type="hidden" name="ShareCapitalID" 
                                                                                value="<?php echo $row['ShareCapitalID']; ?>">
                                                                            <input type="hidden" name="MemberID" 
                                                                                value="<?php echo $row['MemberID']; ?>">
                                                                            
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Member Name</label>
                                                                                <input type="text" class="form-control" 
                                                                                    value="<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>" 
                                                                                    readonly>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label class="form-label">Amount</label>
                                                                                <input type="text" class="form-control" 
                                                                                    value="<?php echo htmlspecialchars($row['Amount']); ?>" 
                                                                                    readonly>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label class="form-label">Payment Method</label>
                                                                                <input type="text" class="form-control" 
                                                                                    value="<?php echo htmlspecialchars($row['PaymentMethod']); ?>" 
                                                                                    readonly>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label class="form-label">Notes</label>
                                                                                <textarea class="form-control" readonly><?php echo htmlspecialchars($row['Notes']); ?></textarea>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label for="status" class="form-label">Status</label>
                                                                                <select class="form-control" id="status" name="Status" required>
                                                                                    <option value="In Progress" <?php echo ($row['Status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                                                    <option value="Approved" <?php echo ($row['Status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                                                                    <option value="Disapproved" <?php echo ($row['Status'] == 'Disapproved') ? 'selected' : ''; ?>>Disapproved</option>
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
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="7">No share capital found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                    <!-- Pagination Section -->
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