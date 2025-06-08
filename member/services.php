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
    <title>Services | Member</title>
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
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top">
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

            .page-item.active .page-link {
                background-color: #00563B !important;
                border-color: #00563B !important;
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

                <?php

                // Check if the "success" parameter is set in the URL
                if (isset($_GET['success']) && $_GET['success'] == 1) {
                    // Use SweetAlert to show a success message
                    echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "success",
                        title: "You  Set An Appointment Successfully",
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

                $user_id = $_SESSION['user_id'] ?? null;

                // Fetch services from the database
                $query = "SELECT name, type FROM services";
                $result = mysqli_query($conn, $query);
                $services = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $services[$row['type']][] = $row['name'];
                }

                // Check existing loan applications for the user
                $hasExistingLoan = false;
                $existingLoanType = '';
                if ($user_id) {
                    $loanQuery = "SELECT LoanType FROM loanapplication WHERE userID = ? AND Status = 'In Progress'";
                    $stmt = mysqli_prepare($conn, $loanQuery);
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $loanResult = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($loanResult)) {
                        $hasExistingLoan = true;
                        $existingLoanType = $row['LoanType'];
                    }
                }
                ?>


                <div class="container mt-5">
                    <h2 class="text-center mb-4">Services</h2>
                    <div class="row">
                        <!-- Products and Services -->
                        <div class="col-md-6">
                            <div class="card p-3">
                                <h4>Products and Services</h4>
                                <ul>
                                    <?php foreach ($services['Product'] as $service) { ?>
                                        <li><?= htmlspecialchars($service) ?></li>
                                    <?php } ?>
                                </ul>
                                <button class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#productServicesModal">Avail</button>
                            </div>
                        </div>

                        <!-- Medical Services -->
                        <div class="col-md-6">
                            <div class="card p-3">
                                <h4>Medical Services</h4>
                                <ul>
                                    <?php foreach ($services['Medical'] as $service) { ?>
                                        <li><?= htmlspecialchars($service) ?></li>
                                    <?php } ?>
                                </ul>
                                <button class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#medicalServicesModal">Avail</button>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Loan Services -->
                        <div class="col-md-6">
                            <div class="card p-3">
                                <h4>Loan Services</h4>
                                <ul class="list-group mb-3">
                                    <?php 
                                    // Filter services to only include Regular Loan and Collateral Loan
                                    $loanServices = array_filter($services['Loan'], function ($service) {
                                        return in_array($service, ['Regular Loan', 'Collateral Loan']);
                                    });

                                    foreach ($loanServices as $service) { ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= htmlspecialchars($service) ?>
                                            <?php if ($service == 'Regular Loan') { ?>
                                                <button class="btn btn-primary mb-3 loan-btn" data-loan-type="Regular"
                                                    data-existing-loan="<?= $hasExistingLoan ? 'true' : 'false' ?>"
                                                    data-existing-type="<?= htmlspecialchars($existingLoanType) ?>"
                                                    <?= $hasExistingLoan ? '' : 'data-bs-toggle="modal" data-bs-target="#regularLoanModal"' ?>>
                                                    Apply Now
                                                </button>
                                            <?php } elseif ($service == 'Collateral Loan') { ?>
                                                <button class="btn btn-primary mb-3 loan-btn" data-loan-type="Collateral"
                                                    data-existing-loan="<?= $hasExistingLoan ? 'true' : 'false' ?>"
                                                    data-existing-type="<?= htmlspecialchars($existingLoanType) ?>"
                                                    <?= $hasExistingLoan ? '' : 'data-bs-toggle="modal" data-bs-target="#collateralLoanModal"' ?>>
                                                    Apply Now
                                                </button>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                

                        <!-- Amortization Calculator -->
                        <div class="col-md-6">
                            <div class="card p-3">
                                <h4>Amortization Calculator</h4>
                                <button class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#calculatorModal" style="padding:10px;">Try Now</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Services Modal -->
                <div class="modal fade" id="productServicesModal" tabindex="-1"
                    aria-labelledby="productServicesModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productServicesModalLabel">Set Appointment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="add_prod.php" method="post">
                                    <div class="mb-3">
                                        <label for="service" class="form-label">Service</label>
                                        <select id="service" name="service" class="form-select">
                                            <?php foreach ($services['Product'] as $service) { ?>
                                                <option value="<?= $service ?>"><?= $service ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" id="date" name="date" class="form-control">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="saveEventBtn">Save
                                            Appointment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Services Modal -->
                <div class="modal fade" id="medicalServicesModal" tabindex="-1"
                    aria-labelledby="medicalServicesModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="medicalServicesModalLabel">Set Appointment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="add_med.php" method="post">
                                    <div class="mb-3">
                                        <label for="service" class="form-label">Service</label>
                                        <select id="service" name="service" class="form-select">
                                            <?php foreach ($services['Medical'] as $service) { ?>
                                                <option value="<?= $service ?>"><?= $service ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" id="date" name="date" class="form-control">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="saveEventBtn">Save
                                            Appointment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regular Loan Modal -->
                <div class="modal fade" id="regularLoanModal" tabindex="-1" aria-labelledby="regularLoanModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="regularLoanModalLabel">Regular Loan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apply for a Regular Loan and get up to 90% of your share capital and savings
                                    deposits. Convenient access to funds while leveraging your savings!</p>
                                <a href="regular.php?loanType=Regular" class="btn btn-success w-100">Go to
                                    Application</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Collateral Loan Modal -->
                <div class="modal fade" id="collateralLoanModal" tabindex="-1"
                    aria-labelledby="collateralLoanModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="collateralLoanModalLabel">Collateral Loan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apply for a Collateral Loan using your land title and get up to 50% of your
                                    collateral's value to cover your financial needs!</p>
                                <a href="collateral.php?loanType=Collateral" class="btn btn-success w-100">Go to
                                    Application</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calculator Modal -->
                <div class="modal fade" id="calculatorModal" tabindex="-1" aria-labelledby="calculatorModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="calculatorModalLabel">Amortization Calculator</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="calculator">
                                    <form oninput="calculateInstallment()">
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">Loan Amount</label>
                                            <input type="number" id="amount" class="form-control"
                                                placeholder="Enter amount" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="loan-type" class="form-label">Type of Loan</label>
                                            <select id="loan-type" class="form-select" onchange="setInterestRate()">
                                                <option value="Regular">Regular</option>
                                                <option value="Collateral REM">Collateral REM</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="rate" class="form-label">Interest Rate per Annum</label>
                                            <input type="text" id="rate" class="form-control" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="terms" class="form-label">Terms of Payment</label>
                                            <select id="terms" class="form-select" onchange="calculateInstallment()">
                                                <option value="3">3 months</option>
                                                <option value="6">6 months</option>
                                                <option value="9">9 months</option>
                                                <option value="12">12 months</option>
                                                <option value="15">15 months</option>
                                                <option value="18">18 months</option>
                                                <option value="21">21 months</option>
                                                <option value="24">24 months</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="installment" class="form-label">Installment Amount</label>
                                            <input type="text" id="installment" class="form-control" readonly>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" id="saveEventBtn">Save
                                                Events</button>
                                        </div>
                                    </form>
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
        document.addEventListener('DOMContentLoaded', function () {
            var searchForm = document.getElementById('searchForm');
            var searchInput = document.getElementById('searchInput');
            var clearButton = document.getElementById('clearButton');

            clearButton.addEventListener('click', function () {
                searchInput.value = '';
                searchForm.submit(); // Submit the form to reload all records
            });
        });
        function calculateInstallment() {
            const loanAmount = parseFloat(document.getElementById('amount').value);
            const loanType = document.getElementById('loan-type').value;
            const terms = parseInt(document.getElementById('terms').value);

            let interestRate = loanType === 'Regular' ? 12 : 14; // Set interest based on loan type
            const monthlyRate = (interestRate / 100) / 12;

            if (loanAmount && terms) {
                const installment = (loanAmount * monthlyRate) / (1 - Math.pow(1 + monthlyRate, -terms));
                document.getElementById('installment').value = installment.toFixed(2);
            }
        }

        function setInterestRate() {
            const loanType = document.getElementById('loan-type').value;
            document.getElementById('rate').value = loanType === 'Regular' ? '12%' : '14%';
            calculateInstallment();
        }
        $(document).ready(function () {
            // Disable past dates
            var today = new Date().toISOString().split('T')[0];
            $('#date').attr('min', today);


        });
        document.addEventListener('DOMContentLoaded', function () {
            const loanButtons = document.querySelectorAll('.loan-btn');

            loanButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    const hasExistingLoan = this.dataset.existingLoan === 'true';
                    const existingLoanType = this.dataset.existingType;

                    if (hasExistingLoan) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Existing Loan Application',
                            text: `You can't apply for a loan. You have an existing ${existingLoanType} Loan application.`,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>