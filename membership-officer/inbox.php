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
  <title>Inbox | Membership Officer</title>
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
            <i class="fa-solid fa-gauge"></i>
            <span class="menu-title">Dashboard</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="member.php">
            <i class="fa-solid fa-users"></i>
            <span class="menu-title">Membership Application</span>
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
        

          
          <style>
            /* Preview thumbnail styles */
            

            /* Modal styles */
            .modal-overlay {
              position: fixed;
              bottom: 20px;
              left: 80%;
              z-index: 1000;
              display: none;
            }

            .modal-container {
              background: white;
              border-radius: 8px;
              box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
              width: 350px;
              max-width: 90vw;
              border: 1px solid #ddd;
            }

            .modal-header {
              display: flex;
              justify-content: space-between;
              align-items: center;
              padding: 15px;
              border-bottom: 1px solid #eee;
            }

            .modal-close {
              cursor: pointer;
              font-size: 24px;
            }

            .modal-body {
              padding: 15px;
            }

            select,
            textarea {
              width: 100%;
              margin-bottom: 15px;
              padding: 8px;
              border: 1px solid #ddd;
              border-radius: 4px;
            }
            .dashboard-card {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-bottom: 20px;
            }
            .chart-container {
                width: 100%;
                max-width: 500px; /* Adjust the maximum width as needed */
                height: 300px; /* Adjust the height as needed */
                margin: 0 auto; /* Center the chart horizontally */
              }

              #memberStatusChart {
                width: 100%;
                height: 100%;
              }
            .metric-card {
                background-color: #ffffff;
                border-left: 4px solid;
                padding: 15px;
                margin-bottom: 15px;
            }

            /* Responsive styles */
            @media (max-width: 767px) {
              .modal-overlay {
                left: 60%;
                transform: translateX(-50%);
              }

              .modal-container {
                width: 90%;
              }

              select,
              textarea {
                font-size: 16px;
              }

              
            }
          </style>
         
          <?php

            // Check if the "success" parameter is set in the URL
            if (isset($_GET['success']) && $_GET['success'] == 1) {
            // Use SweetAlert to show a success message
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "success",
                        title: "Your Message Send Successfully",
                        showConfirmButton: false,
                        timer: 1500 // Close after 1.5 seconds
                    });
                });
            </script>';
            }
            // Check if the "success" parameter is set in the URL
            if (isset($_GET['success']) && $_GET['success'] == 2) {
              // Use SweetAlert to show a success message
              echo '<script>
                  document.addEventListener("DOMContentLoaded", function() {
                      Swal.fire({
                          icon: "success",
                          title: "You Replied Successfully",
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

   
            $user_id = $_SESSION['user_id'];
            
                        $sql = "SELECT 
                        message_id,
                        sender_id,
                        category,
                        message,
                        is_replied,
                        is_read,
                        datesent
                    FROM messages
                    WHERE receiver_id = ? AND
                        (category LIKE ? 
                        OR message LIKE ?
                        OR DATE_FORMAT(datesent, '%M %d, %Y') LIKE ?)
                    LIMIT ? OFFSET ?";

            $stmt = $conn->prepare($sql);
            $search_param = "%" . $search . "%";
            $stmt->bind_param("isssii",
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
                WHERE receiver_id = ? AND
                    (category LIKE ?
                    OR message LIKE ?
                    OR DATE_FORMAT(datesent, '%M %d, %Y') LIKE ?)";

            $count_stmt = $conn->prepare($count_sql);
            $count_stmt->bind_param("isss",
                $user_id,
                $search_param,
                $search_param,
                $search_param
            );
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            $total_rows = $count_result->fetch_assoc()['total'];
            $total_pages = ceil($total_rows / $limit);

            // Fetch member users for the message modal
            $member_sql = "SELECT CONCAT(first_name, ' ', last_name) as full_name, user_id 
                        FROM users 
                        WHERE user_type = 'Member'";
            $member_result = $conn->query($member_sql);
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
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                                                    <td><?php echo date('F d, Y h:i A', strtotime($row['datesent'])); ?></td>
                                                    
                                                    <td>
                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewModal<?php echo $row['message_id']; ?>"><i class="fa-solid fa-eye"></i></button>
                                                    </td>
                                                </tr>
                                                <!-- View Modal -->

                                                <div class="modal fade" id="viewModal<?php echo $row['message_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel<?php echo $row['message_id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="viewModalLabel<?php echo $row['message_id']; ?>">Message Details</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="message-details">
                                                                    <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                                                                    <p><strong>Message:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                                                                    <p><strong>Date Sent:</strong> <?php echo date('F d, Y h:i A', strtotime($row['datesent'])); ?></p>
                                                                    
                                                                    <!-- Admin Reply Section -->
                                                                    <?php if(!empty($row['admin_reply'])): ?>
                                                                        <hr>
                                                                        <div class="admin-reply">
                                                                            <h6>Medical Officer Reply:</h6>
                                                                            <p><strong>Reply:</strong> <?php echo htmlspecialchars($row['membership_reply']); ?></p>
                                                                            <p><strong>Reply Date:</strong> <?php echo htmlspecialchars($row['membership_reply_date']); ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <?php if(empty($row['medical_reply'])): ?>
                                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#replyModal<?php echo $row['message_id']; ?>">
                                                                        Reply
                                                                    </button>
                                                                <?php else: ?>
                                                                    <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $row['message_id']; ?>)">
                                                                        Delete
                                                                    </button>
                                                                <?php endif; ?>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                 <!-- Reply Modal -->
                                                <div class="modal fade" id="replyModal<?php echo $row['message_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel<?php echo $row['message_id']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="replyModalLabel<?php echo $row['message_id']; ?>">Reply to Message</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="reply.php" method="POST">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="message_id" value="<?php echo $row['message_id']; ?>">
                                                                    <div class="form-group">
                                                                        <label for="replyText<?php echo $row['message_id']; ?>">Your Reply</label>
                                                                        <textarea class="form-control" id="replyText<?php echo $row['message_id']; ?>" name="membership_reply" rows="4" required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Send Reply</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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
                                        <option value="">Select Member</option>
                                        <?php while($member = $member_result->fetch_assoc()): ?>
                                            <option value="<?php echo $member['user_id']; ?>">
                                                <?php echo htmlspecialchars($member['full_name']); ?>
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
                                    <textarea class="form-control" id="membership_message" name="membership_message" rows="4" required></textarea>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <!-- Chart.js -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
function confirmDelete(messageId) {
    if(confirm('Are you sure you want to delete this message?')) {
        window.location.href = 'delete_message.php?message_id=' + messageId;
    }
}
document.addEventListener('DOMContentLoaded', function() {
        // Membership Status Chart
        var memberStatusCtx = document.getElementById('memberStatusChart').getContext('2d');
        new Chart(memberStatusCtx, {
            type: 'pie',
            data: {
                labels: [
                    <?php foreach($membershipStatusData as $status): ?>
                    '<?php echo $status['membership_status']; ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    data: [
                        <?php foreach($membershipStatusData as $status): ?>
                        <?php echo $status['count']; ?>,
                        <?php endforeach; ?>
                    ],
                    backgroundColor: [
                        '#007bff', '#28a745', '#dc3545', '#ffc107', '#6c757d'
                    ]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Membership Status Distribution'
                }
            }
        });

        // Application Status Chart
        var applicationStatusCtx = document.getElementById('applicationStatusChart').getContext('2d');
        new Chart(applicationStatusCtx, {
            type: 'bar',
            data: {
                labels: [
                    <?php foreach($applicationStatusData as $status): ?>
                    '<?php echo $status['status']; ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    label: 'Application Status',
                    data: [
                        <?php foreach($applicationStatusData as $status): ?>
                        <?php echo $status['count']; ?>,
                        <?php endforeach; ?>
                    ],
                    backgroundColor: [
                        '#ffc107', '#28a745', '#dc3545', '#dc3545', '#6c757d'
                    ]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Member Application Status'
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
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
    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->
</body>

</html>