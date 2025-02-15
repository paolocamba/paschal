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
$sql = "SELECT username, first_name, last_name, mobile, email, street, barangay, municipality, province, uploadID, membership_type, is_logged_in FROM users WHERE user_id = '$id'";
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
    $membership_type = $row["membership_type"];

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
    $membership_type = "";

}

// Assign the value of $username and $image to $_SESSION variables
$_SESSION['membership_type'] = $row['membership_type'];
$_SESSION['uploadID'] = $uploadID;
$_SESSION['is_logged_in'] = $row['is_logged_in']; // Add this line
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard | Liaison Officer</title>
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
                        <img src="../dist/assets/images/user/<?php echo htmlspecialchars($_SESSION['uploadID']); ?>"
                            alt="profile" />
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
                                <h3 class="font-weight-bold">Welcome
                                    <?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
                                <h3 class="font-weight-bold">
                                    <?php echo htmlspecialchars($_SESSION['membership_type']); ?> <span
                                        class="text-primary"></span></h3>
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
                    <?php
                    $status_sql = "SELECT COUNT(*) as collateral_count FROM collateral_info";
                    $result = mysqli_query($conn, $status_sql);
                    $row = mysqli_fetch_assoc($result);
                    ?>

                    <div class="col-md-6 grid-margin transparent">
                        <div class="row">
                            <div class="col-md-12 mb-4 stretch-card transparent">
                                <div class="card" style="background-color: #03C03C;">
                                    <div class="card-body">
                                        <p class="mb-4 text-white">Total Number of Collateral Assessment</p>
                                        <p class="fs-30 mb-2 text-white"><?php echo $row['collateral_count']; ?></p>
                                    </div>
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
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = 10;
                $offset = ($page - 1) * $limit;

                // Updated SQL query with JOIN statements
                $sql = "SELECT 
                        c.LoanID,
                        c.square_meters,
                        u.first_name,
                        u.last_name,
                        u.user_id,
                        l.DateOfLoan,
                        l.userID
                    FROM collateral_info c
                    INNER JOIN loanapplication l ON c.LoanID = l.LoanID
                    INNER JOIN users u ON l.userID = u.user_id
                    WHERE u.first_name LIKE ? 
                    OR u.last_name LIKE ?
                    OR c.LoanID LIKE ?
                    OR l.DateOfLoan LIKE ?
                    LIMIT ? OFFSET ?";

                $stmt = $conn->prepare($sql);
                $search_param = "%" . $search . "%";
                $stmt->bind_param("ssssii", 
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
                    FROM collateral_info c
                    INNER JOIN loanapplication l ON c.LoanID = l.LoanID
                    INNER JOIN users u ON l.userID = u.user_id
                    WHERE u.first_name LIKE ? 
                    OR u.last_name LIKE ?
                    OR c.LoanID LIKE ?
                    OR l.DateOfLoan LIKE ?";

                $count_stmt = $conn->prepare($count_sql);
                $count_stmt->bind_param("ssss", 
                    $search_param, 
                    $search_param, 
                    $search_param,
                    $search_param
                );
                $count_stmt->execute();
                $count_result = $count_stmt->get_result();
                $total_rows = $count_result->fetch_assoc()['total'];
                $total_pages = ceil($total_rows / $limit);
                ?>

                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p class="card-title mb-0">Collateral List</p>
                                </div>
                                <div class="mb-3">
                                    <form method="GET" action="" class="form-inline" id="searchForm">
                                        <div class="input-group mb-2 mr-sm-2">
                                            <input type="text" name="search" id="searchInput" class="form-control" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Loans">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary" id="clearButton" style="padding:10px;">&times;</button>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mb-2 mr-3" style="background:#03C03C;"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </form>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Loan ID</th>
                                                <th>MemberID</th>
                                                <th>Member Name</th>
                                                <th>Square Meters</th>
                                                <th>Date of Loan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['LoanID']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['userID']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['square_meters']); ?></td>
                                                        <td><?php echo date('F d, Y', strtotime($row['DateOfLoan'])); ?></td>
                                                        <td>
                                                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewModal<?php echo $row['LoanID']; ?>">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                   
                                                    

                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5">No collateral list found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <br>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page - 1; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo; </span>
                                            </a>
                                        </li>
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                <a class="page-link" href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page + 1; ?>" aria-label="Next">
                                                <span aria-hidden="true"> &raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Separate Modal for Each Row - Only shown when view button is clicked -->
                <?php 
                if ($result->num_rows > 0):
                    $result->data_seek(0);
                    while($row = $result->fetch_assoc()): 
                        // Check if validation exists
                        $appraisal_sql = "SELECT * FROM land_appraisal WHERE LoanID = ?";
                        $appraisal_stmt = $conn->prepare($appraisal_sql);
                        $appraisal_stmt->bind_param("i", $row['LoanID']);
                        $appraisal_stmt->execute();
                        $appraisal_data = $appraisal_stmt->get_result()->fetch_assoc();
                ?>
                    <div class="modal fade" id="viewModal<?php echo $row['LoanID']; ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Collateral Assessment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php if ($appraisal_data): ?>
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">Validated Assessment Results</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th width="30%">Square Meters</th>
                                                                <td><?php echo number_format($appraisal_data['square_meters'], 2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Type of Land</th>
                                                                <td><?php echo htmlspecialchars($appraisal_data['type_of_land']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Location</th>
                                                                <td><?php echo htmlspecialchars($appraisal_data['location']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Right of Way</th>
                                                                <td><?php echo htmlspecialchars($appraisal_data['right_of_way']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Hospital</th>
                                                                <td><?php echo htmlspecialchars($appraisal_data['hospital']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Clinic</th>
                                                                <td><?php echo htmlspecialchars($appraisal_data['clinic']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>School</th>
                                                                <td><?php echo htmlspecialchars($appraisal_data['school']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Market</th>
                                                                <td><?php echo htmlspecialchars($appraisal_data['market']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Church</th>
                                                                <td><?php echo htmlspecialchars($appraisal_data['church']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Public Terminal</th>
                                                                <td><?php echo htmlspecialchars($appraisal_data['public_terminal']); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="table-responsive mt-4">
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr>
                                                                <th width="30%">Final Zonal Value</th>
                                                                <td>₱<?php echo number_format($appraisal_data['final_zonal_value'], 2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>EMV Per SQM</th>
                                                                <td>₱<?php echo number_format($appraisal_data['EMV_per_sqm'], 2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Total Value</th>
                                                                <td>₱<?php echo number_format($appraisal_data['total_value'], 2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Loanable Amount</th>
                                                                <td>₱<?php echo number_format($appraisal_data['loanable_amount'], 2); ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <?php if($appraisal_data['image_path']): ?>
                                                    <div class="mt-4">
                                                        <h6>Property Images</h6>
                                                        <img src="<?php echo htmlspecialchars($appraisal_data['image_path']); ?>" class="img-fluid" alt="Property Image">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <?php
                                        $collateral_sql = "SELECT * FROM collateral_info WHERE LoanID = ?";
                                        $collateral_stmt = $conn->prepare($collateral_sql);
                                        $collateral_stmt->bind_param("s", $row['LoanID']);
                                        $collateral_stmt->execute();
                                        $collateral_info = $collateral_stmt->get_result()->fetch_assoc();
                                        
                                        // Get land_title_path from collateral_info
                                        $land_title_path = isset($collateral_info['land_title_path']) ? $collateral_info['land_title_path'] : '';
                                        ?>
                                        <form id="collateralForm<?php echo $row['LoanID']; ?>" class="collateral-form">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">Land Title</h6>
                                                    <div class="title-image" style="text-align: center;">
                                                        <img src="<?php echo htmlspecialchars($land_title_path) ?>" 
                                                            alt="Land Title Image" 
                                                            id="landTitleImage"
                                                            style="margin: 0 auto; max-width: 300px; height: auto;">
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th width="25%">Property Detail</th>
                                                                    <th width="25%">Borrower Input</th>
                                                                    <th width="25%">Validator Input</th>
                                                                    <th width="25%">Result</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Square Meters</td>
                                                                    <td>
                                                                        <input type="number" class="form-control" name="square_meters" 
                                                                            value="<?php echo htmlspecialchars($collateral_info['square_meters'] ?? ''); ?>" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" class="form-control" name="validator_square_meters">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="result_square_meters" class="form-control result-cell" readonly>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Type of Land</td>
                                                                    <td>
                                                                        <input type="text" class="form-control" name="type_of_land" 
                                                                            value="<?php echo htmlspecialchars($collateral_info['type_of_land'] ?? ''); ?>" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="validator_land_type" required>
                                                                            <option value="">Select</option>
                                                                            <option value="INDUSTRIAL">Industrial</option>
                                                                            <option value="RESIDENTIAL">Residential</option>
                                                                            <option value="AGRICULTURAL">Agricultural</option>
                                                                            <option value="COMMERCIAL">Commercial</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="result_land_type" class="form-control result-cell" readonly>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Location</td>
                                                                    <td>
                                                                        <input type="text" class="form-control" name="location_name"
                                                                            value="<?php echo htmlspecialchars($collateral_info['location_name'] ?? ''); ?>" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="validator_location" required>
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            $location_sql = "SELECT name FROM locations ORDER BY name";
                                                                            $location_result = $conn->query($location_sql);
                                                                            while ($loc = $location_result->fetch_assoc()) {
                                                                                echo "<option value='" . htmlspecialchars($loc['name']) . "'>" . 
                                                                                    htmlspecialchars($loc['name']) . "</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="result_location" class="form-control result-cell" readonly>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Right of Way</td>
                                                                    <td>
                                                                        <input type="text" class="form-control borrower-input" name="right_of_way"
                                                                            value="<?php echo htmlspecialchars($collateral_info['right_of_way'] ?? ''); ?>" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control" name="validator_right_of_way">
                                                                            <option value="">Select</option>
                                                                            <option value="Yes">Yes</option>
                                                                            <option value="No">No</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="result_right_of_way" class="form-control result-cell" readonly>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                            $proximity_fields = [
                                                                                ['hospital', 'Hospital', 'has_hospital'],
                                                                                ['clinic', 'Clinic', 'has_clinic'],
                                                                                ['school', 'School', 'has_school'],
                                                                                ['market', 'Market', 'has_market'],
                                                                                ['church', 'Church', 'has_church'],
                                                                                ['public_terminal', 'Public Terminal', 'has_terminal']
                                                                            ];

                                                                            foreach ($proximity_fields as $field):
                                                                                $field_name = $field[0];
                                                                                $display_name = $field[1];
                                                                                $db_field = $field[2];
                                                                                $field_value = $collateral_info[$db_field] ?? 'No';
                                                                                $display_value = $field_value === 'Yes' ? '< 1KM' : '> 1KM';
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $display_name; ?></td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" name="<?php echo $db_field; ?>" 
                                                                                        value="<?php echo htmlspecialchars($field_value); ?>" readonly>
                                                                                </td>
                                                                                <td>
                                                                                    <select class="form-control" name="validator_<?php echo $field_name; ?>">
                                                                                        <option value="">Select</option>
                                                                                        <option value="No">> 1KM</option>
                                                                                        <option value="Yes">< 1KM</option>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" id="result_<?php echo $field_name; ?>" 
                                                                                        class="form-control result-cell" readonly>
                                                                                </td>
                                                                            </tr>
                                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>

                                                        <table class="table table-bordered mt-4">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="25%">Final Zonal Value</td>
                                                                    <td>
                                                                        <input type="number" class="form-control" name="final_zonal_value" 
                                                                            value="<?php echo htmlspecialchars($collateral_info['final_zonal_value'] ?? ''); ?>" readonly>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>EMV (Per Sqm)</td>
                                                                    <td>
                                                                        <input type="number" class="form-control" name="emv_per_sqm" 
                                                                            value="<?php echo htmlspecialchars($collateral_info['emv_per_sqm'] ?? ''); ?>" readonly>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Total Value</td>
                                                                    <td>
                                                                        <input type="number" class="form-control" name="total_value" 
                                                                            value="<?php echo htmlspecialchars($collateral_info['total_value'] ?? ''); ?>" readonly>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Loanable Value</td>
                                                                    <td>
                                                                        <input type="number" class="form-control" name="loanable_value" 
                                                                            value="<?php echo htmlspecialchars($collateral_info['loanable_value'] ?? ''); ?>" readonly>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <div class="form-group mt-4">
                                                            <label>Upload Property Images</label>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="property_images[]" multiple accept="image/*">
                                                                <label class="custom-file-label">Choose files</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <?php if (!$appraisal_data): ?>
                                        <button type="submit" class="btn btn-primary" onclick="submitValidatorAssessment(<?php echo $row['LoanID']; ?>)">Submit Assessment</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                
                <?php 
                    endwhile;
                endif;
                ?>


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
        // Search form functionality
        var searchForm = document.getElementById('searchForm');
        var searchInput = document.getElementById('searchInput');
        var clearButton = document.getElementById('clearButton');

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                searchInput.value = '';
                searchForm.submit();
            });
        }

        // Define the zonal values based on barangay and land type
        const ZONAL_VALUES = {
            'Bagbaguin': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1100,
                'INDUSTRIAL': 3800
            },
            'Bagong Barrio': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 3800
            },
            'Bakabakahan': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1100,
                'INDUSTRIAL': 3800
            },
            'Bunsuran I': {
                'RESIDENTIAL': 2500,
                'COMMERCIAL': 5000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 4800
            },
            'Bunsuran II': {
                'RESIDENTIAL': 2500,
                'COMMERCIAL': 5000,
                'AGRICULTURAL': 1000,
                'INDUSTRIAL': 4800
            },
            'Bunsuran III': {
                'RESIDENTIAL': 2500,
                'COMMERCIAL': 5000,
                'AGRICULTURAL': 1000,
                'INDUSTRIAL': 4800
            },
            'Cacarong Bata': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 3800
            },
            'Cacarong Matanda': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 3800
            },
            'Cupang': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 3800
            },
            'Malibong Bata': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1100,
                'INDUSTRIAL': 3800
            },
            'Malibong Matanda': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 3800
            },
            'Manatal': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 3800
            },
            'Mapulang Lupa': {
                'RESIDENTIAL': 2500,
                'COMMERCIAL': 5000,
                'AGRICULTURAL': 1000,
                'INDUSTRIAL': 4800
            },
            'Masagana': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 3800
            },
            'Masuso': {
                'RESIDENTIAL': 2500,
                'COMMERCIAL': 5000,
                'AGRICULTURAL': 800,
                'INDUSTRIAL': 5000
            },
            'Pinagkuartelan': {
                'RESIDENTIAL': 2500,
                'COMMERCIAL': 5000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 4800
            },
            'Poblacion': {
                'RESIDENTIAL': 2500,
                'COMMERCIAL': 5000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 5000
            },
            'Real De Cacarong': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 3800
            },
            'San Roque': {
                'RESIDENTIAL': 2500,
                'COMMERCIAL': 5000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 4800
            },
            'Santo Niño': {
                'RESIDENTIAL': 2000,
                'COMMERCIAL': 4000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 3800
            },
            'Siling Bata': {
                'RESIDENTIAL': 2500,
                'COMMERCIAL': 5000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 4800
            },
            'Siling Matanda': {
                'RESIDENTIAL': 2500,
                'COMMERCIAL': 5000,
                'AGRICULTURAL': 1200,
                'INDUSTRIAL': 4800
            }
        };
 // Function to check if values match
 function checkMatch(borrowerValue, validatorValue) {
        if (!borrowerValue || !validatorValue) return '';
        
        // Convert both values to lowercase strings for comparison
        const normalizedBorrower = String(borrowerValue).toLowerCase().trim();
        const normalizedValidator = String(validatorValue).toLowerCase().trim();
        
        // Handle proximity values (> 1KM, < 1KM)
        if (normalizedBorrower.includes('1km') || normalizedValidator === 'yes' || normalizedValidator === 'no') {
            if ((normalizedBorrower === '< 1km' && normalizedValidator === 'yes') ||
                (normalizedBorrower === '> 1km' && normalizedValidator === 'no')) {
                return 'Matched';
            }
            return 'Not Matched';
        }
        
        return normalizedBorrower === normalizedValidator ? 'Matched' : 'Not Matched';
    }

    // Function to update result cell
    function updateResultCell(resultElement, borrowerValue, validatorValue) {
        if (!resultElement) return false;
        
        const result = checkMatch(borrowerValue, validatorValue);
        resultElement.value = result;
        resultElement.className = 'form-control result-cell ' + 
            (result === 'Matched' ? 'matched' : 'not-matched');
        
        return result === 'Matched';
    }
    function updateCalculations(form) {
    let allMatched = true;

    // Update Square Meters
    const squareMetersResult = form.querySelector('#result_square_meters');
    const borrowerSquareMeters = form.querySelector('input[name="square_meters"]')?.value;
    const validatorSquareMeters = form.querySelector('input[name="validator_square_meters"]')?.value;
    allMatched &= updateResultCell(squareMetersResult, borrowerSquareMeters, validatorSquareMeters);

    // Update Land Type
    const landTypeResult = form.querySelector('#result_land_type');
    const borrowerLandType = form.querySelector('input[name="type_of_land"]')?.value;
    const validatorLandType = form.querySelector('select[name="validator_land_type"]')?.value;
    allMatched &= updateResultCell(landTypeResult, borrowerLandType, validatorLandType);
      
    // Update location comparison
    const locationMatched = updateLocationComparison(form);
    allMatched &= locationMatched;
    
    // Update Right of Way
    const rightOfWayResult = form.querySelector('#result_right_of_way');
    const borrowerRightOfWay = form.querySelector('input[name="right_of_way"]')?.value;
    const validatorRightOfWay = form.querySelector('select[name="validator_right_of_way"]')?.value;
    if (rightOfWayResult && borrowerRightOfWay && validatorRightOfWay) {
        const normalizedBorrower = borrowerRightOfWay.trim().toLowerCase();
        const normalizedValidator = validatorRightOfWay.trim().toLowerCase();
        const rightOfWayMatched = normalizedBorrower === normalizedValidator;
        
        rightOfWayResult.value = rightOfWayMatched ? 'Matched' : 'Not Matched';
        rightOfWayResult.className = `form-control result-cell ${rightOfWayMatched ? 'matched' : 'not-matched'}`;
        allMatched &= rightOfWayMatched;
    }

    // Update Proximity Fields
    const proximityFields = [
        { name: 'hospital', dbField: 'has_hospital' },
        { name: 'clinic', dbField: 'has_clinic' },
        { name: 'school', dbField: 'has_school' },
        { name: 'market', dbField: 'has_market' },
        { name: 'church', dbField: 'has_church' },
        { name: 'public_terminal', dbField: 'has_terminal' }
    ];

    proximityFields.forEach(field => {
        const resultElement = form.querySelector(`#result_${field.name}`);
        const borrowerInput = form.querySelector(`input[name="${field.dbField}"]`);
        const validatorSelect = form.querySelector(`select[name="validator_${field.name}"]`);
        
        if (resultElement && borrowerInput && validatorSelect) {
            const borrowerValue = borrowerInput.value === 'Yes' ? '< 1KM' : '> 1KM';
            allMatched &= updateResultCell(resultElement, borrowerValue, validatorSelect.value);
        }
    });

    // Calculate and update final values if all required fields are filled
    const validatorLocation = form.querySelector('select[name="validator_location"]')?.value;
    const validSquareMeters = parseFloat(validatorSquareMeters) > 0;
    const validLandType = validatorLandType && validatorLandType !== '';
    const validLocation = validatorLocation && validatorLocation !== '';

    if (validSquareMeters && validLandType && validLocation) {
        // Get references to final value fields
        const finalZonalValueInput = form.querySelector('input[name="final_zonal_value"]');
        const emvPerSqmInput = form.querySelector('input[name="emv_per_sqm"]');
        const totalValueInput = form.querySelector('input[name="total_value"]');
        const loanableValueInput = form.querySelector('input[name="loanable_value"]');

        if (finalZonalValueInput && emvPerSqmInput && totalValueInput && loanableValueInput) {
            // Calculate zonal value
            const zonalValue = ZONAL_VALUES[validatorLocation]?.[validatorLandType] || 0;
            
            // Calculate EMV and other values
            const emvPerSqm = zonalValue;
            const totalValue = emvPerSqm * parseFloat(validatorSquareMeters);
            const loanableValue = totalValue * 0.5;

            // Update calculated fields
            finalZonalValueInput.value = zonalValue.toFixed(2);
            emvPerSqmInput.value = emvPerSqm.toFixed(2);
            totalValueInput.value = totalValue.toFixed(2);
            loanableValueInput.value = loanableValue.toFixed(2);
        }
    }
}

function updateLocationComparison(form) {
    const locationResult = form.querySelector('#result_location');
    const borrowerLocation = form.querySelector('input[name="location_name"]')?.value;
    const validatorLocation = form.querySelector('select[name="validator_location"]')?.value;
    
    if (locationResult && borrowerLocation && validatorLocation) {
        const normalizedBorrower = borrowerLocation.trim().toLowerCase();
        const normalizedValidator = validatorLocation.trim().toLowerCase();
        const locationMatched = normalizedBorrower === normalizedValidator;
        
        locationResult.value = locationMatched ? 'Matched' : 'Not Matched';
        locationResult.className = `form-control result-cell ${locationMatched ? 'matched' : 'not-matched'}`;
        
        return locationMatched;
    }
    return false;
}


    // Function to handle file input changes
    function handleFileInputChange(input) {
        const fileName = Array.from(input.files)
            .map(file => file.name)
            .join(', ');
        const label = input.nextElementSibling;
        label.textContent = fileName || 'Choose files';
    }

 // Validation function
    function validateForm(form) {
        const requiredFields = [
            'validator_square_meters',
            'validator_land_type',
            'validator_location',
            'validator_right_of_way'
        ];

        for (const field of requiredFields) {
            const input = form.querySelector(`[name="${field}"]`);
            if (!input || !input.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Required Field Missing',
                    text: `Please fill in ${field.replace('validator_', '')}`
                });
                return false;
            }
        }
        return true;
    }

    // Check if all values match
    function areAllValuesMatched(form) {
        const resultCells = form.querySelectorAll('.result-cell');
        return Array.from(resultCells).every(cell => cell.value === 'Matched');
    }

    // Form submission function
    async function submitValidatorAssessment(loanId) {
        const form = document.getElementById(`collateralForm${loanId}`);
        if (!form) return;

        if (!validateForm(form)) {
            return;
        }

        updateCalculations(form);
        
        if (!areAllValuesMatched(form)) {
            const result = await Swal.fire({
                title: 'Warning',
                text: 'Some values do not match. Do you want to proceed with submission?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'No, cancel'
            });
            
            if (!result.isConfirmed) {
                return;
            }
        }

        const formData = new FormData(form);
        formData.append('loan_id', loanId);

        const imageFiles = form.querySelector('input[name="property_images[]"]').files;
        if (imageFiles.length > 0) {
            for (let i = 0; i < imageFiles.length; i++) {
                formData.append('property_images[]', imageFiles[i]);
            }
        }

        try {
            const response = await fetch('save_validator_assessment.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            if (result.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Validator assessment saved successfully',
                    timer: 1500
                });
                location.reload();
            } else {
                throw new Error(result.message || result.error || 'Unknown error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Error saving validator assessment: ${error.message}`
            });
        }
    }
    // Initialize all forms
    function initializeForms() {
        const forms = document.querySelectorAll('.collateral-form');
        forms.forEach(form => {
            // Add change event listeners to all validator inputs
            const validatorInputs = form.querySelectorAll('select[name^="validator_"], input[name^="validator_"]');
            validatorInputs.forEach(input => {
                input.addEventListener('change', () => {
                    updateCalculations(form);
                });
            });

            // Add file input change listeners
            const fileInputs = form.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', () => {
                    handleFileInputChange(input);
                });
            });

            // Initialize calculations
            updateCalculations(form);
        });
    }
    // Add CSS styles
    const style = document.createElement('style');
    style.textContent = `
        .result-cell {
            text-align: center;
            font-weight: bold;
            border: 1px solid #ced4da;
        }
        .result-cell.matched {
            background-color: #d4edda !important;
            color: #155724 !important;
        }
        .result-cell.not-matched {
            background-color: #f8d7da !important;
            color: #721c24 !important;
        }
    `;
    document.head.appendChild(style);

    // Initialize everything
    initializeForms();

    // Make submitValidatorAssessment globally available
    window.submitValidatorAssessment = submitValidatorAssessment;
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