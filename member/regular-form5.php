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
    <title>Regular Loan Form 5 | Member</title>
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

                    // Debug session and login status
                    if (!isset($_SESSION['user_id'])) {
                        header("Location: member-login.php");
                        exit();
                    }

                    $user_id = $_SESSION['user_id'];

                    // Get the loan type from the URL
                    $loan_type = filter_input(INPUT_GET, 'loanType', FILTER_SANITIZE_STRING) ?? 'Regular';

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
                            // Prepare columns to update
                            $columns_to_update = [];
                            $params = [];
                            $types = '';

                            // Enhanced validation function for both references and comaker
                            function validatePerson($name, $address, $contact, $type = 'reference')
                            {
                                // Name: Only letters, spaces, and hyphens
                                if (!preg_match('/^[a-zA-Z\s\-\']+$/', $name)) {
                                    throw new Exception("Invalid {$type} name format");
                                }

                                // Address: Allow more flexible address input
                                if (empty(trim($address)) || strlen(trim($address)) < 5) {
                                    throw new Exception("Invalid {$type} address");
                                }

                                // Contact: Allow for international phone number formats
                                if (!preg_match('/^[+]?[\s\d\-()]{8,20}$/', $contact)) {
                                    throw new Exception("Invalid {$type} contact number format");
                                }

                                return true;
                            }

                            // Validate and sanitize reference information
                            for ($i = 1; $i <= 3; $i++) {
                                $name_key = "reference{$i}_name";
                                $address_key = "reference{$i}_address";
                                $contact_key = "reference{$i}_contact_no";

                                if (isset($_POST[$name_key]) && $_POST[$name_key] !== '') {
                                    validatePerson(
                                        $_POST[$name_key],
                                        $_POST[$address_key],
                                        $_POST[$contact_key],
                                        "reference {$i}"
                                    );

                                    $columns_to_update[] = "{$name_key} = ?";
                                    $params[] = trim($_POST[$name_key]);
                                    $types .= 's';

                                    $columns_to_update[] = "{$address_key} = ?";
                                    $params[] = trim($_POST[$address_key]);
                                    $types .= 's';

                                    $columns_to_update[] = "{$contact_key} = ?";
                                    $params[] = trim($_POST[$contact_key]);
                                    $types .= 's';
                                }
                            }

                            // Validate and sanitize comaker information
                            if (isset($_POST['comaker_name']) && $_POST['comaker_name'] !== '') {
                                validatePerson(
                                    $_POST['comaker_name'],
                                    $_POST['comaker_address'],
                                    $_POST['comaker_contact_no'],
                                    'comaker'
                                );

                                $columns_to_update[] = "comaker_name = ?";
                                $params[] = trim($_POST['comaker_name']);
                                $types .= 's';

                                $columns_to_update[] = "comaker_address = ?";
                                $params[] = trim($_POST['comaker_address']);
                                $types .= 's';

                                $columns_to_update[] = "comaker_contact_no = ?";
                                $params[] = trim($_POST['comaker_contact_no']);
                                $types .= 's';
                            }

                            // If no columns to update, throw an error
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
                                header("Location: regular-form6.php?loanType=" . urlencode($loan_type));
                                exit();
                            } else {
                                throw new Exception("Database update failed: " . $stmt->error);
                            }
                        } catch (Exception $e) {
                            error_log("Loan Application Update Error: " . $e->getMessage());
                            echo "<script>
                                    alert('Error updating loan application: " . addslashes($e->getMessage()) . "');
                                    history.back();
                                </script>";
                            exit();
                        }
                    }
                    ob_flush();
                    ?>
                    <div class="container">
                        <div class="form-container">
                        <h1 class="heading"><?php echo ucfirst($loan_type); ?> Loan Application - Reference Information</h1>
                            <form action="regular-form5.php" method="post" id="referenceForm">
                            <div>
                                <div class="row mb-4">
                                    <h6>Reference Information</h6>
                                    
                                    <!-- Reference 1 -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reference1_name" class="form-label">Reference 1 Name<span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="reference1_name" 
                                                id="reference1_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reference1_address" class="form-label">Reference 1 Address<span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="reference1_address" 
                                                id="reference1_address" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reference1_contact_no" class="form-label">Reference 1 Contact Number<span style="color: red;">*</span></label>
                                            <input type="tel" class="form-control" name="reference1_contact_no" 
                                                id="reference1_contact_no" required>
                                        </div>
                                    </div>
                                    <!-- Reference 1 -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reference2_name" class="form-label">Reference 2 Name<span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="reference2_name" 
                                                id="reference2_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reference2_address" class="form-label">Reference 2 Address<span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="reference2_address" 
                                                id="reference2_address" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reference2_contact_no" class="form-label">Reference 2 Contact Number<span style="color: red;">*</span></label>
                                            <input type="tel" class="form-control" name="reference2_contact_no" 
                                                id="reference2_contact_no" required>
                                        </div>
                                    </div>
                                    <!-- Reference 1 -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reference3_name" class="form-label">Reference 3 Name<span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="reference3_name" 
                                                id="reference3_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reference3_address" class="form-label">Reference 3 Address<span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="reference3_address" 
                                                id="reference3_address" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reference3_contact_no" class="form-label">Reference 3 Contact Number<span style="color: red;">*</span></label>
                                            <input type="tel" class="form-control" name="reference3_contact_no" 
                                                id="reference3_contact_no" required>
                                        </div>
                                    </div>
                                    </div>

                                    <div class="row mb-4">
                                    <h6>Comaker Information</h6>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="comaker_name" class="form-label">Comaker Name<span style="color: red;">*</span></label>
                                            <input type="tel" class="form-control" name="comaker_name"
                                                id="comaker_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="comaker_address" class="form-label">Comaker Address<span style="color: red;">*</span></label>
                                            <input type="tel" class="form-control" name="comaker_address"
                                                id="comaker_address" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="comaker_contact_no" class="form-label">Comaker Contact No.<span style="color: red;">*</span></label>
                                            <input type="tel" class="form-control" name="comaker_contact_no"
                                                id="comaker_contact_no" required>
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
     $(document).ready(function() {
        // Validation rules
        const validationRules = {
            nameField: (value) => /^[a-zA-Z\s\-\']+$/.test(value.trim()),
            addressField: (value) => value.trim().length > 5,
            phoneField: (value) => /^[+]?[\s\d\-()]{8,20}$/.test(value.trim())
        };

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
            $(selector).off('input change').on('input change', function() {
                const value = $(this).val();
                const isValid = validateFn(value);
                showValidation(this, isValid, errorMessage);
            });
        }

        // Validate reference fields
        function validateReferenceFields(referenceCount) {
            const fieldTypes = [
                {
                    type: 'name',
                    validate: validationRules.nameField,
                    message: 'name must contain only letters'
                },
                {
                    type: 'address',
                    validate: validationRules.addressField,
                    message: 'address must be at least 5 characters'
                },
                {
                    type: 'contact_no',
                    validate: validationRules.phoneField,
                    message: 'contact number must be in valid format'
                }
            ];

            fieldTypes.forEach(field => {
                const selector = `#reference${referenceCount}_${field.type}`;
                validateInput(
                    selector,
                    field.validate,
                    `Reference ${referenceCount} ${field.message}`
                );

                // Trigger initial validation if field has a value
                const element = $(selector);
                if (element.val()) {
                    element.trigger('change');
                }
            });
        }

        // Validate comaker fields
        function validateComakerFields() {
            const fieldTypes = [
                {
                    type: 'name',
                    validate: validationRules.nameField,
                    message: 'name must contain only letters'
                },
                {
                    type: 'address',
                    validate: validationRules.addressField,
                    message: 'address must be at least 5 characters'
                },
                {
                    type: 'contact_no',
                    validate: validationRules.phoneField,
                    message: 'contact number must be in valid format'
                }
            ];

            fieldTypes.forEach(field => {
                const selector = `#comaker_${field.type}`;
                validateInput(
                    selector,
                    field.validate,
                    `Comaker ${field.message}`
                );

                const element = $(selector);
                if (element.val()) {
                    element.trigger('change');
                }
            });
        }

        // Initial validation for existing references
        $('.form-control[id^="reference"]').each(function() {
            const refNumber = this.id.match(/reference(\d+)_/)[1];
            validateReferenceFields(refNumber);
        });

        // Initial validation for comaker fields
        validateComakerFields();

        // Add more references functionality
        let referenceCount = $('.reference-row').length || 1;
        $('.add-reference').click(function() {
            if (referenceCount >= 4) {
                Swal.fire({
                    title: 'Limit Reached',
                    text: 'You can add up to 3 additional references.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            referenceCount++;
            const newReferenceHtml = `
                <div class="row mb-4 reference-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="reference${referenceCount}_name" class="form-label">Reference ${referenceCount} Name</label>
                            <input type="text" class="form-control" name="reference${referenceCount}_name" 
                                id="reference${referenceCount}_name" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="reference${referenceCount}_address" class="form-label">Reference ${referenceCount} Address</label>
                            <input type="text" class="form-control" name="reference${referenceCount}_address" 
                                id="reference${referenceCount}_address" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="reference${referenceCount}_contact_no" class="form-label">Reference ${referenceCount} Contact Number</label>
                            <input type="tel" class="form-control" name="reference${referenceCount}_contact_no" 
                                id="reference${referenceCount}_contact_no" required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-reference mt-4">Remove</button>
                    </div>
                </div>
            `;
            $('#additional-references').append(newReferenceHtml);
            validateReferenceFields(referenceCount);
        });

        // Remove reference
        $(document).on('click', '.remove-reference', function() {
            $(this).closest('.reference-row').remove();
            referenceCount--;
        });

        // Form submission validation
        $('#referenceForm').on('submit', function(e) {
            let isValid = true;

            // Validate all visible reference fields
            $('.form-control[id^="reference"]').each(function() {
                const field = $(this);
                const [, refNumber, fieldType] = field.attr('id').match(/reference(\d+)_(\w+)/);

                let validationFn;
                let message;

                switch (fieldType) {
                    case 'name':
                        validationFn = validationRules.nameField;
                        message = `Reference ${refNumber} name must contain only letters`;
                        break;
                    case 'address':
                        validationFn = validationRules.addressField;
                        message = `Reference ${refNumber} address must be at least 5 characters`;
                        break;
                    case 'contact_no':
                        validationFn = validationRules.phoneField;
                        message = `Reference ${refNumber} contact number must be in valid format`;
                        break;
                }

                const fieldValid = validationFn(field.val());
                showValidation(field, fieldValid, message);

                if (!fieldValid) {
                    isValid = false;
                }
            });

            // Validate comaker fields
            $('.form-control[id^="comaker_"]').each(function() {
                const field = $(this);
                const fieldType = field.attr('id').replace('comaker_', '');

                let validationFn;
                let message;

                switch (fieldType) {
                    case 'name':
                        validationFn = validationRules.nameField;
                        message = 'Comaker name must contain only letters';
                        break;
                    case 'address':
                        validationFn = validationRules.addressField;
                        message = 'Comaker address must be at least 5 characters';
                        break;
                    case 'contact_no':
                        validationFn = validationRules.phoneField;
                        message = 'Comaker contact number must be in valid format';
                        break;
                }

                const fieldValid = validationFn(field.val());
                showValidation(field, fieldValid, message);

                if (!fieldValid) {
                    isValid = false;
                }
            });

            if (!isValid) {
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