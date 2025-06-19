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
    <title>Settings | Member</title>
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
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary-color: #03C03C;
            --primary-dark: #00563B;
            --secondary-color: #f8f9fa;
            --text-dark: #2c3e50;
            --text-light: #495057;
            --border-color: #e0e0e0;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .content-wrapper {
            padding: 2rem 2.5rem;
        }
        
        /* Card Styling */
        .settings-card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            background: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .settings-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-body {
            padding: 2.5rem;
        }
        
        /* Section Headers */
        .section-title {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 2rem;
            position: relative;
            text-align: center;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: -10px;
            width: 80px;
            height: 3px;
            background: var(--primary-color);
        }
        
        .section-subtitle {
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        /* Form Elements */
        .form-label {
            font-weight: 500;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(3, 192, 60, 0.15);
        }
        
        /* Profile Image */
        .profile-image-container {
            width: fit-content;
            margin: 0 auto 2rem;
            position: relative;
        }
        
        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .profile-image:hover {
            transform: scale(1.03);
        }
        
        .upload-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.1);
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color) !important;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Password Toggle */
        .input-group-text {
            background-color: var(--secondary-color);
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .input-group-text:hover {
            background-color: #e9ecef;
        }
        
        /* Footer */
        .footer {
            background-color: white;
            padding: 1.5rem;
            margin-top: 3rem;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
            border-top: 1px solid rgba(0, 0, 0, 0.03);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .content-wrapper {
                padding: 1.5rem;
            }
            
            .card-body {
                padding: 1.75rem;
            }
        }
        
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .profile-image {
                width: 120px;
                height: 120px;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .settings-card {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        /* Form Group Spacing */
        .form-group-spaced {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo me-5" href="index.php"><img src="../dist/assets/images/logo.png"
                        class="me-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/pmpc-logo.png"
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
                    <a class="nav-link" href="transaction.php">
                        <i class="fas fa-right-left"></i>
                        <span class="menu-title">Transaction</span>
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
            <style>
                .navbar {
                    padding-top: 0 !important;
                    margin-top: 0 !important;
                }
            </style>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php
                    // Check if the "success" parameter is set in the URL
                    if (isset($_GET['success']) && $_GET['success'] == 1) {
                        echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                Swal.fire({
                                    icon: "success",
                                    title: "Settings Updated Successfully",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            });
                        </script>';
                    }
                    ?>
                    
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="settings-card">
                                <div class="card-body">
                                    <h4 class="section-title">Account Settings</h4>
                                    
                                    <!-- Profile Update Form -->
                                    <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="mb-5">
                                        <h5 class="section-subtitle">Profile Information</h5>
                                        
                                        <div class="profile-image-container">
                                            <img id="profileImagePreview" src="../dist/assets/images/user/<?php echo htmlspecialchars($uploadID); ?>" class="profile-image rounded-circle" alt="Profile Image">
                                            <label for="profile_image" class="upload-btn">
                                                <i class="fas fa-camera"></i>
                                                <input type="file" id="profile_image" name="uploadID" class="d-none" accept="image/*">
                                            </label>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 form-group-spaced">
                                                <label for="first_name" class="form-label">First Name</label>
                                                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 form-group-spaced">
                                                <label for="last_name" class="form-label">Last Name</label>
                                                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group-spaced">
                                            <label for="street" class="form-label">Street</label>
                                            <input type="text" id="street" name="street" value="<?php echo htmlspecialchars($street); ?>" class="form-control" required>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 form-group-spaced">
                                                <label for="barangay" class="form-label">Barangay</label>
                                                <input type="text" id="barangay" name="barangay" value="<?php echo htmlspecialchars($barangay); ?>" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 form-group-spaced">
                                                <label for="municipality" class="form-label">Municipality</label>
                                                <input type="text" id="municipality" name="municipality" value="<?php echo htmlspecialchars($municipality); ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group-spaced">
                                            <label for="province" class="form-label">Province</label>
                                            <input type="text" id="province" name="province" value="<?php echo htmlspecialchars($province); ?>" class="form-control" required>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 form-group-spaced">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" required>
                                            </div>
                                            <div class="col-md-6 form-group-spaced">
                                                <label for="mobile" class="form-label">Mobile</label>
                                                <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid gap-2 mt-4">
                                            <button type="submit" name="update_profile" class="btn btn-primary btn-lg">Update Profile</button>
                                        </div>
                                    </form>

                                    <!-- Password Change Form -->
                                    <form action="update_password.php" method="POST">
                                        <h5 class="section-subtitle">Change Password</h5>
                                        
                                        <div class="form-group-spaced">
                                            <label for="old_password" class="form-label">Old Password</label>
                                            <div class="input-group">
                                                <input type="password" id="old_password" name="old_password" class="form-control" required>
                                                <span class="input-group-text toggle-password" data-target="old_password">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 form-group-spaced">
                                                <label for="new_password" class="form-label">New Password</label>
                                                <div class="input-group">
                                                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                                                    <span class="input-group-text toggle-password" data-target="new_password">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 form-group-spaced">
                                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                                <div class="input-group">
                                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                                    <span class="input-group-text toggle-password" data-target="confirm_password">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 mt-4">
                                            <button type="submit" name="change_password" class="btn btn-primary btn-lg">Change Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024-2050. <a href="" target="_blank">Paschal</a>. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"></span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- plugins:js -->
    <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../dist/assets/vendors/chart.js/chart.umd.js"></script>
    <script src="../dist/assets/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="../dist/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script>
    <script src="../dist/assets/js/dataTables.select.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../dist/assets/js/off-canvas.js"></script>
    <script src="../dist/assets/js/template.js"></script>
    <script src="../dist/assets/js/settings.js"></script>
    <script src="../dist/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom js for this page-->
    <script src="../dist/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../dist/assets/js/dashboard.js"></script>
    
    <script>
        // Profile Image Preview
        document.getElementById('profile_image').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profileImagePreview').src = e.target.result;
                    document.getElementById('profileImagePreview').classList.add('shadow');
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

        // Add animation when elements come into view
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.settings-card, .form-group-spaced').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>