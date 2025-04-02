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
    <title>Regular Loan Form 4 | Member</title>
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

                    // Debug session and login status
                    if (!isset($_SESSION['user_id'])) {
                        header("Location: member-login.php");
                        exit();
                    }

                    $user_id = $_SESSION['user_id'];

                    // Get the loan type from the URL
                    $loan_type = isset($_GET['loanType']) ? $_GET['loanType'] : 'Regular';

                    // Verify loan application ID
                    if (!isset($_SESSION['loan_application_id'])) {
                        $query = "SELECT LoanID FROM loanapplication WHERE userID = ? ORDER BY LoanID DESC LIMIT 1";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        if ($row = $result->fetch_assoc()) {
                            $_SESSION['loan_application_id'] = $row['LoanID'];
                        } else {
                            echo "<script>
                                    alert('No active loan application found. Please start a new application.');
                                    window.location.href = 'index.php';
                                </script>";
                            exit();
                        }
                        $stmt->close();
                    }

                    $loan_id = $_SESSION['loan_application_id'];

                    // Handle form submission
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        try {
                            $columns_to_update = [];
                            $params = [];
                            $types = '';
                            $creditor_balance_total = 0; // Initialize total balance

                            // Validate and sanitize creditor information (up to 4 creditors)
                            for ($i = 1; $i <= 4; $i++) {
                                $name_key = "creditor{$i}_name";
                                $address_key = "creditor{$i}_address";
                                $original_amount_key = "creditor{$i}_original_amount";
                                $present_balance_key = "creditor{$i}_present_balance";

                                if (!empty($_POST[$name_key]) || !empty($_POST[$address_key]) || !empty($_POST[$original_amount_key]) || !empty($_POST[$present_balance_key])) {
                                    if (!empty($_POST[$name_key])) {
                                        $columns_to_update[] = "{$name_key} = ?";
                                        $params[] = trim($_POST[$name_key]);
                                        $types .= 's';
                                    }
                                    if (!empty($_POST[$address_key])) {
                                        $columns_to_update[] = "{$address_key} = ?";
                                        $params[] = trim($_POST[$address_key]);
                                        $types .= 's';
                                    }
                                    if (!empty($_POST[$original_amount_key])) {
                                        $columns_to_update[] = "{$original_amount_key} = ?";
                                        $params[] = floatval($_POST[$original_amount_key]);
                                        $types .= 'd';
                                    }
                                    if (!empty($_POST[$present_balance_key])) {
                                        $balance = floatval($_POST[$present_balance_key]);
                                        $columns_to_update[] = "{$present_balance_key} = ?";
                                        $params[] = $balance;
                                        $types .= 'd';

                                        // Accumulate total present balance
                                        $creditor_balance_total += $balance;
                                    }
                                }
                            }

                            // Save total creditor balance
                            $columns_to_update[] = "creditor_balance_total = ?";
                            $params[] = $creditor_balance_total;
                            $types .= 'd';

                            // Add property foreclosure status
                            if (isset($_POST['property_foreclosed_repossessed'])) {
                                $columns_to_update[] = "property_foreclosed_repossessed = ?";
                                $params[] = $_POST['property_foreclosed_repossessed'];
                                $types .= 's';
                            }

                            // Add co-maker status
                            if (isset($_POST['co_maker_cosigner_guarantor'])) {
                                $columns_to_update[] = "co_maker_cosigner_guarantor = ?";
                                $params[] = $_POST['co_maker_cosigner_guarantor'];
                                $types .= 's';
                            }

                            if (empty($columns_to_update)) {
                                throw new Exception("No valid data to update");
                            }

                            // Prepare update query
                            $query = "UPDATE loanapplication 
                                    SET " . implode(', ', $columns_to_update) . "
                                    WHERE LoanID = ?";

                            // Add loan ID to params
                            $params[] = $loan_id;
                            $types .= 'i';

                            // Prepare and execute statement
                            $stmt = $conn->prepare($query);
                            $bind_params = array_merge(array($stmt, $types), $params);
                            call_user_func_array('mysqli_stmt_bind_param', $bind_params);

                            if ($stmt->execute()) {
                                $stmt->close();
                                header("Location: regular-form5.php?loanType=" . urlencode($loan_type));
                                exit();
                            } else {
                                throw new Exception("Database update failed: " . $stmt->error);
                            }
                        } catch (Exception $e) {
                            error_log("Loan Application Update Error: " . $e->getMessage());
                            echo "<script>
                                    alert('Error updating loan application. " . addslashes($e->getMessage()) . "');
                                    history.back();
                                </script>";
                            exit();
                        }
                    }


                    ob_end_flush();
                    ?>
                    <div class="container">
                        <div class="form-container">
                        <h1 class="heading"><?php echo ucfirst($loan_type); ?> Loan Application - Step 4</h1>
                            <form action="regular-form4.php" method="post">
                                <div class="row mb-4">
                                    <h6>Debt and Liability Information</h6>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="creditor1_name" class="form-label">Creditor 1 Name</label>
                                            <input type="text" class="form-control" name="creditor1_name"
                                                id="creditor1_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="creditor1_address" class="form-label">Creditor 1 Address</label>
                                            <input type="text" class="form-control" name="creditor1_address"
                                                id="creditor1_address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="creditor1_original_amount" class="form-label">Creditor 1 Original Amount</label>
                                            <input type="number" class="form-control" name="creditor1_original_amount"
                                                id="creditor1_original_amount">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="creditor1_present_balance" class="form-label">Creditor 1 Present Balance</label>
                                            <input type="number" class="form-control" name="creditor1_present_balance"
                                                id="creditor1_present_balance">
                                        </div>
                                    </div>
                                    <div id="additional-creditor"></div>

                                    <div class="row mb-4">
                                        <div class="col-md-12 text-end">
                                            <button type="button" class="btn btn-primary add-creditor">Add More
                                                Creditor</button>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p>Have you had property foreclosed or repossessed?</p>
                                                <label class="radio-inline">
                                                    <input type="radio" name="property_foreclosed_repossessed" value="yes" required> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="property_foreclosed_repossessed" value="no" required> No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p>Are you a co-maker, co-signer, or guarantor on any loan not listed above?</p>
                                                <label class="radio-inline">
                                                    <input type="radio" name="co_maker_cosigner_guarantor" value="yes" required> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="co_maker_cosigner_guarantor" value="no" required> No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary next-btn px-4">Next</button>
                                </div>
                            </form>
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
    <script>
        $(document).ready(function () {
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

            // General input validation function
            function validateInput(selector, validateFn, errorMessage) {
                $(selector).on('input change', function () {
                    const value = $(this).val();
                    const isValid = validateFn(value);
                    showValidation(this, isValid, errorMessage);
                });
            }

            const validationRules = {
                // Text fields (letters, spaces, and special characters)
                textField: (value) => /^[a-zA-Z\s\p{P}]+$/u.test(value.trim()),

                // Positive number fields (Modified to allow zero)
                positiveNumber: (value) => !isNaN(parseFloat(value)) && parseFloat(value) >= 0,  // Now allows 0

                // Address fields (allowing more flexibility)
                addressField: (value) => value.trim().length > 0,

                // Radio button validation
                radioField: (value) => ['yes', 'no'].includes(value)
            };


            // Creditor validation function
            function validateCreditorFields(creditorCount) {
                const creditorFieldsToValidate = [
                    { 
                        selector: `#creditor${creditorCount}_name`, 
                        validate: validationRules.textField, 
                        message: `Creditor ${creditorCount} name is invalid` 
                    },
                    { 
                        selector: `#creditor${creditorCount}_address`, 
                        validate: validationRules.addressField, 
                        message: `Creditor ${creditorCount} address is invalid` 
                    },
                    { 
                        selector: `#creditor${creditorCount}_original_amount`, 
                        validate: validationRules.positiveNumber, 
                        message: `Creditor ${creditorCount} original amount must be a positive number` 
                    },
                    { 
                        selector: `#creditor${creditorCount}_present_balance`, 
                        validate: validationRules.positiveNumber, 
                        message: `Creditor ${creditorCount} present balance must be a positive number` 
                    }
                ];

                // Apply validation only if the field has data
                creditorFieldsToValidate.forEach(field => {
                    $(field.selector).on('input change', function () {
                        if ($(this).val().trim() === '') {
                            $(this).removeClass('is-invalid is-valid'); // Reset validation state
                        } else {
                            const isValid = field.validate($(this).val());
                            showValidation(this, isValid, field.message);
                        }
                    });
                });
            }


            // Radio button validation function
            function validateRadioFields() {
                const radioFieldsToValidate = [
                    {
                        name: 'property_foreclosed_repossessed',
                        message: 'Please select whether you have had property foreclosed or repossessed'
                    },
                    {
                        name: 'co_maker_cosigner_guarantor',
                        message: 'Please select whether you are a co-maker, co-signer, or guarantor on any loan'
                    }
                ];

                radioFieldsToValidate.forEach(field => {
                    const selectedValue = $(`input[name="${field.name}"]:checked`).val();
                    const isValid = selectedValue !== undefined;

                    if (!isValid) {
                        $(`input[name="${field.name}"]`).each(function() {
                            showValidation(this, false, field.message);
                        });
                    } else {
                        $(`input[name="${field.name}"]`).each(function() {
                            showValidation(this, true);
                        });
                    }
                });
            }

            // Initial validation for first creditor
            validateCreditorFields(1);

            // Add more creditors functionality
            let creditorCount = 1;
            $('.add-creditor').click(function () {
                creditorCount++;
                const newCreditorHtml = `
                <div class="row mb-4 creditor-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="creditor${creditorCount}_name" class="form-label">Creditor ${creditorCount} Name</label>
                            <input type="text" class="form-control" name="creditor${creditorCount}_name" 
                                id="creditor${creditorCount}_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="creditor${creditorCount}_address" class="form-label">Creditor ${creditorCount} Address</label>
                            <input type="text" class="form-control" name="creditor${creditorCount}_address" 
                                id="creditor${creditorCount}_address">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="creditor${creditorCount}_original_amount" class="form-label">Creditor ${creditorCount} Original Amount</label>
                            <input type="number" class="form-control" name="creditor${creditorCount}_original_amount" 
                                id="creditor${creditorCount}_original_amount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="creditor${creditorCount}_present_balance" class="form-label">Creditor ${creditorCount} Present Balance</label>
                            <input type="number" class="form-control" name="creditor${creditorCount}_present_balance" 
                                id="creditor${creditorCount}_present_balance">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-creditor mt-4">Remove</button>
                    </div>
                </div>
                `;
                $('#additional-creditor').append(newCreditorHtml);

                // Add validation for new creditor
                validateCreditorFields(creditorCount);

                // Remove creditor
                $(document).on('click', '.remove-creditor', function () {
                    $(this).closest('.creditor-row').remove();
                    creditorCount--;
                });
            });
            
            // Radio button change event
            $('input[type="radio"]').on('change', function() {
                validateRadioFields();
            });

            // Form submission validation
            $('form').on('submit', function (e) {
                // Validate all creditor fields
                for (let i = 1; i <= creditorCount; i++) {
                    $(`#creditor${i}_name, #creditor${i}_address, #creditor${i}_original_amount, #creditor${i}_present_balance`).trigger('change');
                }

                // Validate radio buttons
                validateRadioFields();

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