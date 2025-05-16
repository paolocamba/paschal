

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
    <title>Inbox | Member</title>
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
                <a class="nav-link" href="home.php">
                    <i class="fa-solid fa-house"></i>
                    <span class="menu-title">Home</span>
                </a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="services.php">
                    <i class="fa-brands fa-slack"></i>
                    <span class="menu-title">Services</span>
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
                        title: "Message Send Successfully",
                        showConfirmButton: false,
                        timer: 1500 // Close after 1.5 seconds
                    });
                });
            </script>';
            }
            
                   
             ?>
                 <style>
                    .navbar {
                        padding-top: 0 !important;
                        margin-top: 0 !important;
                    }
                </style>

            <?php
            include '../connection/config.php';

            // Fetch the search query and page number
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $user_id = $_SESSION['user_id'];

            // Main query
            $sql = "SELECT 
                message_id,
                sender_id,
                category,
                admin_reply,
                admin_reply_date,
                medical_reply,
                medical_reply_date,
                membership_reply,
                membership_reply_date,
                loan_reply,
                loan_reply_date,
                admin_message,
                admin_date,
                membership_message,
                membership_date,
                medical_message,
                medical_date,
                loan_message,
                loan_date,
                message,
                is_replied,
                is_read,
                receiver_id,
                datesent
            FROM messages
            WHERE (sender_id = ? OR receiver_id = ?) AND
                (category LIKE ? 
                OR message LIKE ?
                OR DATE_FORMAT(datesent, '%M %d, %Y') LIKE ?)
            LIMIT ? OFFSET ?";

            $stmt = $conn->prepare($sql);
            $search_param = "%" . $search . "%";
            $stmt->bind_param("iisssii",
                $user_id,
                $user_id,
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
            FROM messages 
            WHERE (sender_id = ? OR receiver_id = ?) AND
                (category LIKE ?
                OR message LIKE ?
                OR DATE_FORMAT(datesent, '%M %d, %Y') LIKE ?)";

            $count_stmt = $conn->prepare($count_sql);
            $count_stmt->bind_param("iisss",
                $user_id,
                $user_id,
                $search_param,
                $search_param,
                $search_param
            );
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            $total_rows = $count_result->fetch_assoc()['total'];
            $total_pages = ceil($total_rows / $limit);

            // Fetch admin users for the message modal
            $admin_sql = "SELECT CONCAT(first_name, ' ', last_name) as full_name, user_id 
            FROM users 
            WHERE user_type IN ('Admin', 'Medical Officer', 'Loan Officer', 'Membership Officer')";
            $admin_result = $conn->query($admin_sql);
            ?>

            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                <p class="card-title mb-0">Messages</p>
                                <div class="ml-auto">
                                    <button class="btn btn-primary mb-3" data-toggle="modal"data-target="#sendMessageModal">
                                        Send Message
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <form method="GET" action="" class="form-inline" id="searchForm">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <input type="text" name="search" id="searchInput" class="form-control" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Messages">
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
                                        <th>Category</th>
                                        <th>Admin Reply</th>
                                        <th>Admin Reply Date</th>
                                        <th>Medical Reply</th>
                                        <th>Medical Reply Date</th>
                                        <th>Loan Reply</th>
                                        <th>Loan Reply Date</th>
                                        <th>Membership Reply</th>
                                        <th>Membership Reply Date</th>
                                        <th>Admin Message</th>
                                        <th>Admin Message Date</th>
                                        <th>Membership Message</th>
                                        <th>Membership Message Date</th>
                                        <th>Medical Message</th>
                                        <th>Medical Message Date</th>
                                        <th>Loan Message</th>
                                        <th>Loan Message Date</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while($row = $result->fetch_assoc()): ?>
                                               
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['admin_reply']); ?></td>
                                                    <td>
                                                        <?php if (!empty($row['admin_reply_date'])): ?>
                                                            <?php echo date('F d, Y h:i A', strtotime($row['admin_reply_date'])); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row['medical_reply']); ?></td>
                                                    <td>
                                                        <?php if (!empty($row['medical_reply_date'])): ?>
                                                            <?php echo date('F d, Y h:i A', strtotime($row['medical_reply_date'])); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row['loan_reply']); ?></td>
                                                    <td>
                                                        <?php if (!empty($row['loan_reply_date'])): ?>
                                                            <?php echo date('F d, Y h:i A', strtotime($row['loan_reply_date'])); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row['membership_reply']); ?></td>
                                                    <td>
                                                        <?php if (!empty($row['membership_reply_date'])): ?>
                                                            <?php echo date('F d, Y h:i A', strtotime($row['membership_reply_date'])); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row['admin_message']); ?></td>
                                                    <td>
                                                        <?php if (!empty($row['admin_date'])): ?>
                                                            <?php echo date('F d, Y h:i A', strtotime($row['admin_date'])); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row['membership_message']); ?></td>
                                                    <td>
                                                        <?php if (!empty($row['membership_date'])): ?>
                                                            <?php echo date('F d, Y h:i A', strtotime($row['membership_date'])); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row['medical_message']); ?></td>
                                                    <td>
                                                        <?php if (!empty($row['medical_date'])): ?>
                                                            <?php echo date('F d, Y h:i A', strtotime($row['medical_date'])); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($row['loan_message']); ?></td>
                                                    <td>
                                                        <?php if (!empty($row['loan_date'])): ?>
                                                            <?php echo date('F d, Y h:i A', strtotime($row['loan_date'])); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                    <!--<td>
                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewModal<?php echo $row['message_id']; ?>"><i class="fa-solid fa-eye"></i></button>
                                                    </td>-->
                                                </tr>
                                                <!-- View Modal -->
                                                <!--<div class="modal fade" id="viewModal<?php echo $row['message_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel<?php echo $row['message_id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="viewModalLabel<?php echo $row['message_id']; ?>">Message Details</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-12 mb-3">
                                                                        <strong>Reply:</strong>
                                                                        <p class="mt-2"><?php echo htmlspecialchars($row['admin_reply']); ?></p>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <strong>Replied On:</strong>
                                                                        <p class="mt-2">
                                                                        <?php echo date('F d, Y h:i A', strtotime($row['admin_reply_date'])); ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 mb-3">
                                                                        <strong>Reply:</strong>
                                                                        <p class="mt-2"><?php echo htmlspecialchars($row['medical_reply']); ?></p>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <strong>Replied On:</strong>
                                                                        <p class="mt-2">
                                                                        <?php echo date('F d, Y h:i A', strtotime($row['medical_reply_date'])); ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 mb-3">
                                                                        <strong>Reply:</strong>
                                                                        <p class="mt-2"><?php echo htmlspecialchars($row['loan_reply']); ?></p>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <strong>Replied On:</strong>
                                                                        <p class="mt-2">
                                                                        <?php echo date('F d, Y h:i A', strtotime($row['loan_reply_date'])); ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12 mb-3">
                                                                        <strong>Reply:</strong>
                                                                        <p class="mt-2"><?php echo htmlspecialchars($row['membership_reply']); ?></p>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <strong>Replied On:</strong>
                                                                        <p class="mt-2">
                                                                        <?php echo date('F d, Y h:i A', strtotime($row['membership_reply_date'])); ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->
                                                
                                                 
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5">No messages found</td>
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

            <!-- Message Modal -->
            <div id="sendMessageModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Send a Message</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="send_message.php" method="POST">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="recipient">Send to:</label>
                                    <select class="form-control" id="recipient" name="recipient" required>
                                        <option value="">Select Admin</option>
                                        <?php while($admin = $admin_result->fetch_assoc()): ?>
                                            <option value="<?php echo $admin['user_id']; ?>">
                                                <?php echo htmlspecialchars($admin['full_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category:</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="General Query">General Query</option>
                                        <option value="Services">Services</option>
                                        <option value="Loan">Loan</option>
                                        <option value="Medical">Medical</option>
                                        <option value="Membership">Membership</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="message">Message:</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

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
                            // Fetch appointments for the logged-in user
                            $appointment_sql = "SELECT * FROM appointments WHERE user_id = ? ORDER BY appointmentdate DESC";
                            $appointment_stmt = $conn->prepare($appointment_sql);
                            $appointment_stmt->bind_param("i", $user_id);
                            $appointment_stmt->execute();
                            $appointment_result = $appointment_stmt->get_result();
                            
                            if ($appointment_result->num_rows > 0): ?>
                                <?php while($appointment = $appointment_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['description']); ?></td>
                                        <td><?php echo date('F d, Y', strtotime($appointment['appointmentdate'])); ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php 
                                                switch($appointment['status']) {
                                                    case 'Pending': echo 'badge-warning'; break;
                                                    case 'Approved': echo 'badge-success'; break;
                                                    case 'Completed': echo 'badge-primary'; break;
                                                    case 'Cancelled': echo 'badge-danger'; break;
                                                    default: echo 'badge-secondary';
                                                }
                                                ?>">
                                                <?php echo htmlspecialchars($appointment['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                        <?php if ($appointment['status'] == 'Pending'): ?>
                                        <button class="btn btn-danger btn-sm cancel-appointment" 
                                            data-id="<?php echo $appointment['id']; ?>">
                                            Cancel
                                        </button>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>

                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No appointments found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
    dataType: 'json', // <-- this line ensures automatic JSON parsing
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