

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
    <title>Land Appraisal | Admin</title>
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
    <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/pmpc-logo.png" alt="logo" /></a>
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
            .btn-info{
                background-color: #03C03C !important;
            }
            .btn-info:hover{
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
            include '../connection/config.php';

            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $sql = "SELECT 
            l.*,
            la.*,
            u.first_name,
            u.last_name
            FROM credit_history l
            JOIN land_appraisal la ON l.LoanID = la.LoanID
            JOIN users u ON l.MemberID = u.user_id
            WHERE l.LoanID LIKE ? 
            OR u.first_name LIKE ?
            OR u.last_name LIKE ?
            OR l.LoanType LIKE ?
            OR l.ApprovalStatus LIKE ?
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
                        FROM credit_history l
                        JOIN land_appraisal la ON l.LoanID = la.LoanID
                        JOIN users u ON l.MemberID = u.user_id
                        WHERE l.LoanID LIKE ? 
                        OR u.first_name LIKE ?
                        OR u.last_name LIKE ?
                        OR l.LoanType LIKE ?
                        OR l.ApprovalStatus LIKE ?";

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
                                <p class="card-title mb-0">Land Appraisal List</p>
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
                                            <th>Loanable Amount</th>
                                            <th>Square Meters</th>
                                            <th>Type of Land</th>
                                            <th>Location</th>
                                            <th>Final Zonal Value</th>
                                            <th>EMV per sqm</th>
                                            <th>Total Value</th>
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
                                                    <td><?php echo number_format($row['loanable_amount'], 2); ?></td>
                                                    <td><?php echo htmlspecialchars($row['square_meters']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['type_of_land']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['location_name']); ?></td>
                                                    <td><?php echo number_format($row['final_zonal_value'], 2); ?></td>
                                                    <td><?php echo number_format($row['EMV_per_sqm'], 2); ?></td>
                                                    <td><?php echo number_format($row['total_value'], 2); ?></td>
                                                    <td>
                                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewModal<?php echo $row['LoanID']; ?>">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- View Modal -->
                                                <div class="modal fade" id="viewModal<?php echo $row['LoanID']; ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title" id="viewModalLabel">
                                                                    <i class="fas fa-file-contract me-2"></i>
                                                                    Land Loan Application Details
                                                                </h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="card shadow-sm mb-3">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Property Information</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <p><strong>Loan ID:</strong> <?php echo htmlspecialchars($row['LoanID']); ?></p>
                                                                                <p><strong>Square Meters:</strong> <?php echo htmlspecialchars($row['square_meters']); ?></p>
                                                                                <p><strong>Type of Land:</strong> <?php echo htmlspecialchars($row['type_of_land']); ?></p>
                                                                                <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location_name']); ?></p>
                                                                                <p><strong>Final Zonal Value:</strong> ₱<?php echo number_format($row['final_zonal_value'], 2); ?></p>
                                                                                <p><strong>EMV per sqm:</strong> ₱<?php echo number_format($row['EMV_per_sqm'], 2); ?></p>
                                                                                <p><strong>Total Value:</strong> ₱<?php echo number_format($row['total_value'], 2); ?></p>
                                                                                <p><strong>Loanable Amount:</strong> ₱<?php echo number_format($row['loanable_amount'], 2); ?></p>
                                                                                <p><strong>Validation Status:</strong> 
                                                                                <?php if (!empty($row['validated_date'])): ?>
                                                                                    <span class="text-success">Validated on <?php echo htmlspecialchars($row['validated_date']); ?></span>
                                                                                <?php else: ?>
                                                                                    <span class="text-danger">Not yet validated by Liaison Officer</span>
                                                                                <?php endif; ?>
                                                                            </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="card shadow-sm mb-3">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Nearby Establishments</h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <div class="d-flex justify-content-between mb-2">
                                                                                    <span><i class="fas fa-road me-2"></i>Right of Way:</span>
                                                                                    <span class="badge <?php echo $row['right_of_way'] === 'Yes' ? 'badge-success' : 'badge-danger'; ?>" style="color:white !important;">
                                                                                        <?php echo htmlspecialchars($row['right_of_way']); ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="d-flex justify-content-between mb-2">
                                                                                    <span><i class="fas fa-hospital me-2"></i>Hospital:</span>
                                                                                    <span class="badge <?php echo $row['has_hospital'] === 'Yes' ? 'badge-success' : 'badge-danger'; ?>" style="color:white !important;">
                                                                                        <?php echo htmlspecialchars($row['has_hospital']); ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="d-flex justify-content-between mb-2">
                                                                                    <span><i class="fas fa-school me-2"></i>School:</span>
                                                                                    <span class="badge <?php echo $row['has_school'] === 'Yes' ? 'badge-success' : 'badge-danger'; ?>" style="color:white !important;">
                                                                                        <?php echo htmlspecialchars($row['has_school']); ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="d-flex justify-content-between mb-2">
                                                                                    <span><i class="fas fa-shopping-cart me-2"></i>Market:</span>
                                                                                    <span class="badge <?php echo $row['has_market'] === 'Yes' ? 'badge-success' : 'badge-danger'; ?>" style="color:white !important;">
                                                                                        <?php echo htmlspecialchars($row['has_market']); ?>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="d-flex justify-content-between">
                                                                                    <span><i class="fas fa-church me-2"></i>Church:</span>
                                                                                    <span class="badge <?php echo $row['has_church'] === 'Yes' ? 'badge-success' : 'badge-danger'; ?>" style="color:white !important;">
                                                                                        <?php echo htmlspecialchars($row['has_church']); ?>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                // Create an array of all image paths
                                                                $property_images = [];
                                                                if (!empty($row['image_path1'])) $property_images[] = $row['image_path1'];
                                                                if (!empty($row['image_path2'])) $property_images[] = $row['image_path2'];
                                                                if (!empty($row['image_path3'])) $property_images[] = $row['image_path3'];

                                                                // Only show the section if there are any images
                                                                if (!empty($property_images)): ?>
                                                                    <div class="card shadow-sm">
                                                                        <div class="card-header bg-light">
                                                                            <h6 class="mb-0"><i class="fas fa-images me-2"></i>Property Images</h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <?php foreach ($property_images as $image_path): ?>
                                                                                    <div class="col-md-4 mb-3">
                                                                                        <div class="property-image-container">
                                                                                            <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                                                                                class="img-fluid rounded" 
                                                                                                alt="Property Image"
                                                                                                style="max-height: 200px; width: 100%; object-fit: cover;">
                                                                                            <div class="image-actions mt-2 text-center">
                                                                                                <a href="<?php echo htmlspecialchars($image_path); ?>" 
                                                                                                class="btn btn-sm btn-primary" 
                                                                                                target="_blank"
                                                                                                data-bs-toggle="tooltip" 
                                                                                                title="View Full Image">
                                                                                                    <i class="fas fa-expand"></i>
                                                                                                </a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endforeach; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
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
                                                <td colspan="11">No land appraisal found</td>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchForm = document.getElementById('searchForm');
    var searchInput = document.getElementById('searchInput');
    var clearButton = document.getElementById('clearButton');

    clearButton.addEventListener('click', function() {
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