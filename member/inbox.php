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

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $receiver_id = $_POST['recipient'];
    $category = $_POST['category'];
    $message = $_POST['message'];
    
    // Insert the new message
    $insert_sql = "INSERT INTO messages (sender_id, receiver_id, category, message) 
                   VALUES ('$id', '$receiver_id', '$category', '$message')";
    if ($conn->query($insert_sql)) {
        $success = "Message sent successfully!";
    } else {
        $error = "Error sending message: " . $conn->error;
    }
}

// Handle reply sending
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_reply'])) {
    $receiver_id = $_POST['recipient'];
    $category = $_POST['category'];
    $message = $_POST['message'];
    $thread_id = $_POST['thread_id'];
    
    // Insert the reply message
    $insert_sql = "INSERT INTO messages (sender_id, receiver_id, category, message, thread_id) 
                   VALUES ('$id', '$receiver_id', '$category', '$message', '$thread_id')";
    if ($conn->query($insert_sql)) {
        $success = "Reply sent successfully!";
        // Mark the original message as replied if this is the first reply
        $conn->query("UPDATE messages SET is_replied = 1 WHERE message_id = '$thread_id'");
    } else {
        $error = "Error sending reply: " . $conn->error;
    }
}

// Fetch messages for the current user
$messages_sql = "SELECT m.*, 
                u1.first_name as sender_first_name, u1.last_name as sender_last_name, u1.uploadID as sender_image,
                u2.first_name as receiver_first_name, u2.last_name as receiver_last_name, u2.uploadID as receiver_image
                FROM messages m
                JOIN users u1 ON m.sender_id = u1.user_id
                JOIN users u2 ON m.receiver_id = u2.user_id
                WHERE m.sender_id = '$id' OR m.receiver_id = '$id'
                ORDER BY m.sent_at DESC";
$messages_result = $conn->query($messages_sql);

// Fetch admin users for the message modal
$admin_sql = "SELECT user_id, CONCAT(first_name, ' ', last_name) as full_name, user_type 
              FROM users 
              WHERE user_type IN ('Admin', 'Medical Officer', 'Loan Officer', 'Membership Officer')";
$admin_result = $conn->query($admin_sql);

// Function to fetch thread messages
function fetchThreadMessages($conn, $thread_id) {
    $thread_sql = "SELECT m.*, 
                  u1.first_name as sender_first_name, u1.last_name as sender_last_name, u1.uploadID as sender_image,
                  u2.first_name as receiver_first_name, u2.last_name as receiver_last_name, u2.uploadID as receiver_image
                  FROM messages m
                  JOIN users u1 ON m.sender_id = u1.user_id
                  JOIN users u2 ON m.receiver_id = u2.user_id
                  WHERE m.thread_id = '$thread_id' OR m.message_id = '$thread_id'
                  ORDER BY m.sent_at ASC";
    return $conn->query($thread_sql);
}

// Mark message as read when viewing
if (isset($_GET['thread_id'])) {
    $thread_id = $_GET['thread_id'];
    $conn->query("UPDATE messages SET is_read = 1 WHERE message_id = '$thread_id' AND receiver_id = '$id'");
}
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
    <style>
        .navbar {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
        .nav-link i {
            margin-right: 10px;
        }
        .btn-primary {
            background-color:#03C03C !important;
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
        .message-card {
            border-left: 4px solid #03C03C;
            transition: all 0.3s;
        }
        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .unread {
            background-color: #f8f9fa;
            border-left: 4px solid #ffc107;
        }
        .message-sender {
            font-weight: 600;
        }
        .message-time {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .message-preview {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .message-category {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .thread-indicator {
            margin-left: 10px;
            font-size: 0.8rem;
            color: #6c757d;
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

            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                <p class="card-title mb-0">Messages</p>
                                <div class="ml-auto">
                                    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#sendMessageModal">
                                        <i class="fas fa-plus"></i> New Message
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
    <table class="table table-striped table-borderless">
        <thead>
            <tr>
                <th>From/To</th>
                <th>Category</th>
                <th class="message-col">Message</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($messages_result->num_rows > 0): ?>
                <?php while($message = $messages_result->fetch_assoc()): ?>
                    <?php 
                        $is_sender = ($message['sender_id'] == $id);
                        $other_person = $is_sender ? 
                            $message['receiver_first_name'] . ' ' . $message['receiver_last_name'] : 
                            $message['sender_first_name'] . ' ' . $message['sender_last_name'];
                        $other_image = $is_sender ? $message['receiver_image'] : $message['sender_image'];
                        $status_class = $message['is_read'] ? '' : 'unread';
                    ?>
                    <tr class="<?php echo $status_class; ?>">
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="message-sender"><?php echo $other_person; ?></span><br>
                                    <small class="text-muted"><?php echo $is_sender ? 'To' : 'From'; ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                <?php 
                            switch($message['category']) {
                                case 'General Query': echo 'badge-primary'; break;
                                case 'Membership': echo 'badge-success'; break;
                                case 'Loan': echo 'badge-warning'; break;
                                case 'Services': echo 'badge-info'; break;  // Added for Services
                                case 'Medical': echo 'badge-danger'; break;
                                default: echo 'badge-secondary';
                            }
                                ?>">
                                <?php echo $message['category']; ?>
                            </span>
                            <?php if ($message['thread_id']): ?>
                                <span class="thread-indicator"><i class="fas fa-reply"></i> Thread</span>
                            <?php endif; ?>
                        </td>
                        <td class="message-col message-preview" style="max-width: 200px;">
                            <div class="text-truncate" style="max-width: 200px;">
                                <?php echo htmlspecialchars($message['message']); ?>
                            </div>
                        </td>
                        <td class="message-time"><?php echo date('M j, Y h:i A', strtotime($message['sent_at'])); ?></td>
                        <td>
                            <?php if ($is_sender): ?>
                                <?php if ($message['is_replied']): ?>
                                    <span class="badge badge-success">Replied</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Sent</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if ($message['is_read']): ?>
                                    <span class="badge badge-primary">Read</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Unread</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                        <button class="btn btn-sm btn-primary view-message" 
                            data-id="<?php echo $message['message_id']; ?>"
                            data-sender="<?php echo $message['sender_first_name'] . ' ' . $message['sender_last_name']; ?>"
                            data-sender-id="<?php echo $message['sender_id']; ?>"
                            data-receiver="<?php echo $message['receiver_first_name'] . ' ' . $message['receiver_last_name']; ?>"
                            data-receiver-id="<?php echo $message['receiver_id']; ?>"
                            data-category="<?php echo $message['category']; ?>"
                            data-message="<?php echo htmlspecialchars($message['message']); ?>"
                            data-date="<?php echo date('M j, Y h:i A', strtotime($message['sent_at'])); ?>"
                            data-is-sender="<?php echo $is_sender ? 'true' : 'false'; ?>"
                            data-thread-id="<?php echo $message['thread_id'] ? $message['thread_id'] : $message['message_id']; ?>">
                            <i class="fas fa-eye"></i> View
                        </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No messages found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    .message-col {
        max-width: 200px;
        width: 200px;
    }
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Send Message Modal -->
            <div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="sendMessageModalLabel">New Message</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="recipient">Recipient</label>
                                    <select class="form-control" id="recipient" name="recipient" required>
                                        <option value="">Select Recipient</option>
                                        <?php while($admin = $admin_result->fetch_assoc()): ?>
                                            <option value="<?php echo $admin['user_id']; ?>">
                                                <?php echo htmlspecialchars($admin['full_name']); ?> (<?php echo $admin['user_type']; ?>)
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="General Query">General Query</option>
                                        <option value="Membership">Membership</option>
                                        <option value="Loan">Loan</option>
                                        <option value="Services">Services</option>
                                        <option value="Medical">Medical</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="send_message" class="btn btn-primary">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

<!-- View Message Modal -->
    <div class="modal fade" id="viewMessageModal" tabindex="-1" role="dialog" aria-labelledby="viewMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title mb-0" id="viewMessageModalLabel">Message Details</h5>
                    <button type="button" class="close py-1" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Main Message Section -->
                    <div class="message-content bg-light p-3 mb-3 rounded">
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <strong id="view-sender"></strong>
                                <small class="text-muted d-block">to <span id="view-receiver"></span></small>
                            </div>
                            <div class="text-right">
                                <span id="view-category" class="badge"></span>
                                <small class="text-muted d-block" id="view-date"></small>
                            </div>
                        </div>
                        <div id="view-message" class="message-text"></div>
                    </div>

                    <!-- Reply Section -->
                    <div class="reply-section mt-3 pt-2 border-top">
                        <form id="replyForm" method="POST" action="">
                            <input type="hidden" id="reply-thread-id" name="thread_id">
                            <input type="hidden" id="reply-recipient" name="recipient">
                            <input type="hidden" id="reply-category" name="category">
                            <div class="input-group">
                                <textarea class="form-control form-control-sm" id="reply-message" name="message" 
                                        rows="2" placeholder="Type your reply..." required></textarea>
                                <div class="input-group-append">
                                    <button type="submit" name="send_reply" class="btn btn-primary btn-sm">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Thread Messages -->
                    <div id="thread-messages-container">
                        <h6 class="mb-2">Conversation</h6>
                        <div id="thread-messages" class="thread-container">
                            <?php 
                            if (isset($_GET['thread_id'])) {
                                $thread_id = $_GET['thread_id'];
                                $thread_result = fetchThreadMessages($conn, $thread_id);
                                
                                if ($thread_result->num_rows > 1) { // Only show if there are replies
                                    while($thread_msg = $thread_result->fetch_assoc()) {
                                        $is_sender = ($thread_msg['sender_id'] == $id);
                                        ?>
                                        <div class="thread-message mb-2 p-2 rounded <?php echo $thread_msg['is_read'] || $is_sender ? 'bg-white' : 'bg-light'; ?>">
                                            <div class="d-flex justify-content-between">
                                                <strong><?php echo $thread_msg['sender_first_name'] . ' ' . $thread_msg['sender_last_name']; ?></strong>
                                                <small class="text-muted"><?php echo date('M j, Y h:i A', strtotime($thread_msg['sent_at'])); ?></small>
                                            </div>
                                            <div class="message-text mt-1"><?php echo nl2br(htmlspecialchars($thread_msg['message'])); ?></div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo '<div class="alert alert-info py-1 px-2 mb-2">No previous messages in this conversation</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
    .message-content {
        border-left: 3px solid #03C03C;
        background-color: #f8f9fa;
    }
    .thread-message {
        border-left: 2px solid #dee2e6;
        font-size: 0.9rem;
    }
    .message-text {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    .thread-container {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 5px;
    }
    .reply-section {
        position: sticky;
        bottom: 0;
        background: white;
        padding-bottom: 5px;
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
    
    <script>
$(document).ready(function() {
    // View message modal handler
    $('.view-message').click(function() {
        var isSender = $(this).data('is-sender') === 'true';
        var category = $(this).data('category');
        var threadId = $(this).data('thread-id');
        var senderId = $(this).data('sender-id');
        var receiverId = $(this).data('receiver-id');
        
        // Set modal content
        $('#view-sender').text($(this).data('sender'));
        $('#view-receiver').text($(this).data('receiver'));
        $('#view-date').text($(this).data('date'));
        $('#view-message').text($(this).data('message'));
        
        // Set category badge
        var categoryBadge = $('#view-category');
        categoryBadge.text(category);
        categoryBadge.removeClass('badge-primary badge-success badge-warning badge-danger badge-secondary');
        
        switch(category) {
            case 'General Query': categoryBadge.addClass('badge-primary'); break;
            case 'Membership': categoryBadge.addClass('badge-success'); break;
            case 'Loan': categoryBadge.addClass('badge-warning'); break;
            case 'Services': categoryBadge.addClass('badge-info'); break;  // Added for Services
            case 'Medical': categoryBadge.addClass('badge-danger'); break;
            default: categoryBadge.addClass('badge-secondary');
        }
        
        // Set up reply form
        $('#reply-thread-id').val(threadId);
        $('#reply-recipient').val(isSender ? receiverId : senderId);
        $('#reply-category').val(category);
        
        // Clear previous thread messages
        $('#thread-messages').html('<div class="text-center py-2"><i class="fas fa-spinner fa-spin"></i> Loading conversation...</div>');
        
        // Load thread messages via AJAX
        $.ajax({
            url: 'fetch_thread.php', // Create this new file
            method: 'GET',
            data: { thread_id: threadId },
            success: function(response) {
                $('#thread-messages').html(response);
                // Scroll to bottom of thread messages
                var threadContainer = document.getElementById('thread-messages');
                threadContainer.scrollTop = threadContainer.scrollHeight;
                // Show the modal
                $('#viewMessageModal').modal('show');
            },
            error: function() {
                $('#thread-messages').html('<div class="alert alert-danger">Error loading conversation</div>');
                $('#viewMessageModal').modal('show');
            }
        });
    });
    
    // Clear search button
    $('#clearButton').click(function() {
        $('#searchInput').val('');
        $('#searchForm').submit();
    });
    
    // Show success message if URL has success parameter
    if (window.location.href.indexOf('success=1') > -1) {
        Swal.fire({
            icon: 'success',
            title: 'Message sent successfully!',
            showConfirmButton: false,
            timer: 1500
        });
        
        // Clean URL
        history.replaceState(null, null, window.location.pathname);
    }
    
    // Handle modal close properly
    $('#viewMessageModal .close, #viewMessageModal [data-dismiss="modal"]').click(function() {
        // Remove thread_id from URL when closing modal
        if (window.location.href.indexOf('thread_id=') > -1) {
            history.replaceState(null, null, window.location.pathname);
        }
        $('#viewMessageModal').modal('hide');
    });
});
</script>
  </body>
</html>