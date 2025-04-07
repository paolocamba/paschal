

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
    <title>Loan Application | Loan Officer</title>
      <!-- plugins:css -->
      <link rel="stylesheet" href="../dist/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <!--FONTAWESOME CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <a class="navbar-brand brand-logo me-5" href="index.php"><img src="../dist/assets/images/logo.png" class="me-2" alt="logo" /></a>
    <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/logo.png" alt="logo" /></a>
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
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
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
            // Check if the "success" parameter is set in the URL and show a success alert
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                $prediction = isset($_GET['prediction']) ? $_GET['prediction'] : null;
                $status = isset($_GET['status']) ? $_GET['status'] : null;

                // Only show alert if prediction and status are set
                if ($prediction !== null && $status !== null) {
                    $alert_message = "Predicted Loanable Amount: ₱" . number_format($prediction, 2) . "\n";
                    $alert_message .= "Status: " . ($status === 'Approved' ? 'Eligible' : 'Not Eligible');

                    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                    echo "<script>
                        Swal.fire({
                            title: 'Loan Processed',
                            text: `$alert_message`,
                            icon: 'success'
                        }).then((result) => {
                            window.location.href = 'loan.php?success=1';
                        });
                    </script>";
                }
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
                u.first_name,
                u.last_name
                FROM loanapplication l
                JOIN users u ON l.userID = u.user_id
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
                                                                <div id="prediction_details_<?php echo $row['LoanID']; ?>" style="display:none;" class="alert alert-info">
                                                                    <h6>Loan Analysis Results:</h6>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <p><strong>Loan Eligibility:</strong> <span id="loan_eligibility_<?php echo $row['LoanID']; ?>"></span></p>
                                                                            <p><strong>Interest Rate:</strong> <span id="interest_rate_<?php echo $row['LoanID']; ?>"></span></p>
                                                                            <?php if ($row['LoanType'] === 'Collateral'): ?>
                                                                            <p><strong>Total Value:</strong> <span id="total_value_<?php echo $row['LoanID']; ?>"></span></p>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <p><strong>Monthly Payment:</strong> <span id="periodic_payment_<?php echo $row['LoanID']; ?>"></span></p>
                                                                            <p><strong>Total Payment:</strong> <span id="total_payment_<?php echo $row['LoanID']; ?>"></span></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <form action="edit_loan.php" method="POST">
                                                                    <input type="hidden" name="LoanID" value="<?php echo $row['LoanID']; ?>">
                                                                    
                                                                    <div class="mb-3">
                                                                        <label for="loanable_amount_<?php echo $row['LoanID']; ?>" class="form-label">Loanable Amount</label>
                                                                        <input type="number" class="form-control" id="loanable_amount_<?php echo $row['LoanID']; ?>" 
                                                                            name="loanable_amount" value="<?php echo htmlspecialchars($row['loanable_amount']); ?>" readonly>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="Status" class="form-label">Status</label>
                                                                        <select class="form-control" id="Status" name="Status" required>
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

                                                <!-- View Modal -->
                                                <div class="modal fade" id="viewModal<?php echo $row['LoanID']; ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="viewModalLabel">Loan Application Details</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <p><strong>Loan ID:</strong> <?php echo htmlspecialchars($row['LoanID']); ?></p>
                                                                        <p><strong>Applicant Name:</strong> <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></p>
                                                                        <p><strong>Amount Requested:</strong>₱ <?php echo htmlspecialchars($row['AmountRequested']); ?></p>
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
                                                       
                                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewModal<?php echo $row['LoanID']; ?>">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                
                                                

                                                <!-- View Modal -->
                                                <div class="modal fade" id="viewModal<?php echo $row['LoanID']; ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
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
                                                                        <p><strong>Amount Requested:</strong> <?php echo htmlspecialchars($row['AmountRequested']); ?></p>
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
        
           <br><br><br><br><br><br><br>
              
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024-2050. <a href="" target="_blank">Paschal</a>. All rights reserved.</span>
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