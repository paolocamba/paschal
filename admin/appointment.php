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
    <title>Appointment | Admin</title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Add these CSS links in the <head> section of your main page -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
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
                include '../connection/config.php';

                // Fetch the search query and page number 
                $search = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '';
                $page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
                $limit = 10;
                $offset = ($page - 1) * $limit;

                try {
                    // Count appointments by status
                    $status_count_sql = "SELECT status, COUNT(*) as count 
                                        FROM appointments 
                                        GROUP BY status";
                    $status_count_stmt = $conn->prepare($status_count_sql);
                    $status_count_stmt->execute();
                    $status_count_result = $status_count_stmt->get_result();
                    $status_counts = [];
                    while ($row = $status_count_result->fetch_assoc()) {
                        $status_counts[$row['status']] = $row['count'];
                    }

                    // Total appointments count
                    $total_appointments = array_sum($status_counts);

                    // Pending appointments count (assuming 'Pending' is the status for pending appointments)
                    $pending_appointments = isset($status_counts['Pending']) ? $status_counts['Pending'] : 0;

                    // Query to fetch appointments data 
                    $sql = "SELECT id, first_name, last_name, email, description, appointmentdate, status 
                            FROM appointments 
                            WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? 
                            OR description LIKE ? OR appointmentdate LIKE ? OR status LIKE ? 
                            LIMIT ? OFFSET ?";

                    $stmt = $conn->prepare($sql);
                    $search_param = "%" . $search . "%";

                    $stmt->bind_param(
                        "ssssssii",
                        $search_param,
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

                    // Fetch all appointments into an array
                    $appointments = $result->fetch_all(MYSQLI_ASSOC);

                    // Count query for pagination 
                    $count_sql = "SELECT COUNT(*) as total 
                                FROM appointments 
                                WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? 
                                OR description LIKE ? OR appointmentdate LIKE ? OR status LIKE ?";

                    $count_stmt = $conn->prepare($count_sql);
                    $count_stmt->bind_param(
                        "ssssss",
                        $search_param,
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
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                    exit;
                }
                // Define status colors
                $status_colors = [
                    'Pending' => 'warning',
                    'Approved' => 'success',
                    'Disapproved' => 'danger'

                ];


                // Check if the "success" parameter is set in the URL
                if (isset($_GET['success']) && $_GET['success'] == 1) {
                    // Use SweetAlert to show a success message
                    echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            icon: "success",
                            title: "Appointment Updated Successfully",
                            showConfirmButton: false,
                            timer: 1500 // Close after 1.5 seconds
                        });
                    });
                </script>';
                }
                ?>
                <style>
                    .appointment-dashboard {
                        display: flex;
                        gap: 20px;
                        margin-bottom: 30px;
                    }

                    .appointment-card {
                        flex: 1;
                        background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%);
                        border-radius: 15px;
                        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
                        padding: 25px;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        transition: all 0.3s ease;
                        overflow: hidden;
                        position: relative;
                    }

                    .appointment-card:hover {
                        transform: translateY(-10px);
                        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
                    }

                    .appointment-card-total {
                        border-left: 5px solid #4a89dc;
                    }

                    .appointment-card-pending {
                        border-left: 5px solid #f39c12;
                    }

                    .appointment-card-content {
                        z-index: 2;
                    }

                    .appointment-card-title {
                        font-size: 16px;
                        color: #6c757d;
                        margin-bottom: 10px;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                    }

                    .appointment-card-count {
                        font-size: 2.5rem;
                        font-weight: 700;
                        color: #333;
                        line-height: 1.2;
                    }

                    .appointment-card-icon {
                        width: 80px;
                        height: 80px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 2;
                        position: relative;
                    }

                    .appointment-card-total .appointment-card-icon {
                        background: rgba(74, 137, 220, 0.1);
                        color: #4a89dc;
                    }

                    .appointment-card-pending .appointment-card-icon {
                        background: rgba(243, 156, 18, 0.1);
                        color: #f39c12;
                    }

                    .appointment-card-icon i {
                        font-size: 2rem;
                    }

                    /* Background Effect */
                    .appointment-card::before {
                        content: '';
                        position: absolute;
                        top: -50%;
                        right: -50%;
                        width: 200%;
                        height: 200%;
                        background: radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.2) 0%, transparent 50%);
                        transform: rotate(-45deg);
                        z-index: 1;
                    }



                    #calendar {
                        width: 100%;
                        height: 400px;
                        background-color: #fff;
                        border: 1px solid #ddd;
                        border-radius: 10px;
                        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
                        padding: 20px;
                        font-family: 'Roboto', sans-serif;
                        display: flex;
                        flex-direction: column;
                        margin-bottom: 20px;
                    }

                    /* Header styling for month and year */
                    #calendar .fc-toolbar {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 10px 0;
                    }

                    #calendar .fc-toolbar h2 {
                        font-size: 1.5rem;
                        font-weight: 600;
                        color: #333;
                        text-align: center;
                        margin: 0 auto;
                    }

                    /* Arrow and button styles */
                    #calendar .fc-toolbar .fc-button-group .fc-button {
                        border: none;
                        background-color: #007bff;
                        color: #fff;
                        border-radius: 6px;
                        padding: 6px 12px;
                        margin: 0 5px;
                        font-size: 0.9rem;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    }

                    #calendar .fc-toolbar .fc-button-group .fc-button:hover {
                        background-color: #0056b3;
                    }

                    /* Today's button styling */
                    #calendar .fc-toolbar .fc-today-button {
                        background-color: #28a745;
                        color: #fff;
                        border-radius: 6px;
                        padding: 6px 12px;
                        font-size: 0.9rem;
                        margin: 0 10px;
                        border: none;
                        transition: all 0.3s ease;
                    }

                    #calendar .fc-toolbar .fc-today-button:hover {
                        background-color: #218838;
                    }


                    #calendar .fc-daygrid {
                        margin-top: 15px;
                    }

                    #calendar .fc-daygrid-cell {
                        border: 1px solid #e9ecef;
                        padding: 10px;
                        text-align: center;
                        font-size: 0.9rem;
                        transition: background-color 0.3s ease;
                    }

                    #calendar .fc-daygrid-cell:hover {
                        background-color: #f8f9fa;
                    }


                    #calendar .fc-day-today {
                        background-color: #007bff33;
                        border-radius: 4px;
                        color: #007bff;
                        font-weight: 600;
                    }


                    #calendar .fc-toolbar {
                        gap: 15px;
                    }



                    @media (max-width: 768px) {
                        .calendar-container {
                            max-width: 100%;
                            padding: 10px;
                        }

                        #calendar {
                            height: 350px;
                        }
                    }
                </style>



                <div class="appointment-dashboard">
                    <div class="appointment-card appointment-card-total">
                        <div class="appointment-card-content">
                            <h4 class="appointment-card-title">Total Appointments</h4>
                            <p class="appointment-card-count"><?php echo $total_appointments; ?></p>
                        </div>
                        <div class="appointment-card-icon">
                            <i class="fa-solid fa-square-check"></i>
                        </div>
                    </div>

                    <div class="appointment-card appointment-card-pending">
                        <div class="appointment-card-content">
                            <h4 class="appointment-card-title">Pending Appointments</h4>
                            <p class="appointment-card-count"><?php echo $pending_appointments; ?></p>
                        </div>
                        <div class="appointment-card-icon">
                            <i class="fa-solid fa-arrows-spin"></i>
                        </div>
                    </div>
                </div>
                <div class="calendar-container">

                    <!-- Calendar integration here -->
                    <div id="calendar" style="height: 500px;"></div>

                </div>
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <p class="card-title mb-0">Appointments</p>
                                    <!--<div class="ml-auto">
                                        <button class="btn btn-primary mb-3" data-toggle="modal"
                                            data-target="#calendarModal">Calendar</button>
                                    </div>-->
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Description</th>
                                                <th>Appointment Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($appointments)): ?>
                                                <?php foreach ($appointments as $appointment): ?>

                                                    <tr>
                                                        <td><?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($appointment['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($appointment['description']); ?></td>
                                                        <td><?php echo date('F d, Y', strtotime($appointment['appointmentdate'])); ?>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge badge-<?php echo $status_colors[$appointment['status']] ?? 'secondary'; ?> text-white">
                                                                <?php echo htmlspecialchars($appointment['status']); ?>
                                                            </span>
                                                        </td>

                                                        <td>
                                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                                data-target="#viewModal<?php echo $appointment['id']; ?>">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <!-- View Modal -->

                                                    <div class="modal fade" id="viewModal<?php echo $appointment['id']; ?>"
                                                        tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="viewModalLabel">Appointment
                                                                        Details</h5>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Name:
                                                                        <?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?>
                                                                    </p>
                                                                    <p>Email:
                                                                        <?php echo htmlspecialchars($appointment['email']); ?>
                                                                    </p>
                                                                    <p>Description:
                                                                        <?php echo htmlspecialchars($appointment['description']); ?>
                                                                    </p>
                                                                    <p>Appointment Date:
                                                                        <?php echo date('F d, Y', strtotime($appointment['appointmentdate'])); ?>
                                                                    </p>
                                                                    <p>Status: <span
                                                                            id="status<?php echo $appointment['id']; ?>"><?php echo htmlspecialchars($appointment['status']); ?></span>
                                                                    </p>

                                                                    <!-- Form to update the appointment status -->
                                                                    <form action="update_appointment.php" method="POST">
                                                                        <input type="hidden" name="appointment_id"
                                                                            value="<?php echo $appointment['id']; ?>">

                                                                        <!-- Buttons for updating status -->
                                                                        <button type="submit" name="status" value="approved"
                                                                            class="btn btn-success" <?php echo ($appointment['status'] == 'approved') ? 'disabled' : ''; ?>>Approve</button>
                                                                        <button type="submit" name="status" value="disapproved"
                                                                            class="btn btn-danger" <?php echo ($appointment['status'] == 'disapproved') ? 'disabled' : ''; ?>>Disapprove</button>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">No appointments found.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page - 1; ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo; </span>
                                            </a>
                                        </li>
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                <a class="page-link"
                                                    href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page + 1; ?>"
                                                aria-label="Next">
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
                // Fetch all booked appointment dates
                $booked_dates_sql = "SELECT appointmentdate FROM appointments";
                $booked_stmt = $conn->prepare($booked_dates_sql);
                $booked_stmt->execute();
                $booked_result = $booked_stmt->get_result();

                $booked_dates = [];
                while ($row = $booked_result->fetch_assoc()) {
                    $booked_dates[] = date('Y-m-d', strtotime($row['appointmentdate']));
                }
                ?>

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


    <!-- Add the FullCalendar JavaScript file at the end of the body section -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            // Check if calendar element exists before initializing
            if (!calendarEl) {
                console.error('Calendar element not found');
                return;
            }

            var bookedDates = <?php echo json_encode($booked_dates); ?>;

            try {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: bookedDates.map(function (date) {
                        return {
                            start: date,
                            display: 'background',
                            color: 'lightgrey'
                        }
                    }),
                    // Prevent selecting booked dates
                    selectConstraint: {
                        startEditable: false,
                        durationEditable: false,
                        constraint: {
                            startTime: '00:00',
                            endTime: '23:59'
                        }
                    },
                    // Prevent clicking on booked dates
                    dateClick: function (info) {
                        if (bookedDates.includes(info.dateStr)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'This date is already booked!',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }
                    }
                });
                calendar.render();
            } catch (error) {
                console.error('Error initializing calendar:', error);
            }
        });
    </script>
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