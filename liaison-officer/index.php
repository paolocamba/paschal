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
                    .navbar {
            padding-top: 0 !important;
            margin-top: 0 !important;
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
                    FROM land_appraisal c
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
<!-- Separate Modal for Each Row - Only shown when view button is clicked -->
<?php 
if ($result->num_rows > 0):
    $result->data_seek(0);
    while($row = $result->fetch_assoc()): 
        // Check if validation exists (if any validator_ fields are filled)
        $appraisal_sql = "SELECT * FROM land_appraisal WHERE LoanID = ?";
        $appraisal_stmt = $conn->prepare($appraisal_sql);
        $appraisal_stmt->bind_param("i", $row['LoanID']);
        $appraisal_stmt->execute();
        $appraisal_data = $appraisal_stmt->get_result()->fetch_assoc();
        
        // Check if this is validated (any validator field is not null)
        $is_validated = false;
        if ($appraisal_data) {
            foreach ($appraisal_data as $key => $value) {
                if (strpos($key, 'validator_') === 0 && $value !== null) {
                    $is_validated = true;
                    break;
                }
            }
        }
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
                    <?php if ($is_validated): ?>
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Validated Assessment Results</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="30%">Square Meters</th>
                                                <td><?php echo number_format($appraisal_data['validator_square_meters'], 2); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Type of Land</th>
                                                <td><?php echo htmlspecialchars($appraisal_data['validator_type_of_land']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Location</th>
                                                <td><?php echo htmlspecialchars($appraisal_data['validator_location']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Right of Way</th>
                                                <td><?php echo htmlspecialchars($appraisal_data['validator_right_of_way']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Hospital</th>
                                                <td><?php echo htmlspecialchars($appraisal_data['validator_hospital']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Clinic</th>
                                                <td><?php echo htmlspecialchars($appraisal_data['validator_clinic']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>School</th>
                                                <td><?php echo htmlspecialchars($appraisal_data['validator_school']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Market</th>
                                                <td><?php echo htmlspecialchars($appraisal_data['validator_market']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Church</th>
                                                <td><?php echo htmlspecialchars($appraisal_data['validator_church']); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Public Terminal</th>
                                                <td><?php echo htmlspecialchars($appraisal_data['validator_terminal']); ?></td>
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
                                            <tr>
                                                <th>Date Validated</th>
                                                <td><?php echo htmlspecialchars($appraisal_data['validated_date']); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if (!empty($appraisal_data['image_path1']) || !empty($appraisal_data['image_path2']) || !empty($appraisal_data['image_path3'])): ?>

                                    <div class="mt-4">
                                        <h6>Property Images</h6>
                                        <?php 
                                        $imagePaths = [
                                            $appraisal_data['image_path1'] ?? null,
                                            $appraisal_data['image_path2'] ?? null,
                                            $appraisal_data['image_path3'] ?? null
                                        ];
                                        
                                        foreach ($imagePaths as $image) {
                                            if (!empty($image)) {
                                                echo '<img src="' . htmlspecialchars($image) . '" class="img-fluid mb-2" alt="Property Image" style="max-width: 100%; height: auto; display: block;">';
                                            }
                                        }
                                        ?>
                                    </div>

                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                    <form id="collateralForm<?php echo $row['LoanID']; ?>" class="collateral-form">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Land Title</h6>
                                <div class="title-image" style="text-align: center;">
                                    <?php if ($appraisal_data && $appraisal_data['land_title_path']): ?>
                                        <img src="<?php echo htmlspecialchars($appraisal_data['land_title_path']) ?>" 
                                            alt="Land Title Image" 
                                            id="landTitleImage"
                                            style="margin: 0 auto; max-width: 300px; height: auto;">
                                    <?php endif; ?>
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
                                                        value="<?php echo htmlspecialchars($appraisal_data['square_meters'] ?? ''); ?>" readonly>
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
                                                        value="<?php echo htmlspecialchars($appraisal_data['type_of_land'] ?? ''); ?>" readonly>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="validator_type_of_land" required>
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
                                                        value="<?php echo htmlspecialchars($appraisal_data['location_name'] ?? ''); ?>" readonly>
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
                                                        value="<?php echo htmlspecialchars($appraisal_data['right_of_way'] ?? 'No'); ?>" readonly>
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
                                                ['terminal', 'Terminal', 'has_terminal']
                                            ];

                                            foreach ($proximity_fields as $field):
                                                $field_name = $field[0];
                                                $display_name = $field[1];
                                                $db_field = $field[2];
                                                $field_value = $appraisal_data[$db_field] ?? 'No';
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
                                                        value="<?php echo htmlspecialchars($appraisal_data['final_zonal_value'] ?? ''); ?>" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>EMV (Per Sqm)</td>
                                                <td>
                                                    <input type="number" class="form-control" name="EMV_per_sqm" 
                                                        value="<?php echo htmlspecialchars($appraisal_data['EMV_per_sqm'] ?? ''); ?>" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Value</td>
                                                <td>
                                                    <input type="number" class="form-control" name="total_value" 
                                                        value="<?php echo htmlspecialchars($appraisal_data['total_value'] ?? ''); ?>" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Loanable Value</td>
                                                <td>
                                                    <input type="number" class="form-control" name="loanable_amount" 
                                                        value="<?php echo htmlspecialchars($appraisal_data['loanable_amount'] ?? ''); ?>" readonly>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="form-group mt-4">
                                        <label>Upload Property Images (Max 3 images)</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="property_images[]" multiple accept="image/*" max="6">
                                            <label class="custom-file-label">Choose files (up to 3 images)</label>
                                        </div>
                                        <small class="form-text text-muted">You can select multiple images (JPEG, PNG, etc.)</small>
                                        <div id="filePreview<?php echo $row['LoanID']; ?>" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" id="calculateBtn">Calculate</button>
                    <button type="button" class="btn btn-primary save-appraisal" data-loanid="<?php echo $row['LoanID']; ?>">Save Assessment</button>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
<?php endif; ?>
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
        document.addEventListener('DOMContentLoaded', function() {
    // Initialize form event listeners
    initializeForms();
});

function initializeForms() {
    const forms = document.querySelectorAll('.collateral-form');
    forms.forEach(form => {
        // Add change listeners to validator inputs to update match status
        const validatorInputs = form.querySelectorAll('select[name^="validator_"], input[name^="validator_"]');
        validatorInputs.forEach(input => {
            input.addEventListener('change', () => {
                updateCalculations(form);
            });
        });
    });
}

// Update match status indicators
function updateCalculations(form) {
    // Square Meters
    updateMatchStatus(
        form.querySelector('input[name="square_meters"]'),
        form.querySelector('input[name="validator_square_meters"]'),
        form.querySelector('#result_square_meters')
    );

    // Land Type
    updateMatchStatus(
        form.querySelector('input[name="type_of_land"]'),
        form.querySelector('select[name="validator_type_of_land"]'),
        form.querySelector('#result_land_type')
    );

    // Location
    updateMatchStatus(
        form.querySelector('input[name="location_name"]'),
        form.querySelector('select[name="validator_location"]'),
        form.querySelector('#result_location')
    );

    // Right of Way
    updateMatchStatus(
        form.querySelector('input[name="right_of_way"]'),
        form.querySelector('select[name="validator_right_of_way"]'),
        form.querySelector('#result_right_of_way'),
        'right_of_way'  // Pass the field name to identify it's the special case
    );

    // Proximity Fields
    const proximityFields = [
        { name: 'hospital', dbField: 'has_hospital' },
        { name: 'clinic', dbField: 'has_clinic' },
        { name: 'school', dbField: 'has_school' },
        { name: 'market', dbField: 'has_market' },
        { name: 'church', dbField: 'has_church' },
        { name: 'terminal', dbField: 'has_terminal' }
    ];

    proximityFields.forEach(field => {
        const borrowerInput = form.querySelector(`input[name="${field.dbField}"]`);
        const validatorInput = form.querySelector(`select[name="validator_${field.name}"]`);
        const resultCell = form.querySelector(`#result_${field.name}`);
        
        if (borrowerInput && validatorInput && resultCell) {
            const borrowerValue = borrowerInput.value === 'Yes' ? '< 1KM' : '> 1KM';
            updateMatchStatus(borrowerValue, validatorInput.value, resultCell);
        }
    });
}

function updateMatchStatus(borrowerValue, validatorValue, resultElement, fieldName) {
    if (!resultElement) return;
    
    // Get the actual values (handling both direct values and input elements)
    const borrowerVal = (typeof borrowerValue === 'object' && borrowerValue !== null) ? 
        borrowerValue.value : borrowerValue;
    const validatorVal = (typeof validatorValue === 'object' && validatorValue !== null) ? 
        validatorValue.value : validatorValue;
    
    // If validator value is empty, show empty result
    if (!validatorVal) {
        resultElement.value = '';
        resultElement.className = 'form-control result-cell';
        return;
    }
    
    let result;
    
    // Special handling for Right of Way field
    if (fieldName === 'right_of_way') {
        // Consider it matched if both are "Yes" or both are "No"
        result = (borrowerVal === validatorVal) ? 'Matched' : 'Not Matched';
    } else {
        // Normal matching logic for other fields
        result = checkMatch(borrowerVal, validatorVal);
    }
    
    resultElement.value = result;
    resultElement.className = 'form-control result-cell ' + 
        (result === 'Matched' ? 'matched' : 'not-matched');
}

// Check if values match (for UI only)
function checkMatch(borrowerValue, validatorValue) {
    if (typeof borrowerValue === 'object' && borrowerValue !== null) {
        borrowerValue = borrowerValue.value;
    }
    if (typeof validatorValue === 'object' && validatorValue !== null) {
        validatorValue = validatorValue.value;
    }
    
    if (!validatorValue) return '';
    
    const strBorrower = String(borrowerValue).toLowerCase().trim();
    const strValidator = String(validatorValue).toLowerCase().trim();
    
    // Handle proximity fields
    if (strValidator === 'yes' || strValidator === 'no') {
        const borrowerProximity = strBorrower.includes('< 1km') ? 'yes' : 'no';
        return borrowerProximity === strValidator ? 'Matched' : 'Not Matched';
    }
    
    return strBorrower === strValidator ? 'Matched' : 'Not Matched';
}
// Calculate button handler
document.addEventListener('click', function(e) {
    if (e.target.id === 'calculateBtn') {
        const modal = e.target.closest('.modal');
        const form = modal.querySelector('.collateral-form');
        const LoanID = modal.id.replace('viewModal', '');
        calculateCollateralValue(form, LoanID);
    }
});

async function calculateCollateralValue(form, LoanID) {
    try {
        Swal.fire({
            title: 'Calculating...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        // Get form data
        const formData = {
            LoanID: LoanID,
            square_meters: form.querySelector('[name="validator_square_meters"]').value,
            type_of_land: form.querySelector('[name="validator_type_of_land"]').value,
            location_name: form.querySelector('[name="validator_location"]').value,
            right_of_way: form.querySelector('[name="validator_right_of_way"]').value || 'No',
            hospital: form.querySelector('[name="validator_hospital"]').value || 'No',
            clinic: form.querySelector('[name="validator_clinic"]').value || 'No',
            school: form.querySelector('[name="validator_school"]').value || 'No',
            market: form.querySelector('[name="validator_market"]').value || 'No',
            church: form.querySelector('[name="validator_church"]').value || 'No',
            terminal: form.querySelector('[name="validator_terminal"]').value || 'No'
        };

        const response = await fetch('run_prediction.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (!result.success) {
            throw new Error(result.error || "Calculation failed");
        }

        // Update the form fields with the returned values
        const prediction = result.prediction;
        form.querySelector('[name="final_zonal_value"]').value = prediction.final_zonal_value?.toFixed(2) || '0.00';
        form.querySelector('[name="EMV_per_sqm"]').value = prediction.EMV_per_sqm?.toFixed(2) || '0.00';
        form.querySelector('[name="total_value"]').value = prediction.total_value?.toFixed(2) || '0.00';
        form.querySelector('[name="loanable_amount"]').value = prediction.loanable_amount?.toFixed(2) || '0.00';

        Swal.fire({
            icon: 'success',
            title: 'Calculation Complete',
            text: `Loanable Amount: ₱${prediction.loanable_amount?.toFixed(2) || '0.00'}`,
            didClose: () => {
                // Any post-calculation actions can go here
            }
        });
    } catch (error) {
        console.error("Error:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message,
            footer: 'Check console for details'
        });
    }
}


// Add event listener for save buttons
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('save-appraisal')) {
        const LoanID = e.target.getAttribute('data-loanid');
        submitValidatorAssessment(LoanID);
    }
});

function checkAllMatched(form) {
    // Example logic: Check if required numeric fields have expected values
    const expectedValues = {
        final_zonal_value: form.querySelector('[name="final_zonal_value"]').value,
        EMV_per_sqm: form.querySelector('[name="EMV_per_sqm"]').value,
        total_value: form.querySelector('[name="total_value"]').value,
        loanable_amount: form.querySelector('[name="loanable_amount"]').value
    };

    let allMatched = true;

    Object.keys(expectedValues).forEach(field => {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input || input.value.trim() !== expectedValues[field].trim()) {
            allMatched = false;
        }
    });

    return allMatched;
}


async function submitValidatorAssessment(LoanID) {
    console.log("LoanID received:", LoanID); // Debugging

    const form = document.querySelector(`#viewModal${LoanID} .collateral-form`);
    if (!form) return;

    // ✅ Declare formData at the top before using it
    const formData = new FormData();
    formData.append('LoanID', LoanID);

    // Validate required fields
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fill in all required fields'
        });
        return;
    }

    // Add all validator fields
    const validatorInputs = form.querySelectorAll('[name^="validator_"]');
    validatorInputs.forEach(input => {
        formData.append(input.name, input.value);
    });

    // Add calculation results
    const calculationFields = ['final_zonal_value', 'EMV_per_sqm', 'total_value', 'loanable_amount'];
    calculationFields.forEach(field => {
        const value = form.querySelector(`[name="${field}"]`).value;
        formData.append(field, value);
    });

    // Add image files if any
    const imageInput = form.querySelector('input[name="property_images[]"]');
    if (imageInput && imageInput.files.length > 0) {
        for (let i = 0; i < imageInput.files.length; i++) {
            formData.append('property_images[]', imageInput.files[i]);
        }
    }

    try {
        console.log("Submitting validator assessment...");

        // First, save validator data in the database
        const saveResponse = await fetch('/paschal/liaison-officer/save_validator_assessment.php', {
            method: 'POST',
            body: formData
        });

        const saveText = await saveResponse.text();
        console.log("Raw response from save_validator_data.php:", saveText);
        const saveResult = JSON.parse(saveText);

        if (!saveResult.success) {
            throw new Error(saveResult.error || "Failed to save validator data.");
        }

        // Now, run eligibility check
        console.log("Running prediction for LoanID:", LoanID);
        const predictionResponse = await fetch(`/paschal/member/run_prediction.php?LoanID=${String(LoanID)}`);

        const predictionText = await predictionResponse.text();
        console.log("Raw response from run_prediction.php:", predictionText);
        const predictionResult = JSON.parse(predictionText);

        if (!predictionResult.success) {
            throw new Error(predictionResult.error || 'Eligibility check failed');
        }

        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Assessment saved and eligibility check completed',
            timer: 1500
        }).then(() => {
            $(`#viewModal${LoanID}`).modal('hide');
            location.reload();
        });

    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    }
}




// Function to handle file input changes with preview
function handleFileInputChange(input) {
    const previewContainer = input.closest('.form-group').querySelector('#filePreview' + input.closest('.modal').id.replace('viewModal', ''));
    previewContainer.innerHTML = '';
    
    const files = input.files;
    const label = input.nextElementSibling;
    
    if (files.length > 0) {
        // Update label
        if (files.length === 1) {
            label.textContent = files[0].name;
        } else {
            label.textContent = files.length + ' files selected';
        }
        
        // Show previews
        if (files.length > 3) {
            Swal.fire({
                icon: 'error',
                title: 'Too many files',
                text: 'You can upload a maximum of 3 images',
            });
            input.value = '';
            label.textContent = 'Choose files (up to 3 images)';
            return;
        }
        
        // Create preview for each file
        Array.from(files).slice(0, 3).forEach((file, index) => {
            if (!file.type.match('image.*')) {
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'd-inline-block mr-2 mb-2 position-relative';
                previewDiv.style.width = '100px';
                
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="height:100px;object-fit:cover">
                    <button type="button" class="btn btn-danger btn-sm position-absolute" style="top:-10px;right:-10px" 
                        onclick="removeImagePreview(this, ${index})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                previewContainer.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        });
    } else {
        label.textContent = 'Choose files (up to 3 images)';
    }
}

// Function to remove image preview
// Add this to your script to make the function globally available
window.removeImagePreview = function(button, index) {
    const input = button.closest('.form-group').querySelector('input[type="file"]');
    const files = Array.from(input.files);
    
    // Remove the file from the FileList
    files.splice(index, 1);
    
    // Create a new DataTransfer object to hold the remaining files
    const dataTransfer = new DataTransfer();
    files.forEach(file => dataTransfer.items.add(file));
    
    // Assign the modified FileList back to the input
    input.files = dataTransfer.files;
    
    // Trigger the change event to update the display
    const event = new Event('change');
    input.dispatchEvent(event);
    
    // Remove the preview element
    button.closest('div').remove();
};
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