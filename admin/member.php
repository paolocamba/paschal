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
    <title>Members | Admin</title>
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
            .navbar {
                        padding-top: 0 !important;
                        margin-top: 0 !important;
                    }

        .table-responsive {
            overflow-x: auto; /* Enables horizontal scrolling */
            position: relative; /* Needed for sticky positioning */
        }

        th:last-child, td:last-child { 
            position: sticky;
            right: 0;
            background: white; /* Keeps background color when scrolling */
            z-index: 2; /* Ensures it stays above other columns */
        }

        th:last-child {
            z-index: 3; /* Keeps header on top */
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
                        title: "Members Info Updated Successfully",
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
            $search = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '';
            $page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            try {
                // Modified query to correctly calculate totals
                    $sql = "SELECT 
                    u.id, 
                    u.user_id,
                    u.first_name, 
                    u.last_name, 
                    u.middle_name, 
                    u.email, 
                    u.birthday, 
                    u.gender, 
                    u.age, 
                    u.mobile, 
                    u.street, 
                    u.barangay, 
                    u.municipality, 
                    u.province, 
                    u.membership_type,
                    u.membership_status, 
                    u.tin_number, 
                    u.tin_id_image, 
                    u.certificate_no,
                    COALESCE(SUM(CASE 
                        WHEN a.description = 'Savings Deposit' THEN a.savings 
                        ELSE 0 
                    END), 0) as savings,
                    COALESCE(SUM(CASE 
                        WHEN a.description = 'Share Capital Deposit' THEN a.share_capital 
                        ELSE 0 
                    END), 0) as share_capital,
                    COALESCE(MAX(CASE 
                        WHEN a.description = 'Membership Payment' THEN a.membership_fee 
                        ELSE 0 
                    END), 0) as membership_fee,
                    COALESCE(MAX(CASE 
                        WHEN a.description = 'Membership Payment' THEN a.insurance 
                        ELSE 0 
                    END), 0) as insurance,
                    COALESCE(SUM(a.total_amount), 0) as total_amount
                FROM users u
                LEFT JOIN appointments a ON u.user_id = a.user_id AND a.status = 'Approved'
                WHERE (
                    u.first_name LIKE ? OR 
                    u.last_name LIKE ? OR 
                    u.middle_name LIKE ? OR
                    u.email LIKE ? OR 
                    u.mobile LIKE ? OR
                    u.street LIKE ? OR
                    u.barangay LIKE ? OR
                    u.municipality LIKE ? OR
                    u.province LIKE ? OR
                    u.membership_type LIKE ? OR
                    u.tin_number LIKE ? OR
                    u.certificate_no LIKE ? OR
                    CONVERT(a.savings, CHAR) LIKE ? OR
                    CONVERT(a.share_capital, CHAR) LIKE ?
                ) 
                AND u.user_type = 'Member'
                GROUP BY 
                    u.id, 
                    u.user_id,
                    u.first_name, 
                    u.last_name, 
                    u.middle_name, 
                    u.email, 
                    u.birthday, 
                    u.gender, 
                    u.age, 
                    u.mobile, 
                    u.street, 
                    u.barangay, 
                    u.municipality, 
                    u.province, 
                    u.membership_type,
                    u.membership_status, 
                    u.tin_number, 
                    u.tin_id_image, 
                    u.certificate_no
                LIMIT ? OFFSET ?";

                $stmt = $conn->prepare($sql);
                $search_param = "%" . $search . "%";
                
                // Bind all search parameters
                $stmt->bind_param(
                    "ssssssssssssssii",
                    $search_param, // first_name
                    $search_param, // last_name
                    $search_param, // middle_name
                    $search_param, // email
                    $search_param, // mobile
                    $search_param, // street
                    $search_param, // barangay
                    $search_param, // municipality
                    $search_param, // province
                    $search_param, // membership_type
                    $search_param, // tin_number
                    $search_param, // certificate_no
                    $search_param, // savings amount
                    $search_param, // share_capital amount
                    $limit,
                    $offset
                );
                
                $stmt->execute();
                $result = $stmt->get_result();

                $count_sql = "SELECT COUNT(DISTINCT u.id) as total 
                FROM users u
                LEFT JOIN appointments a ON u.user_id = a.user_id
                WHERE (
                    u.first_name LIKE ? OR 
                    u.last_name LIKE ? OR 
                    u.middle_name LIKE ? OR
                    u.email LIKE ? OR 
                    u.mobile LIKE ? OR
                    u.street LIKE ? OR
                    u.barangay LIKE ? OR
                    u.municipality LIKE ? OR
                    u.province LIKE ? OR
                    u.membership_type LIKE ? OR
                    u.tin_number LIKE ? OR
                    u.certificate_no LIKE ? OR
                    CONVERT(a.savings, CHAR) LIKE ? OR
                    CONVERT(a.share_capital, CHAR) LIKE ?
                ) 
                AND u.user_type = 'Member'";

                $count_stmt = $conn->prepare($count_sql);
                $count_stmt->bind_param(
                    "ssssssssssssss",
                    $search_param, // first_name
                    $search_param, // last_name
                    $search_param, // middle_name
                    $search_param, // email
                    $search_param, // mobile
                    $search_param, // street
                    $search_param, // barangay
                    $search_param, // municipality
                    $search_param, // province
                    $search_param, // membership_type
                    $search_param, // tin_number
                    $search_param, // certificate_no
                    $search_param, // savings amount
                    $search_param  // share_capital amount
                );
                
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
                                    <p class="card-title mb-0">Members</p>
                                    <div class="ml-auto">
                                        <button class="btn btn-primary mb-3" style="background-color: #03C03C;" onclick="window.location.href='generate_allmem_pdf.php'">
                                            Generate Members Data
                                        </button>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <form method="GET" action="" class="form-inline" id="searchForm">
                                        <div class="input-group mb-2 mr-sm-2">
                                            <input type="text" name="search" id="searchInput" class="form-control"
                                                value="<?php echo htmlspecialchars($search); ?>"
                                                placeholder="Search Members">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" id="clearButton"
                                                    style="padding:10px;">&times;</button>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mb-2 mr-3"><i
                                                class="fa-solid fa-magnifying-glass"></i></button>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless">
                                        <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Middle Name</th>
                                                <th>Certificate No.</th>
                                                <th>Email</th>
                                                <th>Birthday</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Mobile</th>
                                                <th>Street</th>
                                                <th>Barangay</th>
                                                <th>Municipality</th>
                                                <th>Province</th>
                                                <th>Membership Type</th>
                                                <th>Membership Status</th>
                                                <th>Savings</th>
                                                <th>Share Capital</th>
                                                <th>Membership Fee</th>
                                                <th>Insurance</th>
                                                <th>Total Amount</th>
                                                <th>TIN Number</th>
                                                <th>TIN ID</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['middle_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['certificate_no']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['birthday']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['street']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['barangay']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['municipality']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['province']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['membership_type']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['membership_status']); ?></td>
                                                        <td>₱<?php echo number_format(htmlspecialchars($row['savings']), 2); ?></td>
                                                        <td>₱<?php echo number_format(htmlspecialchars($row['share_capital']), 2); ?></td>
                                                        <td>₱<?php echo number_format(htmlspecialchars($row['membership_fee']), 2); ?></td>
                                                        <td>₱<?php echo number_format(htmlspecialchars($row['insurance']), 2); ?></td>
                                                        <td>₱<?php echo number_format(htmlspecialchars($row['total_amount']), 2); ?></td>
                                                        <td><?php echo htmlspecialchars($row['tin_number']); ?></td>
                                                        <td>
                                                            <?php if (!empty($row['tin_id_image'])): ?>
                                                                <img src="../dist/assets/images/tin_id/<?php echo htmlspecialchars($row['tin_id_image']); ?>" alt="TIN ID" style="max-width: 100px; max-height: 100px;">
                                                            <?php else: ?>
                                                                No Image
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <!-- View Button for the View Modal -->
                                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                                data-target="#viewMemberModal<?php echo $row['id']; ?>">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>
                                                            <!-- Edit Button for the Edit Modal -->
                                                            <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                                data-target="#editMemberModal<?php echo $row['id']; ?>">
                                                                <i class="fa-solid fa-edit"></i>
                                                            </button>
                                                            <!-- PDF Generation Button -->
                                                            <a href="generate_members_pdf.php?id=<?php echo $row['id']; ?>" 
                                                            class="btn btn-success btn-sm">
                                                                <i class="fa-solid fa-file-pdf"></i>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <!-- View Member Modal (Updated with TIN ID Image) -->
                                                    <div class="modal fade" id="viewMemberModal<?php echo $row['id']; ?>" 
                                                        tabindex="-1" aria-labelledby="viewMemberModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="viewMemberModalLabel">View Member Details</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="container-fluid">
                                                                        <div class="row">
                                                                            <!-- Member Details Column -->
                                                                            <div class="col-md-8">
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label for="first_name">First Name</label>
                                                                                            <input type="text" class="form-control" name="first_name"
                                                                                                value="<?php echo $row['first_name']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="last_name">Last Name</label>
                                                                                            <input type="text" class="form-control" name="last_name"
                                                                                                value="<?php echo $row['last_name']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="middle_name">Middle Name</label>
                                                                                            <input type="text" class="form-control" name="middle_name"
                                                                                                value="<?php echo $row['middle_name']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="email">Email</label>
                                                                                            <input type="email" class="form-control" name="email"
                                                                                                value="<?php echo $row['email']; ?>" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label for="birthday">Birthday</label>
                                                                                            <input type="date" class="form-control" name="birthday"
                                                                                                value="<?php echo $row['birthday']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="gender">Gender</label>
                                                                                            <input type="text" class="form-control" name="gender"
                                                                                                value="<?php echo $row['gender']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="age">Age</label>
                                                                                            <input type="number" class="form-control" name="age"
                                                                                                value="<?php echo $row['age']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="mobile">Mobile</label>
                                                                                            <input type="text" class="form-control" name="mobile"
                                                                                                value="<?php echo $row['mobile']; ?>" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label for="street">Street</label>
                                                                                            <input type="text" class="form-control" name="street"
                                                                                                value="<?php echo $row['street']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="barangay">Barangay</label>
                                                                                            <input type="text" class="form-control" name="barangay"
                                                                                                value="<?php echo $row['barangay']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="municipality">Municipality</label>
                                                                                            <input type="text" class="form-control" name="municipality"
                                                                                                value="<?php echo $row['municipality']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="province">Province</label>
                                                                                            <input type="text" class="form-control" name="province"
                                                                                                value="<?php echo $row['province']; ?>" readonly>
                                                                                        </div>
                                                                                       
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label for="membership_type">Membership Type</label>
                                                                                            <input type="text" class="form-control" name="membership_type"
                                                                                                value="<?php echo $row['membership_type']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="savings">Savings</label>
                                                                                            <input type="number" class="form-control" name="savings"
                                                                                                value="<?php echo $row['savings']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="share_capital">Share Capital</label>
                                                                                            <input type="number" class="form-control" name="share_capital"
                                                                                                value="<?php echo $row['share_capital']; ?>" readonly>
                                                                                        </div>
                                                                                        
                                                                                        <div class="form-group">
                                                                                            <label for="tin_number">TIN Number</label>
                                                                                            <input type="text" class="form-control" name="tin_number"
                                                                                                value="<?php echo $row['tin_number']; ?>" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <!-- Add these fields to the existing modal -->
                                                                                        <div class="form-group">
                                                                                            <label for="membership_status">Membership Status</label>
                                                                                            <input type="text" class="form-control" name="membership_status"
                                                                                                value="<?php echo $row['membership_status']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="membership_fee">Membership Fee</label>
                                                                                            <input type="text" class="form-control" name="membership_fee"
                                                                                                value="₱<?php echo $row['membership_fee']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="insurance">Insurance</label>
                                                                                            <input type="text" class="form-control" name="insurance"
                                                                                                value="₱<?php echo $row['insurance']; ?>" readonly>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="total_amount">Total Amount</label>
                                                                                            <input type="text" class="form-control" name="total_amount"
                                                                                                value="₱<?php echo $row['total_amount']; ?>" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <!-- TIN ID Image Column -->
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label for="tin_id_image">TIN ID Image</label>
                                                                                    <div class="tin-id-container" style="
                                                                                        border: 2px solid #ddd;
                                                                                        border-radius: 8px;
                                                                                        overflow: hidden;
                                                                                        max-height: 400px;
                                                                                        display: flex;
                                                                                        justify-content: center;
                                                                                        align-items: center;
                                                                                        background-color: #f8f9fa;
                                                                                    ">
                                                                                        <?php if (!empty($row['tin_id_image'])): ?>
                                                                                            <img src="../dist/assets/images/tin_id/<?php echo htmlspecialchars($row['tin_id_image']); ?>" 
                                                                                                alt="TIN ID Image" 
                                                                                                class="img-fluid" 
                                                                                                style="
                                                                                                    max-width: 100%;
                                                                                                    max-height: 400px;
                                                                                                    object-fit: contain;
                                                                                                    padding: 10px;
                                                                                                ">
                                                                                        <?php else: ?>
                                                                                            <p class="text-muted text-center p-3">No TIN ID image uploaded</p>
                                                                                        <?php endif; ?>
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


                                                    <!-- Edit Member Modal (Editable) -->
                                                    <div class="modal fade" id="editMemberModal<?php echo $row['id']; ?>"
                                                        tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editMemberModalLabel">Edit
                                                                        Member Details</h5>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="update_member.php" method="POST">
                                                                        <input type="hidden" name="id"
                                                                            value="<?php echo $row['id']; ?>">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="first_name">First Name</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="first_name"
                                                                                        value="<?php echo $row['first_name']; ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="last_name">Last Name</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="last_name"
                                                                                        value="<?php echo $row['last_name']; ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="middle_name">Middle Name</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="middle_name"
                                                                                        value="<?php echo $row['middle_name']; ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="email">Email</label>
                                                                                    <input type="email" class="form-control"
                                                                                        name="email"
                                                                                        value="<?php echo $row['email']; ?>"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="birthday">Birthday</label>
                                                                                    <input type="date" class="form-control"
                                                                                        name="birthday"
                                                                                        value="<?php echo $row['birthday']; ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="gender">Gender</label>
                                                                                    <select class="form-control" name="gender"
                                                                                        required>
                                                                                        <option value="male" <?php echo ($row['gender'] == 'male') ? 'selected' : ''; ?>>male</option>
                                                                                        <option value="female" <?php echo ($row['gender'] == 'female') ? 'selected' : ''; ?>>female</option>
                                                                                        <option value="other" <?php echo ($row['gender'] == 'other') ? 'selected' : ''; ?>>other</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="age">Age</label>
                                                                                    <input type="number" class="form-control"
                                                                                        name="age"
                                                                                        value="<?php echo $row['age']; ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="mobile">Mobile</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="mobile"
                                                                                        value="<?php echo $row['mobile']; ?>"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="street">Street</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="street"
                                                                                        value="<?php echo $row['street']; ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="barangay">Barangay</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="barangay"
                                                                                        value="<?php echo $row['barangay']; ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="municipality">Municipality</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="municipality"
                                                                                        value="<?php echo $row['municipality']; ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="province">Province</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="province"
                                                                                        value="<?php echo $row['province']; ?>"
                                                                                        required>
                                                                                </div>
                                                                                
                                                                                
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="membership_type">Membership
                                                                                        Type</label>
                                                                                    <select class="form-control"
                                                                                        name="membership_type" required>
                                                                                        <option value="Regular" <?php echo ($row['membership_type'] == 'Regular') ? 'selected' : ''; ?>>Regular
                                                                                        </option>
                                                                                        <option value="Associate" <?php echo ($row['membership_type'] == 'Associate') ? 'selected' : ''; ?>>Associate
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                                
                                                                                <div class="form-group">
                                                                                    <label for="tin_number">TIN Number</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="tin_number"
                                                                                        value="<?php echo $row['tin_number']; ?>"
                                                                                        required>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="certificate_no">Certificate Number</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="certificate_no"
                                                                                        value="<?php echo $row['certificate_no']; ?>"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-primary">Save
                                                                                Changes</button>
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
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
                                                <span aria-hidden="true">&laquo;</span>
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
                                                <span aria-hidden="true">&raquo;</span>
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
        $(document).ready(function () {
            // Disable past dates
            var today = new Date().toISOString().split('T')[0];
            $('#announcementDate').attr('min', today);

            // Capture the day of the selected date
            $('#announcementDate').change(function () {
                var selectedDate = $(this).val();
                var dateObj = new Date(selectedDate);
                var options = { weekday: 'long' }; // Get full weekday name
                var dayOfWeek = dateObj.toLocaleDateString('en-PH', options);
                $('#announcementDay').val(dayOfWeek); // Set the day value in the hidden input
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