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
    <title>Loans | Admin</title>
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
                       title: "Loan Updated Successfully",
                       showConfirmButton: false,
                       timer: 1500 // Close after 1.5 seconds
                   });
               });
           </script>';
           }
            ?>


          
           

            <?php
            include '../connection/config.php';
            
            require_once 'ml_model.php';
            use App\ML\LoanMLSystem;

            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $sql = "SELECT 
            l.LoanID,
            l.userID,
            l.AmountRequested,
            l.loanable_amount,
            l.ModeOfPayment,
            l.LoanTerm,
            l.net_family_income,
            l.LoanType,
            l.DateOfLoan,
            l.Eligibility,
            l.Status,
            l.deed_of_sale1,
            l.deed_of_sale2,
            l.deed_of_sale3,
            l.deed_of_sale4,
            l.orcr_vehicle1,
            l.orcr_vehicle2,
            l.orcr_vehicle3,
            l.orcr_vehicle4,
            l.valid_id_path,
            l.deed_of_sale_path,
            l.vehicle_orcr_path,
            l.proof_of_income_path,
            l.tax_declaration_path,
            l.tax_clearance_path,
            l.original_transfer_certificate_of_title_path,
            l.certified_true_copy_path,
            l.vicinity_map_path,
            l.barangay_clearance_path,
            l.cedula_path,
            l.post_dated_check_path,
            l.promisory_note_path,
            l.years_stay_present_address,
            l.own_house,
            l.renting,
            l.living_with_relative,
            l.marital_status,
            l.spouse_name,
            l.number_of_dependents,
            l.dependents_in_school,
            l.dependent1_name,
            l.dependent1_age,
            l.dependent1_grade_level,
            l.dependent2_name,
            l.dependent2_age,
            l.dependent2_grade_level,
            l.dependent3_name,
            l.dependent3_age,
            l.dependent3_grade_level,
            l.dependent4_name,
            l.dependent4_age,
            l.dependent4_grade_level,
            l.employer_name,
            l.employer_address,
            l.present_position,
            l.date_of_employment,
            l.contact_person,
            l.contact_telephone_no,
            l.self_employed_business_type,
            l.business_start_date,
            l.monthly_income,
            l.self_other_income_amount,
            l.other_income,
            l.spouse_income,
            l.spouse_income_amount,
            l.spouse_other_income_amount,
            l.food_groceries_expense,
            l.gas_oil_transportation_expense,
            l.schooling_expense,
            l.utilities_expense,
            l.miscellaneous_expense,
            l.total_expenses,
            l.savings_account,
            l.savings_bank,
            l.savings_branch,
            l.current_account,
            l.current_bank,
            l.current_branch,
            l.assets1,
            l.assets2,
            l.assets3,
            l.assets4,
            l.creditor1_name,
            l.creditor1_address,
            l.creditor1_original_amount,
            l.creditor1_present_balance,
            l.creditor2_name,
            l.creditor2_address,
            l.creditor2_original_amount,
            l.creditor2_present_balance,
            l.creditor3_name,
            l.creditor3_address,
            l.creditor3_original_amount,
            l.creditor3_present_balance,
            l.creditor4_name,
            l.creditor4_address,
            l.creditor4_original_amount,
            l.creditor4_present_balance,
            l.property_foreclosed_repossessed,
            l.co_maker_cosigner_guarantor,
            l.reference1_name,
            l.reference1_address,
            l.reference1_contact_no,
            l.reference2_name,
            l.reference2_address,
            l.reference2_contact_no,
            l.reference3_name,
            l.reference3_address,
            l.reference3_contact_no,
            c.land_title_path,
            c.square_meters,
            c.type_of_land,
            c.location_name,
            c.right_of_way,
            c.has_hospital,
            c.has_school,
            c.has_clinic,
            c.has_church,
            c.has_market,
            c.has_terminal,
            u.first_name,
            u.last_name
        FROM loanapplication l
        JOIN users u ON l.userID = u.user_id
        LEFT JOIN land_appraisal c ON l.LoanID = c.LoanID
        WHERE l.LoanID LIKE ? 
        OR u.first_name LIKE ?
        OR u.last_name LIKE ?
        OR l.LoanType LIKE ?
        OR l.Status LIKE ?
        LIMIT ? OFFSET ?";

            $stmt = $conn->prepare($sql);
            $search_param = "%" . $search . "%";
            $stmt->bind_param("sssssii", 
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

            $count_sql = "SELECT COUNT(*) as total 
                        FROM loanapplication l
                        JOIN users u ON l.userID = u.user_id
                        WHERE l.LoanID LIKE ? 
                        OR u.first_name LIKE ?
                        OR u.last_name LIKE ?
                        OR l.LoanType LIKE ?
                        OR l.Status LIKE ?";

            $count_stmt = $conn->prepare($count_sql);
            $count_stmt->bind_param("sssss", 
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
            ?>

            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="card-title mb-0">Loan Applications</p>
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
                                            <th>Applicant Name</th>
                                            <th>Amount Requested</th>
                                            <th>Loanable Amount</th>
                                            <th>Loan Type</th>
                                            <th>Date of Loan</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['LoanID']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                                    <td>₱<?php echo htmlspecialchars($row['AmountRequested']); ?></td>
                                                    <td>₱<?php echo htmlspecialchars($row['loanable_amount']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['LoanType']); ?></td>
                                                    <td><?php echo date('F d, Y', strtotime($row['DateOfLoan'])); ?></td>
                                                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?php echo $row['LoanID']; ?>">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewModal<?php echo $row['LoanID']; ?>">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                
                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal<?php echo $row['LoanID']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">Edit Loan Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="edit_loan.php" method="POST">
                                                            <input type="hidden" name="LoanID" value="<?php echo $row['LoanID']; ?>">
                                                            
                                                            <!-- Loan Type -->
                                                            <div class="mb-3">
                                                                <label for="LoanType" class="form-label">Loan Type</label>
                                                                <input type="text" class="form-control" id="LoanType" name="LoanType" 
                                                                    value="<?php echo htmlspecialchars($row['LoanType']); ?>" readonly>
                                                            </div>
                                                            
                                                            <!-- Interest Rate (Annual) -->
                                                            <div class="mb-3">
                                                                <label for="InterestRate" class="form-label">Interest Rate (Annual)</label>
                                                                <input type="text" class="form-control" id="InterestRate" name="InterestRate" 
                                                                    value="<?php echo ($row['LoanType'] === 'Collateral') ? '14%' : '12%'; ?>" readonly>
                                                            </div>
                                                            
                                                            <!-- Amount Requested -->
                                                            <div class="mb-3">
                                                                <label for="AmountRequested" class="form-label">Amount Requested</label>
                                                                <input type="text" class="form-control" id="AmountRequested" name="AmountRequested" 
                                                                    value="₱<?php echo number_format($row['AmountRequested'], 2); ?>" readonly>
                                                            </div>
                                                            
                                                            <!-- Loan Term -->
                                                            <div class="mb-3">
                                                                <label for="LoanTerm" class="form-label">Loan Term (Months)</label>
                                                                <input type="number" class="form-control" id="LoanTerm" name="LoanTerm" 
                                                                    value="<?php echo htmlspecialchars($row['LoanTerm']); ?>" readonly>
                                                            </div>
                                                            
                                                            <!-- Total Amount Payable -->
                                                            <div class="mb-3">
                                                                <label for="TotalAmountPayable" class="form-label">Total Amount Payable</label>
                                                                <?php 
                                                                    $interestRate = ($row['LoanType'] === 'Collateral') ? 0.14 : 0.12;
                                                                    $loanTermMonths = $row['LoanTerm'];
                                                                    $monthlyInterestRate = $interestRate / 12;
                                                                    $interestForTerm = $row['AmountRequested'] * $monthlyInterestRate * $loanTermMonths;
                                                                    $totalPayable = $row['AmountRequested'] + $interestForTerm;
                                                                ?>
                                                                <input type="text" class="form-control" id="TotalAmountPayable" name="TotalAmountPayable" 
                                                                    value="₱<?php echo number_format($totalPayable, 2); ?>" readonly>
                                                            </div>
                                                            
                                                            <!-- Mode of Payment and Amount Payable -->
                                                            <div class="mb-3">
                                                                <label for="ModeOfPayment" class="form-label">Payment Mode</label>
                                                                <input type="text" class="form-control" id="ModeOfPayment" name="ModeOfPayment" 
                                                                    value="<?php echo htmlspecialchars($row['ModeOfPayment']); ?>" readonly>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="AmountPayable" class="form-label">Amount Payable per Payment</label>
                                                                <?php 
                                                                    $paymentFrequency = [
                                                                        'Weekly' => 4.33, // Approximating weeks in a month
                                                                        'Bi-Monthly' => 2,
                                                                        'Monthly' => 1,
                                                                        'Quarterly' => 1 / 4,
                                                                        'Semi Annual' => 1 / 6
                                                                    ];
                                                                    $numPayments = $loanTermMonths * (isset($paymentFrequency[$row['ModeOfPayment']]) ? $paymentFrequency[$row['ModeOfPayment']] : 1);
                                                                    $amountPerPayment = $totalPayable / $numPayments;
                                                                ?>
                                                                <input type="text" class="form-control" id="AmountPayable" name="AmountPayable" 
                                                                    value="₱<?php echo number_format($amountPerPayment, 2); ?>" readonly>
                                                            </div>
                                                            
                                                            <!-- Loanable Amount -->
                                                            <div class="mb-3">
                                                                <label for="LoanableAmount" class="form-label">Loanable Amount</label>
                                                                <input type="text" class="form-control" id="LoanableAmount" name="LoanableAmount" 
                                                                    value="₱<?php echo number_format($row['loanable_amount'], 2); ?>" readonly>
                                                            </div>
                                                            
                                                            <!-- Eligibility -->
                                                            <div class="mb-3">
                                                                <label for="Eligibility" class="form-label">Eligibility</label>
                                                                <input type="text" class="form-control" id="Eligibility" name="Eligibility" 
                                                                    value="<?php echo htmlspecialchars($row['Eligibility']); ?>" readonly>
                                                            </div>
                                                            
                                                            <!-- Status -->
                                                            <div class="mb-3">
                                                                <label for="Status" class="form-label">Status</label>
                                                                <select class="form-control" id="Status" name="Status" required>
                                                                    <option value="In Progress" <?php echo ($row['Status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                                    <option value="Cancelled" <?php echo ($row['Status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
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


                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal<?php echo $row['LoanID']; ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="viewModalLabel">Loan Application Details</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-4">
                                                            <!-- Basic Information -->
                                                            <div class="col-md-6">
                                                                <div class="card h-100">
                                                                    <div class="card-header bg-light">
                                                                        <h6 class="mb-0">Basic Information</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <p><strong>Loan ID:</strong> <?php echo htmlspecialchars($row['LoanID']); ?></p>
                                                                        <p><strong>Applicant Name:</strong> <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></p>
                                                                        <p><strong>Amount Requested:</strong> ₱<?php echo !empty($row['AmountRequested']) ? number_format((float)$row['AmountRequested'], 2) : '0.00'; ?></p>
                                                                        <p><strong>Loanable Amount:</strong> ₱<?php echo !empty($row['loanable_amount']) ? number_format((float)$row['loanable_amount'], 2) : '0.00'; ?></p>
                                                                        <p><strong>Mode of Payment:</strong> <?php echo htmlspecialchars($row['ModeOfPayment']); ?></p>
                                                                        <p><strong>Loan Term:</strong> <?php echo htmlspecialchars($row['LoanTerm']); ?></p>
                                                                        <p><strong>Net Family Income:</strong> ₱<?php echo !empty($row['net_family_income']) ? number_format((float)$row['net_family_income'], 2) : '0.00'; ?></p>
                                                                        <p><strong>Loan Type:</strong> <?php echo htmlspecialchars($row['LoanType']); ?></p>
                                                                        <p><strong>Date of Loan:</strong> <?php echo date('F d, Y', strtotime($row['DateOfLoan'])); ?></p>
                                                                        <p><strong>Eligibility:</strong> <?php echo htmlspecialchars($row['Eligibility']); ?></p>
                                                                        <p><strong>Status:</strong> <span class="badge badge-<?php echo getStatusBadgeClass($row['Status']); ?>"><?php echo htmlspecialchars($row['Status']); ?></span></p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Documents -->
                                                            <div class="col-md-6">
                                                                <div class="card h-100">
                                                                    <div class="card-header bg-light">
                                                                        <h6 class="mb-0">Documents</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="document-section">
                                                                            <h6 class="text-primary">Valid IDs & Personal Documents</h6>
                                                                            <?php displayDocument($row['valid_id_path'], 'Valid ID'); ?>
                                                                            <?php displayDocument($row['cedula_path'], 'Cedula'); ?>
                                                                        </div>
                                                                        
                                                                        <div class="document-section mt-3">
                                                                            <h6 class="text-primary">Property Documents</h6>
                                                                            <?php displayDocument($row['deed_of_sale_path'], 'Deed of Sale'); ?>
                                                                            <?php displayDocument($row['tax_declaration_path'], 'Tax Declaration'); ?>
                                                                            <?php displayDocument($row['tax_clearance_path'], 'Tax Clearance'); ?>
                                                                            <?php displayDocument($row['original_transfer_certificate_of_title_path'], 'Original Transfer Certificate'); ?>
                                                                        </div>
                                                                        
                                                                        <div class="document-section mt-3">
                                                                            <h6 class="text-primary">Vehicle Documents</h6>
                                                                            <?php displayDocument($row['vehicle_orcr_path'], 'Vehicle ORCR'); ?>
                                                                            <?php 
                                                                            for($i = 1; $i <= 4; $i++) {
                                                                                displayDocument($row["orcr_vehicle$i"], "ORCR Vehicle $i");
                                                                                displayDocument($row["deed_of_sale$i"], "Deed of Sale $i");
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                        
                                                                        <div class="document-section mt-3">
                                                                            <h6 class="text-primary">Additional Documents</h6>
                                                                            <?php displayDocument($row['proof_of_income_path'], 'Proof of Income'); ?>
                                                                            <?php displayDocument($row['certified_true_copy_path'], 'Certified True Copy'); ?>
                                                                            <?php displayDocument($row['vicinity_map_path'], 'Vicinity Map'); ?>
                                                                            <?php displayDocument($row['barangay_clearance_path'], 'Barangay Clearance'); ?>
                                                                            <?php displayDocument($row['post_dated_check_path'], 'Post Dated Check'); ?>
                                                                            <?php displayDocument($row['promisory_note_path'], 'Promissory Note'); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Personal Information -->
                                                        <div class="row mb-4">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-header bg-light">
                                                                        <h6 class="mb-0">Personal Information</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <p><strong>Years Stay at Present Address:</strong> <?php echo htmlspecialchars($row['years_stay_present_address']); ?></p>
                                                                                <p><strong>Own House:</strong> <?php echo htmlspecialchars($row['own_house']); ?></p>
                                                                                <p><strong>Renting:</strong> <?php echo htmlspecialchars($row['renting']); ?></p>
                                                                                <p><strong>Living with Relative:</strong> <?php echo htmlspecialchars($row['living_with_relative']); ?></p>
                                                                                <p><strong>Marital Status:</strong> <?php echo htmlspecialchars($row['marital_status']); ?></p>
                                                                                <?php if ($row['marital_status'] === 'Married' && !empty($row['spouse_name'])): ?>
                                                                                <p><strong>Spouse Name:</strong> <?php echo htmlspecialchars($row['spouse_name']); ?></p>
                                                                                 <?php endif; ?>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <p><strong>Number of Dependents:</strong> <?php echo htmlspecialchars($row['number_of_dependents']); ?></p>
                                                                                <p><strong>Dependents in School:</strong> <?php echo htmlspecialchars($row['dependents_in_school']); ?></p>
                                                                                <p><strong>Dependent 1 Name:</strong> <?php echo htmlspecialchars($row['dependent1_name']); ?></p>
                                                                                <p><strong>Dependent 1 Age:</strong> <?php echo htmlspecialchars($row['dependent1_age']); ?></p>
                                                                                <p><strong>Dependent 1 Grade Level:</strong> <?php echo htmlspecialchars($row['dependent1_grade_level']); ?></p>
                                                                                <p><strong>Dependent 2 Name:</strong> <?php echo htmlspecialchars($row['dependent2_name']); ?></p>
                                                                                <p><strong>Dependent 2 Age:</strong> <?php echo htmlspecialchars($row['dependent2_age']); ?></p>
                                                                                <p><strong>Dependent 2 Grade Level:</strong> <?php echo htmlspecialchars($row['dependent2_grade_level']); ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Employment Information -->
                                                        <div class="row mb-4">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-header bg-light">
                                                                        <h6 class="mb-0">Employment Information</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <p><strong>Employer Name:</strong> <?php echo htmlspecialchars($row['employer_name']); ?></p>
                                                                                <p><strong>Employer Address:</strong> <?php echo htmlspecialchars($row['employer_address']); ?></p>
                                                                                <p><strong>Present Position:</strong> <?php echo htmlspecialchars($row['present_position']); ?></p>
                                                                                <p><strong>Date of Employment:</strong> <?php echo date('F d, Y', strtotime($row['date_of_employment'])); ?></p>
                                                                                <p><strong>Monthly Income:</strong> ₱<?php echo !empty($row['monthly_income']) ? number_format((float)$row['monthly_income'], 2) : '0.00'; ?></p>
                                                                                <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($row['contact_person']); ?></p>
                                                                                <p><strong>Contact Telephone No:</strong> <?php echo htmlspecialchars($row['contact_telephone_no']); ?></p>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <p><strong>Self Employed Business Type:</strong> <?php echo htmlspecialchars($row['self_employed_business_type']); ?></p>
                                                                                <p><strong>Business Start Date:</strong> <?php echo !empty($row['business_start_date']) ? date('F d, Y', strtotime($row['business_start_date'])) : 'N/A'; ?></p>
                                                                                <p><strong>Other Income:</strong> <?php echo !empty($row['other_income']) ? htmlspecialchars($row['other_income']) : 'N/A'; ?></p>
                                                                                <p><strong>Self Other Income Amount:</strong> ₱<?php echo !empty($row['self_other_income_amount']) ? number_format((float)$row['self_other_income_amount'], 2) : '0.00'; ?></p>
                                                                                <?php if ($row['marital_status'] === 'Married'): ?>
                                                                                <?php if (!empty($row['spouse_name'])): ?>
                                                                                    <p><strong>Spouse Name:</strong> <?php echo htmlspecialchars($row['spouse_name']); ?></p>
                                                                                <?php endif; ?>
                                                                                <?php if (!empty($row['spouse_income'])): ?>
                                                                                    <p><strong>Spouse Income:</strong> <?php echo htmlspecialchars($row['spouse_income']); ?></p>
                                                                                <?php endif; ?>
                                                                                <?php if (!empty($row['spouse_income_amount'])): ?>
                                                                                    <p><strong>Spouse Income Amount:</strong> ₱<?php echo number_format((float)$row['spouse_income_amount'], 2); ?></p>
                                                                                <?php endif; ?>
                                                                                <?php if (!empty($row['spouse_other_income'])): ?>
                                                                                    <p><strong>Spouse Other Income:</strong> <?php echo htmlspecialchars($row['spouse_other_income']); ?></p>
                                                                                <?php endif; ?>
                                                                                <?php if (!empty($row['spouse_other_income_amount'])): ?>
                                                                                    <p><strong>Spouse Other Income Amount:</strong> ₱<?php echo number_format((float)$row['spouse_other_income_amount'], 2); ?></p>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Financial Information -->
                                                        <div class="row mb-4">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-header bg-light">
                                                                        <h6 class="mb-0">Financial Information</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                            <p><strong>Food & Groceries Expense:</strong> ₱<?php echo !empty($row['food_groceries_expense']) ? number_format((float)$row['food_groceries_expense'], 2) : '0.00'; ?></p>
                                                                            <p><strong>Gas & Oil Transportation Expense:</strong> ₱<?php echo !empty($row['gas_oil_transportation_expense']) ? number_format((float)$row['gas_oil_transportation_expense'], 2) : '0.00'; ?></p>
                                                                            <p><strong>Schooling Expense:</strong> ₱<?php echo !empty($row['schooling_expense']) ? number_format((float)$row['schooling_expense'], 2) : '0.00'; ?></p>
                                                                            <p><strong>Utilities Expense:</strong> ₱<?php echo !empty($row['utilities_expense']) ? number_format((float)$row['utilities_expense'], 2) : '0.00'; ?></p>
                                                                            <p><strong>Miscellaneous Expense:</strong> ₱<?php echo !empty($row['miscellaneous_expense']) ? number_format((float)$row['miscellaneous_expense'], 2) : '0.00'; ?></p>
                                                                            <p><strong>Total Expenses:</strong> ₱<?php echo !empty($row['total_expenses']) ? number_format((float)$row['total_expenses'], 2) : '0.00'; ?></p>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <p><strong>Savings Account:</strong> <?php echo htmlspecialchars($row['savings_account']); ?></p>
                                                                                <p><strong>Savings Bank:</strong> <?php echo htmlspecialchars($row['savings_bank']); ?></p>
                                                                                <p><strong>Savings Branch:</strong> <?php echo htmlspecialchars($row['savings_branch']); ?></p>
                                                                                <p><strong>Current Account:</strong> <?php echo htmlspecialchars($row['current_account']); ?></p>
                                                                                <p><strong>Current Bank:</strong> <?php echo htmlspecialchars($row['current_bank']); ?></p>
                                                                                <p><strong>Current Branch:</strong> <?php echo htmlspecialchars($row['current_branch']); ?></p>
                                                                                <p><strong>Assets:</strong> 
                                                                                    <?php 
                                                                                    for($i = 1; $i <= 4; $i++) {
                                                                                        if (!empty($row["assets$i"])) {
                                                                                            echo htmlspecialchars($row["assets$i"]) . "<br>";
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Collateral Information (Only show if LoanType is Collateral) -->
                                                            <?php if ($row['LoanType'] === 'Collateral' && !empty($row['LoanID'])): ?>
                                                            <div class="row mb-4">
                                                                <div class="col-md-12">
                                                                    <div class="card">
                                                                        <div class="card-header bg-light">
                                                                            <h6 class="mb-0">Collateral Information</h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <?php displayDocument($row['land_title_path'], 'Land Title'); ?>
                                                                                    <p><strong>Square Meters:</strong> <?php echo htmlspecialchars($row['square_meters']); ?></p>
                                                                                    <p><strong>Type of Land:</strong> <?php echo htmlspecialchars($row['type_of_land']); ?></p>
                                                                                    <p><strong>Location Name:</strong> <?php echo htmlspecialchars($row['location_name']); ?></p>
                                                                                    <p><strong>Right of Way:</strong> <?php echo htmlspecialchars($row['right_of_way']); ?></p>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <h6 class="text-primary">Nearby Amenities</h6>
                                                                                    <p><i class="fas fa-hospital text-primary mr-2"></i> Hospital: <?php echo htmlspecialchars($row['has_hospital']); ?></p>
                                                                                    <p><i class="fas fa-school text-primary mr-2"></i> School: <?php echo htmlspecialchars($row['has_school']); ?></p>
                                                                                    <p><i class="fas fa-clinic-medical text-primary mr-2"></i> Clinic: <?php echo htmlspecialchars($row['has_clinic']); ?></p>
                                                                                    <p><i class="fas fa-church text-primary mr-2"></i> Church: <?php echo htmlspecialchars($row['has_church']); ?></p>
                                                                                    <p><i class="fas fa-shopping-cart text-primary mr-2"></i> Market: <?php echo htmlspecialchars($row['has_market']); ?></p>
                                                                                    <p><i class="fas fa-bus text-primary mr-2"></i> Terminal: <?php echo htmlspecialchars($row['has_terminal']); ?></p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>

                                                        <!-- Creditors -->
                                                        <div class="row mb-4">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-header bg-light">
                                                                        <h6 class="mb-0">Creditors</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <?php for($i = 1; $i <= 4; $i++): ?>
                                                                                <?php if (!empty($row["creditor{$i}_name"])): ?>
                                                                                    <div class="col-md-6">
                                                                                        <p><strong>Creditor <?php echo $i; ?> Name:</strong> <?php echo htmlspecialchars($row["creditor{$i}_name"]); ?></p>
                                                                                        <p><strong>Creditor <?php echo $i; ?> Address:</strong> <?php echo htmlspecialchars($row["creditor{$i}_address"]); ?></p>
                                                                                        <p><strong>Creditor <?php echo $i; ?> Original Amount:</strong> ₱<?php echo !empty($row["creditor{$i}_original_amount"]) ? number_format((float)$row["creditor{$i}_original_amount"], 2) : '0.00'; ?></p>
                                                                                        <p><strong>Creditor <?php echo $i; ?> Present Balance:</strong> ₱<?php echo !empty($row["creditor{$i}_present_balance"]) ? number_format((float)$row["creditor{$i}_present_balance"], 2) : '0.00'; ?></p>
                                                                                        <?php if (!is_null($row['property_foreclosed_repossessed'])): ?>
                                                                                        <p><strong>Property Foreclosed/Repossessed:</strong> <?php echo ucfirst($row['property_foreclosed_repossessed']); ?></p>
                                                                                        <?php endif; ?>
                                                                                        <?php if (!is_null($row['co_maker_cosigner_guarantor'])): ?>
                                                                                            <p><strong>Co-Maker/Cosigner/Guarantor:</strong> <?php echo ucfirst($row['co_maker_cosigner_guarantor']); ?></p>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            <?php endfor; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- References -->
                                                        <div class="row mb-4">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-header bg-light">
                                                                        <h6 class="mb-0">References</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <?php for($i = 1; $i <= 3; $i++): ?>
                                                                                <?php if (!empty($row["reference{$i}_name"])): ?>
                                                                                    <div class="col-md-4">
                                                                                        <p><strong>Reference <?php echo $i; ?> Name:</strong> <?php echo htmlspecialchars($row["reference{$i}_name"]); ?></p>
                                                                                        <p><strong>Reference <?php echo $i; ?> Address:</strong> <?php echo htmlspecialchars($row["reference{$i}_address"]); ?></p>
                                                                                        <p><strong>Reference <?php echo $i; ?> Contact No:</strong> <?php echo htmlspecialchars($row["reference{$i}_contact_no"]); ?></p>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            <?php endfor; ?>
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
                                                <td colspan="7">No loan applications found</td>
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

            <?php
            include '../connection/config.php';

            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $sql = "SELECT 
                    c.LoanID,
                    c.MemberID,
                    c.AmountRequested,
                    c.loanable_amount,
                    c.ModeOfPayment,
                    c.LoanTerm,
                    c.LoanEligibility,
                    c.PayableAmount,
                    c.PayableDate,
                    c.NextPayableAmount,
                    c.NextPayableDate,
                    c.LoanType,
                    c.MaturityDate,
                    c.ApprovalStatus,
                    u.first_name,
                    u.last_name
                    FROM credit_history c
                    JOIN users u ON c.MemberID = u.user_id
                    WHERE c.LoanID LIKE ? 
                    OR u.first_name LIKE ?
                    OR u.last_name LIKE ?
                    OR c.LoanType LIKE ?
                    OR c.ApprovalStatus LIKE ?
                    LIMIT ? OFFSET ?";

            $stmt = $conn->prepare($sql);
            $search_param = "%" . $search . "%";
            $stmt->bind_param("sssssii", 
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

            $count_sql = "SELECT COUNT(*) as total 
                        FROM credit_history c
                        JOIN users u ON c.MemberID = u.user_id
                        WHERE c.LoanID LIKE ? 
                        OR u.first_name LIKE ?
                        OR u.last_name LIKE ?
                        OR c.LoanType LIKE ?
                        OR c.ApprovalStatus LIKE ?";

            $count_stmt = $conn->prepare($count_sql);
            $count_stmt->bind_param("sssss", 
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
            ?>

            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="card-title mb-0">Active Loan Applications</p>
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
                                            <th>Applicant Name</th>
                                            <th>Amount Requested</th>
                                            <th>Loanable Amount</th>
                                            <th>Loan Type</th>
                                            <th>Loan Term</th>
                                            <th>Mode Of Payment</th>
                                            <th>Date of Loan</th>
                                            <th>PayableAmount</th>
                                            <th>PayableDate</th>
                                            <th>NextPayableAmount</th>
                                            <th>NextPayableDate</th>
                                            <th>ApprovalStatus</th>
                                            <th>Eligibility</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['LoanID']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['MemberID']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                                    <td>₱<?php echo htmlspecialchars($row['AmountRequested']); ?></td>
                                                    <td>₱<?php echo htmlspecialchars($row['loanable_amount']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['LoanType']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['LoanTerm']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['ModeOfPayment']); ?></td>
                                                    <td><?php echo date('F d, Y', strtotime($row['MaturityDate'])); ?></td>
                                                    <td>₱<?php echo htmlspecialchars($row['PayableAmount']); ?></td>
                                                    <td><?php echo date('F d, Y', strtotime($row['PayableDate'])); ?></td>
                                                    <td>₱<?php echo htmlspecialchars($row['NextPayableAmount']); ?></td>
                                                    <td><?php echo date('F d, Y', strtotime($row['NextPayableDate'])); ?></td>
                                                    <td><?php echo htmlspecialchars($row['ApprovalStatus']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['LoanEligibility']); ?></td>
                                                    <td>
                                                       
                                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewModal2<?php echo $row['LoanID']; ?>">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                        
                                                    </td>
                                                </tr>
                                                
                                                

                                                <!-- View Modal -->
                                                <div class="modal fade" id="viewModal2<?php echo $row['LoanID']; ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="viewModalLabel">Active Loan Application Details</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <p><strong>Loan ID:</strong> <?php echo htmlspecialchars($row['LoanID']); ?></p>
                                                                        <p><strong>Applicant Name:</strong> <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></p>
                                                                        <p><strong>Amount Requested:</strong> ₱<?php echo !empty($row['AmountRequested']) ? number_format((float)$row['AmountRequested'], 2) : '0.00'; ?></p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p><strong>Loan Type:</strong> <?php echo htmlspecialchars($row['LoanType']); ?></p>
                                                                        <p><strong>Date of Loan:</strong> <?php echo date('F d, Y', strtotime($row['DateOfLoan'])); ?></p>
                                                                        <p><strong>Status:</strong> <?php echo htmlspecialchars($row['Status']); ?></p>
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
                                                <td colspan="7">No loan applications found</td>
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
            <?php
            // Helper function for status badge
            function getStatusBadgeClass($status) {
                return match($status) {
                    'Approved' => 'success',
                    'Disapproved' => 'danger',
                    'In Progress' => 'warning',
                    default => 'secondary'
                };
            }

            // Updated document display function with correct path
            function displayDocument($path, $label) {
                if (!empty($path)) {
                    $fullPath = "../dist/assets/images/proofs/" . $path;
                    $fileExtension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    $icon = $fileExtension === 'pdf' ? 'fa-file-pdf' : 'fa-file-image';
                    
                    echo "<div class='document-item d-flex align-items-center'>";
                    echo "<i class='fas {$icon} text-primary mr-2'></i>";
                    
                    // Preview button for images
                    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        echo "<a href='#' onclick='previewImage(\"{$fullPath}\", \"{$label}\")' class='text-decoration-none mr-2'>{$label}</a>";
                        echo "<button class='btn btn-sm btn-outline-primary ml-auto' onclick='previewImage(\"{$fullPath}\", \"{$label}\")'>";
                        echo "<i class='fas fa-eye'></i> View";
                        echo "</button>";
                    } 
                    // Download button for PDFs
                    else if ($fileExtension === 'pdf') {
                        echo "<a href='{$fullPath}' target='_blank' class='text-decoration-none mr-2'>{$label}</a>";
                        echo "<a href='{$fullPath}' download class='btn btn-sm btn-outline-primary ml-auto'>";
                        echo "<i class='fas fa-download'></i> Download";
                        echo "</a>";
                    }
                    
                    echo "</div>";
                }
            }
            ?>

            <script>
            
            function previewImage(path, label) {
                Swal.fire({
                    title: label,
                    imageUrl: path,
                    imageAlt: label,
                    width: '90%', // Increased modal width
                    confirmButtonText: 'Close',
                    showDownloadButton: true,
                    imageWidth: 'auto', // Let image determine width
                    imageHeight: 'auto',
                    customClass: {
                        image: 'swal-image-sharp', // Custom class for image
                        popup: 'swal-popup-large' // Custom class for popup
                    },
                    didOpen: (popup) => {
                        // Ensure image loads at full resolution
                        const img = popup.querySelector('.swal2-image');
                        if (img) {
                            img.style.maxWidth = '100%';
                            img.style.objectFit = 'contain';
                            img.style.imageRendering = '-webkit-optimize-contrast'; // Improve image rendering
                        }
                    }
                });
            }

            // Add these styles to your existing <style> block
            const styles = `
            .swal-image-sharp {
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
                -ms-interpolation-mode: nearest-neighbor;
                max-width: 100% !important;
                margin: 0 auto;
            }

            .swal-popup-large {
                max-width: 1200px; /* or your preferred max width */
            }

            .swal2-modal {
                padding: 1rem !important;
            }

            .swal2-image {
                border-radius: 4px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }

            /* Ensure container doesn't restrict image size */
            .document-item img {
                max-width: none;
                width: auto;
                height: auto;
            }
            `;

            // Inject styles
            const styleSheet = document.createElement("style");
            styleSheet.textContent = styles;
            document.head.appendChild(styleSheet);
            </script>

            <style>
            .document-item {
                padding: 8px;
                border-bottom: 1px solid #eee;
                transition: background-color 0.2s;
            }

            .document-item:hover {
                background-color: #f8f9fa;
            }

            .document-item:last-child {
                border-bottom: none;
            }

            .document-section {
                margin-bottom: 15px;
            }

            .modal-lg {
                max-width: 80%;
            }

            .badge {
                padding: 8px 12px;
                font-size: 0.9em;
                color: white !important;
            }

            .document-item .btn {
                opacity: 0.8;
                transition: opacity 0.2s;
            }

            .document-item:hover .btn {
                opacity: 1;
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
    <script src="loan_prediction.js"></script>
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