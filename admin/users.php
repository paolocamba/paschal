

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
    <title>Users | Admin</title>
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
            
            .nav-link i{
                margin-right: 10px;
            }
            .btn-primary{
                background-color:#03C03C !important;
            }
            .btn-primary:hover{
                background-color: #00563B !important;
            }
            .btn-outline-primary:hover{
                background-color: #00563B !important;
            }
            .page-item.active .page-link{
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
                        title: "Users Added Successfully",
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
                            title: "Users Updated Successfully",
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
                                title: "Users Deleted Successfully",
                                showConfirmButton: false,
                                timer: 1500 // Close after 1.5 seconds
                            });
                        });
                    </script>';
                    }
                   
             ?>

              <!-- Add Staff Modal -->
<div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel" aria-hidden="true">
    <div class="modal-dialog lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usersModalLabel">Add Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="add_staff.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="member_email">Select Member</label>
                        <select class="form-control select2" name="member_email" id="member_select" required>
                            <option value="">Select a member...</option>
                            <?php
                            // Fetch existing members (user_type = 'Member')
                            $member_sql = "SELECT id, email, CONCAT(first_name, ' ', last_name) AS full_name 
                                          FROM users 
                                          WHERE user_type = 'Member' 
                                          ORDER BY full_name";
                            $member_result = $conn->query($member_sql);
                            
                            if ($member_result->num_rows > 0) {
                                while($member = $member_result->fetch_assoc()) {
                                    echo '<option value="'.$member['email'].'">'.$member['full_name'].' ('.$member['email'].')</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user_type">Staff Role</label>
                        <select class="form-control" name="user_type" required>
                            <option value="Admin">Admin</option>
                            <option value="Membership Officer">Membership Officer</option>
                            <option value="Loan Officer">Loan Officer</option>
                            <option value="Liaison Officer">Liaison Officer</option>
                            <option value="Cashier">Cashier</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Assign Staff Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
            <?php
                include '../connection/config.php';

                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = 10;
                $offset = ($page - 1) * $limit;

                // Fetch users with user_type admin or subadmin, regardless of status, with search and pagination
                $sql = "SELECT id, first_name, last_name, email, username, mobile, street, barangay, municipality, province, user_type, created_at 
                        FROM users
                        WHERE user_type IN ('Admin','Membership Officer','Loan Officer','Liaison Officer','Cashier') AND (
                            first_name LIKE ? OR 
                            last_name LIKE ? OR 
                            email LIKE ? OR 
                            username LIKE ? OR
                            mobile LIKE ? OR 
                            street LIKE ? OR 
                            barangay LIKE ? OR 
                            municipality LIKE ? OR 
                            province LIKE ? OR 
                            created_at LIKE ?
                        )
                        LIMIT ? OFFSET ?";
                $stmt = $conn->prepare($sql);
                $search_param = "%" . $search . "%";
                $stmt->bind_param("ssssssssssii", $search_param, $search_param, $search_param, $search_param, $search_param, $search_param, $search_param, $search_param, $search_param, $search_param, $limit, $offset);
                $stmt->execute();
                $result = $stmt->get_result();

                // Count total records for pagination
                $count_sql = "SELECT COUNT(*) as total 
                            FROM users 
                            WHERE user_type IN ('User') AND (
                                first_name LIKE ? OR 
                                last_name LIKE ? OR 
                                email LIKE ? OR 
                                username LIKE ? OR 
                                mobile LIKE ? OR 
                                street LIKE ? OR 
                                barangay LIKE ? OR 
                                municipality LIKE ? OR 
                                province LIKE ? OR 
                                created_at LIKE ?
                            )";
                $count_stmt = $conn->prepare($count_sql);
                $count_stmt->bind_param("ssssssssss", $search_param, $search_param, $search_param, $search_param, $search_param, $search_param, $search_param,$search_param, $search_param, $search_param);
                $count_stmt->execute();
                    $count_result = $count_stmt->get_result();
                    $total_rows = $count_result->fetch_assoc()['total'];
                    $total_pages = ceil($total_rows / $limit);
                ?>

                <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <p class="card-title mb-0">Staff List</p>
                    <div class="ml-auto">
                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#usersModal"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
                <div class="mb-3">
                    <form method="GET" action="" class="form-inline" id="searchForm">
                        <div class="input-group mb-2 mr-sm-2">
                            <input type="text" name="search" id="searchInput" class="form-control" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Staff">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" id="clearButton" style="padding:10px;">&times;</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Search</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['user_type']); ?></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?php echo $row['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></button>
                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal<?php echo $row['id']; ?>"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit User Role</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="edit_users.php" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <div class="form-group">
                                                            <label>Full Name</label>
                                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="user_type">User Role</label>
                                                            <select class="form-control" name="user_type" required>
                                                                <option value="Member">Member (Remove Staff Access)</option>
                                                                <option value="Membership Officer" <?php echo ($row['user_type'] == 'Membership Officer') ? 'selected' : ''; ?>>Membership Officer</option>
                                                                <option value="Loan Officer" <?php echo ($row['user_type'] == 'Loan Officer') ? 'selected' : ''; ?>>Loan Officer</option>
                                                                <option value="Liaison Officer" <?php echo ($row['user_type'] == 'Liaison Officer') ? 'selected' : ''; ?>>Liaison Officer</option>
                                                                <option value="Cashier" <?php echo ($row['user_type'] == 'Cashier') ? 'selected' : ''; ?>>Cashier</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="deleteModalLabel">Delete Users</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="delete_users.php" method="POST">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                        <p>Are you sure you want to delete this users?</p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   

                                                                                 <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No staff members found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
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
$(document).ready(function() {
        // Disable past dates
        var today = new Date().toISOString().split('T')[0];
        $('#announcementDate').attr('min', today);

        // Capture the day of the selected date
        $('#announcementDate').change(function() {
            var selectedDate = $(this).val();
            var dateObj = new Date(selectedDate);
            var options = { weekday: 'long' }; // Get full weekday name
            var dayOfWeek = dateObj.toLocaleDateString('en-PH', options);
            $('#announcementDay').val(dayOfWeek); // Set the day value in the hidden input
        });
    });
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('currentImagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select a member...",
        allowClear: true
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