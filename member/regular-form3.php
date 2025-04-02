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
    <title>Regular Loan Form 3 | Member</title>
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

                if (!isset($_SESSION['user_id'])) {
                    header("Location: member-login.php");
                    exit();
                }

                $user_id = $_SESSION['user_id'];
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
                        $conn->begin_transaction();

                        // Sanitize and prepare basic form data
                        $savings_account = isset($_POST['savings_account']) ? 1 : 0;
                        $current_account = isset($_POST['current_account']) ? 1 : 0;

                        $savings_bank = $savings_account ? filter_var($_POST['savings_bank'], FILTER_SANITIZE_STRING) : '';
                        $savings_branch = $savings_account ? filter_var($_POST['savings_branch'], FILTER_SANITIZE_STRING) : '';
                        $current_bank = $current_account ? filter_var($_POST['current_bank'], FILTER_SANITIZE_STRING) : '';
                        $current_branch = $current_account ? filter_var($_POST['current_branch'], FILTER_SANITIZE_STRING) : '';

                        // Update basic account information
                        $basic_query = "UPDATE loanapplication SET 
                                savings_account = ?,
                                savings_bank = ?,
                                savings_branch = ?,
                                current_account = ?,
                                current_bank = ?,
                                current_branch = ?
                                WHERE LoanID = ?";

                        $basic_stmt = $conn->prepare($basic_query);
                        $basic_stmt->bind_param(
                            "ississi",
                            $savings_account,
                            $savings_bank,
                            $savings_branch,
                            $current_account,
                            $current_bank,
                            $current_branch,
                            $loan_id
                        );
                        $basic_stmt->execute();
                        $basic_stmt->close();

                        // Process assets and their documents
                        $upload_dir = '../dist/assets/images/proofs/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }

                        // Initialize asset update query parts
                        $asset_updates = [];
                        $asset_types = "";
                        $asset_params = [];

                        // Process each potential asset
                        for ($i = 1; $i <= 4; $i++) {
                            $asset_key = "assets{$i}";
                            if (isset($_POST[$asset_key]) && !empty($_POST[$asset_key])) {
                                $asset_value = filter_var($_POST[$asset_key], FILTER_SANITIZE_STRING);
                                $asset_updates[] = "$asset_key = ?";
                                $asset_types .= "s";
                                $asset_params[] = $asset_value;

                                // Process deed of sale if uploaded
                                $deed_key = "deed_of_sale{$i}";
                                if (isset($_FILES[$deed_key]) && $_FILES[$deed_key]['error'] === 0) {
                                    $file_info = pathinfo($_FILES[$deed_key]['name']);
                                    $new_filename = 'deed_' . uniqid() . '.' . $file_info['extension'];
                                    $upload_path = $upload_dir . $new_filename;

                                    if (move_uploaded_file($_FILES[$deed_key]['tmp_name'], $upload_path)) {
                                        $asset_updates[] = "deed_of_sale{$i} = ?";
                                        $asset_types .= "s";
                                        $asset_params[] = $new_filename;
                                    }
                                }

                                // Process ORCR if uploaded
                                $orcr_key = "orcr_vehicle{$i}";
                                if (isset($_FILES[$orcr_key]) && $_FILES[$orcr_key]['error'] === 0) {
                                    $file_info = pathinfo($_FILES[$orcr_key]['name']);
                                    $new_filename = 'orcr_' . uniqid() . '.' . $file_info['extension'];
                                    $upload_path = $upload_dir . $new_filename;

                                    if (move_uploaded_file($_FILES[$orcr_key]['tmp_name'], $upload_path)) {
                                        $asset_updates[] = "orcr_vehicle{$i} = ?";
                                        $asset_types .= "s";
                                        $asset_params[] = $new_filename;
                                    }
                                }
                            }
                        }

                        // If there are assets to update
                        if (!empty($asset_updates)) {
                            $asset_query = "UPDATE loanapplication SET " . implode(", ", $asset_updates) . " WHERE LoanID = ?";
                            $asset_types .= "i"; // for LoanID
                            $asset_params[] = $loan_id;

                            $asset_stmt = $conn->prepare($asset_query);
                            $asset_stmt->bind_param($asset_types, ...$asset_params);
                            $asset_stmt->execute();
                            $asset_stmt->close();
                        }

                        $conn->commit();
                        header("Location: regular-form4.php?loanType=" . urlencode($loan_type));
                        exit();

                    } catch (Exception $e) {
                        $conn->rollback();
                        error_log("Loan application update error: " . $e->getMessage());
                        echo "<script>
                                    alert('Error updating loan application: " . addslashes($e->getMessage()) . "');
                                    window.history.back();
                                </script>";
                        exit();
                    }
                }
                ob_flush();
                ?>
                <div class="container">


                    <div class="form-container">
                        <h1 class="heading"><?php echo ucfirst($loan_type); ?> Loan Application - Step 3</h1>
                        <form action="regular-form3.php" method="post" enctype="multipart/form-data">
                            <div class="row mb-4">
                                <h6>Savings and Current Account Information</h6>
                                <!-- Savings Account Section -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="savings_account">Do you have a savings account?</label>
                                        <input type="checkbox" id="savings_account" name="savings_account">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="savings_bank" class="form-label">Savings Bank Name</label>
                                        <input type="text" class="form-control" name="savings_bank" id="savings_bank">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="savings_branch" class="form-label">Savings Bank Branch</label>
                                        <input type="text" class="form-control" name="savings_branch"
                                            id="savings_branch">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <!-- Current Account Section -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="current_account">Do you have a current account?</label>
                                        <input type="checkbox" id="current_account" name="current_account">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="current_bank" class="form-label">Current Bank Name</label>
                                        <input type="text" class="form-control" name="current_bank" id="current_bank">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="current_branch" class="form-label">Current Bank Branch</label>
                                        <input type="text" class="form-control" name="current_branch"
                                            id="current_branch">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="assets1" class="form-label">Assets 1<span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="assets1" id="assets1" required
                                            placeholder="Enter asset type (e.g., Property, Vehicle)">
                                    </div>
                                </div>
                                <div class="col-md-8 document-upload-1">
                                    <!-- Dynamic document upload fields will be inserted here -->
                                </div>
                            </div>

                            <div id="additional-assets"></div>

                            <div class="row mb-4">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-primary add-assets">Add More Assets</button>
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
            // Hide bank fields initially
            $('#savings_bank, #savings_branch').closest('.form-group').hide();
            $('#current_bank, #current_branch').closest('.form-group').hide();

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

            // File input validation function
            function validateFileInput(input) {
                const formGroup = $(input).closest('.form-group');
                formGroup.find('.validation-message').remove();

                if (input.files && input.files[0]) {
                    $(input).addClass('is-valid').removeClass('is-invalid');
                    return true;
                } else {
                    $(input).addClass('is-invalid').removeClass('is-valid');
                    formGroup.append('<div class="validation-message text-danger small mt-1">Please select a file</div>');
                    return false;
                }
            }

            // Add file input validation handlers
            function addFileValidation(fileInput) {
                $(fileInput).on('change', function () {
                    validateFileInput(this);
                });
            }

            // Initialize file validation for existing inputs
            $('input[type="file"]').each(function () {
                addFileValidation(this);
            });

            // Validation functions
            const validationRules = {
                textField: (value) => /^[a-zA-Z\s\p{P}]+$/u.test(value.trim()),
                positiveNumber: (value) => !isNaN(parseFloat(value)) && parseFloat(value) > 0,
                selectField: (value) => value && value !== '',
                dateField: (value) => value ? true : false
            };

            // Function to create document upload fields for an asset
            function createDocumentUploadFields(assetNumber) {
                return `
            <div class="col-md-6">
                <div class="form-group deed-upload-${assetNumber}" style="display: none;">
                    <label for="deed_of_sale${assetNumber}" class="form-label">Deed Of Sale (If Property)</label>
                    <input type="file" class="form-control" name="deed_of_sale${assetNumber}" 
                        id="deed_of_sale${assetNumber}" accept=".pdf,.jpg,.jpeg,.png,.gif">
                </div>
                <div class="form-group orcr-upload-${assetNumber}" style="display: none;">
                    <label for="orcr_vehicle${assetNumber}" class="form-label">ORCR For Vehicles</label>
                    <input type="file" class="form-control" name="orcr_vehicle${assetNumber}" 
                        id="orcr_vehicle${assetNumber}" accept=".pdf,.jpg,.jpeg,.png,.gif">
                </div>
            </div>`;
            }

            // Function to handle asset type selection and show/hide relevant upload fields
            function handleAssetTypeChange(assetNumber) {
                $(`#assets${assetNumber}`).on('input', function () {
                    const assetValue = $(this).val().toLowerCase();
                    const deedUpload = $(`.deed-upload-${assetNumber}`);
                    const orcrUpload = $(`.orcr-upload-${assetNumber}`);

                    // Reset required attributes and validation
                    const deedFile = $(`#deed_of_sale${assetNumber}`);
                    const orcrFile = $(`#orcr_vehicle${assetNumber}`);

                    deedFile.prop('required', false).removeClass('is-valid is-invalid');
                    orcrFile.prop('required', false).removeClass('is-valid is-invalid');

                    // Hide both upload sections initially
                    deedUpload.hide();
                    orcrUpload.hide();

                    // Extended property types
                    const propertyTypes = [
                        'property', 'house', 'lot', 'land', 'apartment',
                        'condo', 'condominium', 'townhouse', 'building',
                        'commercial space', 'office space', 'warehouse',
                        'farm', 'residential', 'commercial'
                    ];

                    // Extended vehicle types
                    const vehicleTypes = [
                        'vehicle', 'car', 'motorcycle', 'truck', 'van',
                        'suv', 'pickup', 'bus', 'trailer', 'boat',
                        'atv', 'scooter', 'tricycle', 'jeep', 'jeepney'
                    ];

                    const isProperty = propertyTypes.some(type => assetValue.includes(type));
                    const isVehicle = vehicleTypes.some(type => assetValue.includes(type));

                    if (isProperty) {
                        deedUpload.show();
                        deedFile.prop('required', true);
                    } else if (isVehicle) {
                        orcrUpload.show();
                        orcrFile.prop('required', true);
                    }
                });
            }

            // Initialize document upload fields and validation for first asset
            $('.document-upload-1').html(createDocumentUploadFields(1));
            handleAssetTypeChange(1);
            addFileValidation('#deed_of_sale1');
            addFileValidation('#orcr_vehicle1');
            $('#assets1').trigger('input');

            // Validation for bank fields
            const bankFieldsToValidate = [
                { selector: '#savings_bank', validate: validationRules.textField, message: 'Savings bank name is required' },
                { selector: '#savings_branch', validate: validationRules.textField, message: 'Savings bank branch is required' },
                { selector: '#current_bank', validate: validationRules.textField, message: 'Current bank name is required' },
                { selector: '#current_branch', validate: validationRules.textField, message: 'Current bank branch is required' },
                { selector: '#assets1', validate: validationRules.textField, message: 'Assets description is required' }
            ];

            // Apply validation to bank fields
            bankFieldsToValidate.forEach(field => {
                validateInput(field.selector, field.validate, field.message);
            });

            // Checkbox behavior for savings account
            $('#savings_account').on('change', function () {
                const isSavingsChecked = $(this).is(':checked');
                const savingsFields = $('#savings_bank, #savings_branch').closest('.form-group');

                if (isSavingsChecked) {
                    savingsFields.slideDown();
                    $('#savings_bank, #savings_branch').prop('required', true);
                } else {
                    savingsFields.slideUp();
                    $('#savings_bank, #savings_branch')
                        .prop('required', false)
                        .val('')
                        .removeClass('is-valid is-invalid');
                    savingsFields.find('.validation-message').remove();
                }
            });

            // Checkbox behavior for current account
            $('#current_account').on('change', function () {
                const isCurrentChecked = $(this).is(':checked');
                const currentFields = $('#current_bank, #current_branch').closest('.form-group');

                if (isCurrentChecked) {
                    currentFields.slideDown();
                    $('#current_bank, #current_branch').prop('required', true);
                } else {
                    currentFields.slideUp();
                    $('#current_bank, #current_branch')
                        .prop('required', false)
                        .val('')
                        .removeClass('is-valid is-invalid');
                    currentFields.find('.validation-message').remove();
                }
            });

            // Add more assets functionality
            let assetCount = 1;
            $('.add-assets').click(function () {
                assetCount++;
                const newAssetHtml = `
            <div class="row mb-4 asset-row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="assets${assetCount}" class="form-label">Assets ${assetCount}</label>
                        <input type="text" class="form-control" name="assets${assetCount}" 
                            id="assets${assetCount}" required
                            placeholder="Enter asset type (e.g., Property, Vehicle)">
                    </div>
                </div>
                ${createDocumentUploadFields(assetCount)}
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-asset mt-4">Remove</button>
                </div>
            </div>
        `;
                $('#additional-assets').append(newAssetHtml);

                validateInput(`#assets${assetCount}`, validationRules.textField, `Assets ${assetCount} description is required`);
                handleAssetTypeChange(assetCount);

                addFileValidation(`#deed_of_sale${assetCount}`);
                addFileValidation(`#orcr_vehicle${assetCount}`);
                $(`#assets${assetCount}`).trigger('input');
            });

            // Remove asset handler
            $(document).on('click', '.remove-asset', function () {
                $(this).closest('.asset-row').remove();
                assetCount--;
            });

            // Form submission validation
            $('form').on('submit', function (e) {
                const propertyTypes = [
                    'property', 'house', 'lot', 'land', 'apartment',
                    'condo', 'condominium', 'townhouse', 'building',
                    'commercial space', 'office space', 'warehouse',
                    'farm', 'residential', 'commercial'
                ];

                const vehicleTypes = [
                    'vehicle', 'car', 'motorcycle', 'truck', 'van',
                    'suv', 'pickup', 'bus', 'trailer', 'boat',
                    'atv', 'scooter', 'tricycle', 'jeep', 'jeepney'
                ];

                // Validate all asset fields and their required documents
                for (let i = 1; i <= assetCount; i++) {
                    const assetValue = $(`#assets${i}`).val().toLowerCase();
                    const deedFile = $(`#deed_of_sale${i}`);
                    const orcrFile = $(`#orcr_vehicle${i}`);

                    const isProperty = propertyTypes.some(type => assetValue.includes(type));
                    const isVehicle = vehicleTypes.some(type => assetValue.includes(type));

                    if (isProperty && deedFile.prop('required') && !deedFile.val()) {
                        e.preventDefault();
                        validateFileInput(deedFile[0]);
                        return;
                    }

                    if (isVehicle && orcrFile.prop('required') && !orcrFile.val()) {
                        e.preventDefault();
                        validateFileInput(orcrFile[0]);
                        return;
                    }
                }

                // Validate bank fields only if their respective checkboxes are checked
                if ($('#savings_account').is(':checked')) {
                    if (!$('#savings_bank').val() || !$('#savings_branch').val()) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Savings Account',
                            text: 'Please provide savings bank name and branch.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                }

                if ($('#current_account').is(':checked')) {
                    if (!$('#current_bank').val() || !$('#current_branch').val()) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Current Account',
                            text: 'Please provide current bank name and branch.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                }

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