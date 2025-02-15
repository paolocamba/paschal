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
    <title>Settings | Liaison Officer</title>
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
                                <!--<h3 class="font-weight-bold">Welcome</h3>
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
                                title: "Settings Updated Successfully",
                                showConfirmButton: false,
                                timer: 1500 // Close after 1.5 seconds
                            });
                        });
                    </script>';
                }

                $user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
                $profile_title = '';

                switch ($user_type) {
                    case 'Admin':
                        $profile_title = 'Admin Profile Settings';
                        break;
                    case 'Member':
                        $profile_title = 'Member Profile Settings';
                        break;
                    case 'Loan-Officer':
                        $profile_title = 'Loan-Officer Profile Settings';
                        break;
                    case 'Liaison-Officer':
                        $profile_title = 'Liaison-Officer Profile Settings';
                        break;
                    case 'Medical-Officer':
                        $profile_title = 'Medical-Officer Profile Settings';
                        break;


                    default:
                        $profile_title = 'Profile Settings';
                        break;
                }

                ?>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center mb-4">Account Settings</h4>
                                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3 text-center">
                                        <div class="d-flex justify-content-center mb-3">
                                            <div class="position-relative">
                                                <img id="profileImagePreview"
                                                    src="../dist/assets/images/user/<?php echo htmlspecialchars($uploadID); ?>"
                                                    class="rounded-circle img-thumbnail"
                                                    style="width: 150px; height: 150px; object-fit: cover;"
                                                    alt="Profile Image">
                                                <label for="profile_image"
                                                    class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle"
                                                    style="padding: 3px 10px;">
                                                    <i class="fas fa-upload"></i>
                                                    <input type="file" id="profile_image" name="uploadID" class="d-none"
                                                        accept="image/*">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" id="first_name" name="first_name"
                                                value="<?php echo htmlspecialchars($first_name); ?>"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" id="last_name" name="last_name"
                                                value="<?php echo htmlspecialchars($last_name); ?>" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="mb-3">
                                            <label for="street" class="form-label">Street</label>
                                            <input type="text" id="street" name="street"
                                                value="<?php echo htmlspecialchars($street); ?>" class="form-control"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="barangay" class="form-label">Barangay</label>
                                            <input type="text" id="barangay" name="barangay"
                                                value="<?php echo htmlspecialchars($barangay); ?>" class="form-control"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="municipality" class="form-label">Municipality</label>
                                            <input type="text" id="municipality" name="municipality"
                                                value="<?php echo htmlspecialchars($municipality); ?>"
                                                class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="province" class="form-label">Province</label>
                                            <input type="text" id="province" name="province"
                                                value="<?php echo htmlspecialchars($province); ?>" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email"
                                            value="<?php echo htmlspecialchars($email); ?>" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile" class="form-label">Mobile</label>
                                        <input type="text" id="mobile" name="mobile"
                                            value="<?php echo htmlspecialchars($mobile); ?>" class="form-control"
                                            required>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="old_password" class="form-label">Old Password</label>
                                            <div class="input-group">
                                                <input type="password" id="old_password" name="old_password"
                                                    class="form-control" required>
                                                <span class="input-group-text toggle-password"
                                                    data-target="old_password" style="cursor: pointer;">
                                                    <i class="fas fa-eye" style="color:black;"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <div class="input-group">
                                                <input type="password" id="new_password" name="new_password"
                                                    class="form-control" required>
                                                <span class="input-group-text toggle-password"
                                                    data-target="new_password" style="cursor: pointer;">
                                                    <i class="fas fa-eye" style="color:black;"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="confirm_password" class="form-label">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password" id="confirm_password" name="confirm_password"
                                                    class="form-control" required>
                                                <span class="input-group-text toggle-password"
                                                    data-target="confirm_password" style="cursor: pointer;">
                                                    <i class="fas fa-eye" style="color:black;"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <script>
                    document.getElementById('profile_image').addEventListener('change', function (event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                document.getElementById('profileImagePreview').src = e.target.result;
                            }
                            reader.readAsDataURL(file);
                        }
                    });

                    // Password Visibility Toggle
                    document.querySelectorAll('.toggle-password').forEach(button => {
                        button.addEventListener('click', function () {
                            const targetId = this.getAttribute('data-target');
                            const passwordInput = document.getElementById(targetId);
                            const icon = this.querySelector('i');

                            if (passwordInput.type === 'password') {
                                passwordInput.type = 'text';
                                icon.classList.remove('fa-eye');
                                icon.classList.add('fa-eye-slash');
                            } else {
                                passwordInput.type = 'password';
                                icon.classList.remove('fa-eye-slash');
                                icon.classList.add('fa-eye');
                            }
                        });
                    });
                </script>



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