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
  <title>Dashboard | Member</title>
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
      <a class="navbar-brand brand-logo me-5" href="index.php"><img src="../dist/assets/images/logo.png" class="me-2"
          alt="logo" /></a>
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
          
        </style>
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
                <li class="nav-item">
                    <a class="nav-link" href="appointments.php">
                    <i class="fa-solid fa-calendar"></i>
                    <span class="menu-title">Appointments</span>
                    </a>
                </li>
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
                <h3 class="font-weight-bold">Welcome <?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
                <h3 class="font-weight-bold"> <?php echo htmlspecialchars($_SESSION['membership_type']); ?> <span
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
            <div class="card" style="background:rgb(1, 120, 36);">
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
            $user_id = $_SESSION['user_id'];

            // Query to count active loans and loan applications for the logged-in user
            $status_sql = "
                SELECT 
                    (SELECT COUNT(*) 
                    FROM `credit_history` 
                    WHERE `ApprovalStatus` = 'Approved' AND `MemberID` = '$user_id') as active_loans_count,
                    (SELECT COUNT(*) 
                    FROM `loanapplication` 
                    WHERE `userID` = '$user_id') as total_loan_applications_count
            ";

            $result = mysqli_query($conn, $status_sql);
            $row = mysqli_fetch_assoc($result);
            ?>
            <div class="col-md-6 grid-margin transparent">
                <div class="row">
                    <div class="col-md-6 mb-4 stretch-card transparent">
                        <div class="card" style="background: rgb(1, 120, 36);">
                            <div class="card-body">
                                <p class="mb-4 text-white">Total Number of Active Loans</p>
                                <p class="fs-30 mb-2 text-white"><?php echo $row['active_loans_count']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4 stretch-card transparent">
                        <div class="card" style="background: rgb(1, 120, 36)">
                            <div class="card-body">
                                <p class="mb-4 text-white">Total Number of Loan Applications</p>
                                <p class="fs-30 mb-2 text-white"><?php echo $row['total_loan_applications_count']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
         
          </div>
          <style>

            
            .container-upcoming {
              max-width: 1400px;
              padding: 0 30px;
              display: flex;
              flex-direction: column;
              align-items: center;
            }

            .row {
              justify-content: center;
              /* Aligns items horizontally in the center */
            }

            .card-body-compact {
              padding: 20px;
              background-color: #ffffff;
              text-align: center;
              /* Ensures text is centered inside the card */
            }

            .card-title,
            .card-text {
              text-align: center;
              /* Centers the text content */
            }

            h2 {
              color: #2c3e50;
              font-weight: 700;
              text-align: center;
              /* Centers the title */
              position: relative;
              margin-bottom: 40px;
            }

            .navbar {
              padding-top: 0 !important;
              margin-top: 0 !important;
            }



            h2::after {
              content: '';
              position: absolute;
              bottom: -10px;
              left: 50%;
              transform: translateX(-50%);
              width: 100px;
              height: 4px;
              background-color: #3498db;
            }

            @media (max-width: 768px) {
              .container-fluid {
                padding: 0 15px;
              }

              .card-img-top {
                height: 200px;
              }
            }
          </style>
          <div class="container-upcoming mt-4">
            <div class="row">
              <div class="col-12">
                <h2 class="text-center mb-4">Announcements</h2>
              </div>
              <?php
              require_once '../connection/config.php';

              // Fetch Announcements
              $stmt = $conn->query("SELECT * FROM announcements ORDER BY date ASC");
              while ($announcement = $stmt->fetch_assoc()) {
                ?>
                <div class="col-md-3 d-flex align-items-stretch">
                  <div class="card card-compact">
                    <img src="../dist/assets/images/announcements/<?php echo htmlspecialchars($announcement['image']); ?>"
                      class="card-img-top" alt="<?php echo htmlspecialchars($announcement['name']); ?>">
                    <div class="card-body card-body-compact">
                      <h5 class="card-title"><?php echo htmlspecialchars($announcement['name']); ?></h5>
                      <p class="card-text text-muted">
                        Date: <?php echo date('F d, Y', strtotime($announcement['date'])); ?>
                        <?php if ($announcement['day']): ?>
                          (<?php echo htmlspecialchars($announcement['day']); ?>)
                        <?php endif; ?>
                      </p>
                    </div>
                  </div>
                </div>
                <?php
              }
              ?>
            </div>
          </div>


          <div class="row mt-5">
            <div class="col-12">
              <h2 class="text-center mb-4">Upcoming Events</h2>
            </div>
            <?php
            // Fetch Events
            $stmt = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
            while ($event = $stmt->fetch_assoc()) {
              ?>
              <div class="col-md-3">
                <div class="card card-compact">
                  <?php if ($event['image']): ?>
                    <img src="../dist/assets/images/events/<?php echo htmlspecialchars($event['image']); ?>"
                      class="card-img-top" alt="<?php echo htmlspecialchars($event['title']); ?>">
                  <?php endif; ?>
                  <div class="card-body card-body-compact">
                    <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                    <p class="card-text text-muted mb-1">
                      Date: <?php echo date('F d, Y', strtotime($event['event_date'])); ?>
                    </p>
                    <p class="card-text small"><?php echo htmlspecialchars($event['description']); ?></p>
                  </div>
                </div>
              </div>
              <?php
            }
            ?>
          </div>
        </div>








        <br><br><br><br><br><br><br>

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024-2050. <a
                href="" target="_blank">Paschal</a>. All rights reserved.</span>
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

  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../dist/assets/vendors/chart.js/chart.umd.js"></script>
  <script src="../dist/assets/vendors/datatables.net/jquery.dataTables.js"></script>
  <!-- <script src="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script> -->
  <script src="../dist/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script>
  <script src="../dist/assets/js/dataTables.select.min.js"></script>
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