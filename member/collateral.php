<?php
ob_start();
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
    <title>Collateral Loan Form 1 | Member</title>
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

                <style>
                    :root {
                        --primary-color: #00FFAF;
                        --secondary-color: #0F4332;
                        --accent-color: #ffffff;
                        --text-color: #2c3e50;
                        --light-bg: #f3f4f9;
                    }

                    .navbar {
                        padding-top: 0 !important;
                        margin-top: 0 !important;
                    }


                    .container {
                        max-width: 1200px;
                        margin: 0 auto;
                        background: transparent;
                    }

                    .heading {
                        background: var(--secondary-color);
                        color: white;
                        margin: 0 0 20px 0;
                        padding: 1rem 2rem;
                        font-size: 1.5rem;
                        font-weight: 500;
                        border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }

                    .form-container {
                        background: white;
                        border-radius: 8px;
                        padding: 2rem;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }

                    .form-group {
                        margin-bottom: 1.5rem;
                    }

                    .form-label {
                        font-weight: 500;
                        color: var(--secondary-color);
                        margin-bottom: 0.5rem;
                        display: block;
                        font-size: 0.95rem;
                    }

                    .form-control {
                        border: 1px solid #e1e5ea;
                        border-radius: 4px;
                        padding: 0.8rem 1rem;
                        transition: all 0.3s ease;
                        font-size: 1rem;
                        width: 100%;
                        box-sizing: border-box;
                    }

                    .form-control:focus {
                        border-color: var(--primary-color);
                        box-shadow: 0 0 0 3px rgba(0, 255, 175, 0.1);
                        outline: none;
                    }

                    .checkbox-wrapper {
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                    }

                    input[type="checkbox"] {
                        width: 18px;
                        height: 18px;
                        border: 2px solid var(--primary-color);
                        border-radius: 4px;
                        cursor: pointer;
                    }

                    .btn {
                        padding: 0.8rem 2rem;
                        border-radius: 4px;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        border: none;
                        cursor: pointer;
                    }

                    .btn-primary {
                        background: var(--primary-color);
                        color: white;
                        font-weight: 600;
                    }

                    .btn-primary:hover {
                        background: #00e69d;
                        transform: translateY(-1px);
                    }

                    .row {
                        background: white;
                        border: 1px solid #e1e5ea;
                        border-radius: 8px;
                        padding: 1.5rem;
                        margin-bottom: 1.5rem;
                    }

                    h6 {
                        color: var(--secondary-color);
                        font-size: 1.1rem;
                        margin-top: 0;
                        margin-bottom: 1.5rem;
                        font-weight: 600;
                    }

                    .document-upload-1 {
                        border-left: 3px solid var(--primary-color);
                        padding-left: 1.5rem;
                    }

                    .add-assets {
                        background: transparent;
                        color: white;
                        border: 2px solid var(--primary-color);
                        padding: 0.6rem 1.5rem;
                    }

                    .add-assets:hover {
                        background: var(--primary-color);
                        color: white;
                    }

                    .items-count {
                        background: var(--primary-color);
                        color: var(--secondary-color);
                        padding: 0.25rem 0.75rem;
                        border-radius: 4px;
                        font-size: 0.875rem;
                        font-weight: 500;
                        float: right;
                    }

                    @media (max-width: 768px) {
                        .form-container {
                            padding: 1rem;
                        }

                        .row {
                            padding: 1rem;
                        }

                        .document-upload-1 {
                            padding-left: 1rem;
                            margin-top: 1rem;
                        }
                    }

                    .content-wrapper {

                        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;

                    }
                </style>
                <?php
                    include '../connection/config.php';

                    // Check if the user is logged in
                    if (!isset($_SESSION['user_id'])) {
                        header("Location: member-login.php");
                        exit();
                    }

                    $user_id = $_SESSION['user_id'];

                    // Get the loan type from the URL (either 'regular' or 'collateral')
                    $loan_type = isset($_GET['loanType']) ? $_GET['loanType'] : 'Collateral';

                    // Handle form submission
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $dateOfLoan = $_POST['dateLoan'];
                        $amountRequested = (float) str_replace(',', '', $_POST['amountRequested']);
                        $purpose = $_POST['purpose'];
                        $loanTerm = $_POST['loanterm'];
                        $modePayment = $_POST['modePayment'];

                        // Insert loan application
                        $query = "INSERT INTO loanapplication 
                                (userID, DateOfLoan, AmountRequested, LoanTerm, Purpose, LoanType, ModeOfPayment) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)";

                        $stmt = $conn->prepare($query);
                        if (!$stmt) {
                            die("Query preparation failed: " . $conn->error);
                        }

                        $stmt->bind_param("isidsss", 
                            $user_id,
                            $dateOfLoan,
                            $amountRequested,
                            $loanTerm,
                            $purpose,
                            $loan_type,
                            $modePayment
                        );

                        if ($stmt->execute()) {
                            $_SESSION['current_loan_id'] = $stmt->insert_id;
                            $stmt->close();
                            header("Location: collateral-form2.php?loanType=" . urlencode($loan_type));
                            exit();
                        } else {
                            echo "<script>
                                    alert('Error submitting loan application. Please try again.');
                                    history.back();
                                </script>";
                            $stmt->close();
                        }
                    }
                    ?>
                <div class="container">
                    <div class="form-container">
                    <h1 class="heading"><?php echo ucfirst(htmlspecialchars($loan_type)); ?> Loan Application - Step 1</h1>
                        <form action="collateral.php" method="post">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="dateLoan" class="form-label">Date of Loan <span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" name="dateLoan" id="dateLoan" placeholder="mm/dd/yyyy" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amountRequested" class="form-label">Amount Requested <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="amountRequested" id="amountRequested" required oninput="formatAmount(this)">
                                    <p class="amount" style="color:rgb(49, 167, 129);">The amount you can request depends on 50% of your collateral value</p>
                                </div>
                                <script>
                                    function formatAmount(input) {
                                    // Remove all non-numeric characters
                                    let value = input.value.replace(/\D/g, '');
                                    
                                    // Store the raw numeric value in a data attribute
                                    input.dataset.rawValue = value;
                                    
                                    // Format with commas for display
                                    input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                    }

                                    // Remove commas before form submission
                                    document.querySelector('form').addEventListener('submit', function() {
                                        const amountInput = document.getElementById('amountRequested');
                                        // Use the raw value we stored in the data attribute
                                        amountInput.value = amountInput.dataset.rawValue || amountInput.value.replace(/,/g, '');
                                    });
                                    </script>
                                    
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purpose" class="form-label">Purpose <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="purpose" id="purpose" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="loanTerm" class="form-label">Loan Term <span style="color: red;">*</span></label>
                                        <select class="form-select" name="loanterm" id="loanTerm" required>
                                            <option selected>Select Term</option>
                                            <option value="3">3 Months</option>
                                            <option value="6">6 Months</option>
                                            <option value="9">9 Months</option>
                                            <option value="12">12 Months</option>
                                            <option value="15">15 Months</option>
                                            <option value="18">18 Months</option>
                                            <option value="21">21 Months</option>
                                            <option value="24">24 Months</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="modePayment" class="form-label">Mode of Payment <span style="color: red;">*</span></label>
                                        <select class="form-select" name="modePayment" id="modePayment" required>
                                            <option selected>Select Mode</option>
                                            <option value="Weekly">Weekly</option>
                                            <option value="Bi_Monthly">Bi-Monthly</option>
                                            <option value="Monthly">Monthly</option>
                                            <option value="Quarterly">Quarterly</option>
                                            <option value="Semi_Annual">Semi Annual</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary next-btn px-4">Next</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                
                <br><br><br><br><br><br><br> <br><br><br><br><br><br><br>

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
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const loanTermSelect = document.getElementById("loanTerm");
    const modePaymentSelect = document.getElementById("modePayment");

    function updatePaymentModes() {
        const selectedTerm = parseInt(loanTermSelect.value);

        // Enable all options first
        Array.from(modePaymentSelect.options).forEach(option => {
            option.disabled = false;
        });

        // Disable invalid options based on loan term
        if (selectedTerm <= 3) {
            modePaymentSelect.querySelector('option[value="Quarterly"]').disabled = true;
            modePaymentSelect.querySelector('option[value="Semi_Annual"]').disabled = true;
        } else if (selectedTerm <= 6) {
            modePaymentSelect.querySelector('option[value="Semi_Annual"]').disabled = true;
        }

        // If current selection is invalid, reset to default
        if (modePaymentSelect.options[modePaymentSelect.selectedIndex].disabled) {
            modePaymentSelect.selectedIndex = 0;
        }
    }

    loanTermSelect.addEventListener("change", updatePaymentModes);
    updatePaymentModes(); // Run on page load to apply initial state
});
</script>

    <script>
        $(document).ready(function () {
            // Disable past dates
            var today = new Date().toISOString().split('T')[0];
            $('#dateLoan').attr('min', today);

            
            // Format number with commas for display
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Function to show validation message
            function showValidation(element, isValid, message) {
                const formGroup = $(element).closest('.form-group');
                formGroup.find('.validation-message').remove(); // Remove existing message
                
                if (!isValid) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                    formGroup.append(`<div class="validation-message text-danger small mt-1">${message}</div>`);
                } else {
                    $(element).addClass('is-valid').removeClass('is-invalid');
                }
            }

            // Date validation
            $('#dateLoan').on('input change', function() {
                const selectedDate = new Date(this.value);
                const currentDate = new Date();
                
                if (!this.value) {
                    showValidation(this, false, 'Date is required');
                } else if (selectedDate < currentDate.setHours(0,0,0,0)) {
                    showValidation(this, false, 'Cannot select past dates');
                } else {
                    showValidation(this, true);
                }
            });

          
  

            // Purpose validation
            $('#purpose').on('input', function() {
                const value = this.value.trim();
                
                if (!value) {
                    showValidation(this, false, 'Purpose is required');
                } else if (value.length < 5) {
                    showValidation(this, false, 'Purpose must be at least 5 characters');
                } else if (value.length > 255) {
                    showValidation(this, false, 'Purpose must not exceed 255 characters');
                } else {
                    showValidation(this, true);
                }
            });

            // Loan Term validation
            $('#loanTerm').on('change', function() {
                const value = this.value;
                
                if (value === 'Select Term') {
                    showValidation(this, false, 'Please select a loan term');
                } else {
                    showValidation(this, true);
                }
            });

            // Mode of Payment validation
            $('#modePayment').on('change', function() {
                const value = this.value;
                
                if (value === 'Select Mode') {
                    showValidation(this, false, 'Please select a payment mode');
                } else {
                    showValidation(this, true);
                }
            });

          

            // Form submission validation
            $('form').on('submit', function(e) {
                let isValid = true;
                
                // Trigger validation for all fields
                $('#dateLoan').trigger('change');
       
                $('#purpose').trigger('input');
                $('#loanTerm').trigger('change');
                $('#modePayment').trigger('change');

                // Check if any field is invalid
                if ($('.is-invalid').length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Form Validation',
                        text: 'Please check all fields and correct the errors.',
                        icon: 'error',
                        confirmButtonText: 'OK'
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
    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->
</body>

</html>