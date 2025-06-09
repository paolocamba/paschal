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
    <title>Appointments | Member</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../dist/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <!--FONTAWESOME CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
    <style>
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
            border-color: #00563B !important ;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .navbar {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
        
        /* Added space above pagination */
        .pagination-container {
            margin-top: 30px;
        }
    </style>
  </head>
  <body>
    <div class="container-scroller">
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
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <i class="fa-solid fa-house"></i>
                <span class="menu-title">Home</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="home.php">
                <i class="fa-solid fa-gauge"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="services.php">
                <i class="fa-brands fa-slack"></i>
                <span class="menu-title">Services</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="appointments.php">
                <i class="fa-solid fa-calendar"></i>
                <span class="menu-title">Appointments</span>
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
                          title: "Operation Successful",
                          showConfirmButton: false,
                          timer: 1500
                      });
                  });
              </script>';
            }
            ?>

            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                      <p class="card-title mb-0">My Appointments</p>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-striped table-borderless">
                        <thead>
                          <tr>
                            <th>Description</th>
                            <th>Appointment Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          // Pagination for appointments
                          $appointment_page = isset($_GET['appointment_page']) ? (int)$_GET['appointment_page'] : 1;
                          $appointment_limit = 5;
                          $appointment_offset = ($appointment_page - 1) * $appointment_limit;
                          
                          // First get all pending appointments (they always show at the top)
                          $pending_sql = "SELECT * FROM appointments WHERE user_id = ? AND status = 'Pending' ORDER BY appointmentdate DESC";
                          $pending_stmt = $conn->prepare($pending_sql);
                          $pending_stmt->bind_param("s", $id);
                          $pending_stmt->execute();
                          $pending_result = $pending_stmt->get_result();
                          
                          // Then get non-pending appointments with pagination
                          $other_sql = "SELECT * FROM appointments WHERE user_id = ? AND status != 'Pending' ORDER BY appointmentdate DESC LIMIT ? OFFSET ?";
                          $other_stmt = $conn->prepare($other_sql);
                          $other_stmt->bind_param("sii", $id, $appointment_limit, $appointment_offset);
                          $other_stmt->execute();
                          $other_result = $other_stmt->get_result();
                          
                          // Count total non-pending appointments for pagination
                          $count_appointment_sql = "SELECT COUNT(*) as total FROM appointments WHERE user_id = ? AND status != 'Pending'";
                          $count_appointment_stmt = $conn->prepare($count_appointment_sql);
                          $count_appointment_stmt->bind_param("s", $id);
                          $count_appointment_stmt->execute();
                          $count_appointment_result = $count_appointment_stmt->get_result();
                          $total_appointments = $count_appointment_result->fetch_assoc()['total'];
                          $total_appointment_pages = ceil($total_appointments / $appointment_limit);
                          
                          // Display pending appointments first
                          if ($pending_result->num_rows > 0): ?>
                            <?php while($appointment = $pending_result->fetch_assoc()): ?>
                              <tr>
                                <td><?php echo htmlspecialchars($appointment['description']); ?></td>
                                <td><?php echo date('F d, Y', strtotime($appointment['appointmentdate'])); ?></td>
                                <td>
                                  <span class="badge badge-warning">
                                    <?php echo htmlspecialchars($appointment['status']); ?>
                                  </span>
                                </td>
                                <td>
                                  <button class="btn btn-danger btn-sm cancel-appointment" 
                                    data-id="<?php echo $appointment['id']; ?>">
                                    Cancel
                                  </button>
                                </td>
                              </tr>
                            <?php endwhile; ?>
                          <?php endif; ?>
                          
                          <?php 
                          // Then display other appointments
                          if ($other_result->num_rows > 0): ?>
                            <?php while($appointment = $other_result->fetch_assoc()): ?>
                              <tr>
                                <td><?php echo htmlspecialchars($appointment['description']); ?></td>
                                <td><?php echo date('F d, Y', strtotime($appointment['appointmentdate'])); ?></td>
                                <td>
                                  <span class="badge 
                                    <?php 
                                    switch($appointment['status']) {
                                      case 'Approved': echo 'badge-success'; break;
                                      case 'Disapproved': echo 'badge-danger'; break;
                                      case 'Cancelled': echo 'badge-secondary'; break;
                                      default: echo 'badge-secondary';
                                    }
                                    ?>">
                                    <?php echo htmlspecialchars($appointment['status']); ?>
                                  </span>
                                </td>
                                <td>N/A</td>
                              </tr>
                            <?php endwhile; ?>
                          <?php elseif ($pending_result->num_rows == 0): ?>
                            <tr>
                              <td colspan="4">No appointments found</td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>
                    <!-- Appointments Pagination -->
                    <div class="pagination-container">
                      <nav aria-label="Appointments Page navigation">
                        <ul class="pagination justify-content-center">
                          <li class="page-item <?php echo ($appointment_page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?appointment_page=<?php echo $appointment_page - 1; ?>" aria-label="Previous">
                              <span aria-hidden="true">&laquo; </span>
                            </a>
                          </li>
                          <?php for ($i = 1; $i <= $total_appointment_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $appointment_page) ? 'active' : ''; ?>">
                              <a class="page-link" href="?appointment_page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                          <?php endfor; ?>
                          <li class="page-item <?php echo ($appointment_page >= $total_appointment_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?appointment_page=<?php echo $appointment_page + 1; ?>" aria-label="Next">
                              <span aria-hidden="true"> &raquo;</span>
                            </a>
                          </li>
                        </ul>
                      </nav>
                    </div>
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
        // Appointment cancellation
        $(document).on('click', '.cancel-appointment', function() {
          var appointmentId = $(this).data('id');
          
          Swal.fire({
            title: 'Are you sure?',
            text: "You want to cancel this appointment?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!'
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: 'cancel_appointment.php',
                type: 'POST',
                dataType: 'json',
                data: { id: appointmentId },
                success: function(response) {
                  if (response.success) {
                    Swal.fire(
                      'Cancelled!',
                      'Your appointment has been cancelled.',
                      'success'
                    ).then(() => {
                      location.reload();
                    });
                  } else {
                    Swal.fire(
                      'Error!',
                      response.message || 'Failed to cancel appointment.',
                      'error'
                    );
                  }
                },
                error: function() {
                  Swal.fire(
                    'Error!',
                    'Failed to cancel appointment.',
                    'error'
                  );
                }
              });
            }
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
      <!-- End custom js for this page-->
  </body>
</html>