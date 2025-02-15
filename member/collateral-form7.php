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
    <title>Collateral Loan Form 6 | Member</title>
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

                // Debug session and login status
                if (!isset($_SESSION['user_id'])) {
                    header("Location: member-login.php");
                    exit();
                }

                $user_id = $_SESSION['user_id'];

                // Get the loan type from the URL
                $loan_type = filter_input(INPUT_GET, 'loanType', FILTER_SANITIZE_STRING) ?? 'Collateral';

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

                        // Define upload directory
                        $upload_dir = '../dist/assets/images/proofs/';

                        // Ensure upload directory exists
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0755, true);
                        }

                        // Allowed file types
                        $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif'];
                        $allowed_pdf_types = ['application/pdf'];

                        // Files to upload
                        $file_fields = [
                            'validID' => 'valid_id',
                            'proof_of_income' => 'proof_of_income',
                            'tax_declaration' => 'tax_declaration',
                            'tax_clearance' => 'tax_clearance',
                            'original_transfer_certificate_of_title' => 'original_transfer_certificate_of_title',
                            'certified_true_copy' => 'certified_true_copy',
                            'vicinity_map' => 'vicinity_map',
                            'barangay_clearance' => 'barangay_clearance',
                            'cedula' => 'cedula',
                            'post_dated_check' => 'post_dated_check',
                            'promisory_note' => 'promisory_note'
                        ];

                        // Process file uploads
                        foreach ($file_fields as $form_field => $db_column) {
                            if (isset($_FILES[$form_field]) && $_FILES[$form_field]['error'] === UPLOAD_ERR_OK) {
                                $file = $_FILES[$form_field];
                                
                                // Validate file type
                                $file_type = mime_content_type($file['tmp_name']);
                                $is_image = in_array($file_type, $allowed_image_types);
                                $is_pdf = in_array($file_type, $allowed_pdf_types);
                                
                                if (!($is_image || $is_pdf)) {
                                    throw new Exception("Invalid file type for $form_field. Only images (JPEG, PNG, GIF) and PDFs are allowed.");
                                }

                                // Generate unique filename
                                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                                $new_filename = $db_column . '_' . $loan_id . '_' . uniqid() . '.' . $file_extension;
                                $upload_path = $upload_dir . $new_filename;

                                // Move uploaded file
                                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                                    // Add to columns to update
                                    $columns_to_update[] = "{$db_column}_path = ?";
                                    $params[] = $new_filename;
                                    $types .= 's';
                                } else {
                                    throw new Exception("Failed to upload file: $form_field");
                                }
                            } else {
                                // Check if file upload failed
                                if ($_FILES[$form_field]['error'] !== UPLOAD_ERR_NO_FILE) {
                                    throw new Exception("File upload error for $form_field: " . $_FILES[$form_field]['error']);
                                }
                            }
                        }

                        // If no files to update, throw an error
                        if (empty($columns_to_update)) {
                            throw new Exception("No valid files to upload");
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
                        
                        // Dynamically bind parameters
                        $bind_params = array_merge(array($stmt, $types), $params);
                        call_user_func_array('mysqli_stmt_bind_param', $bind_params);

                        if ($stmt->execute()) {
                            $stmt->close();
                            // Redirect to next form step
                            header("Location: collateral-form8.php?loanType=" . urlencode($loan_type));
                            exit();
                        } else {
                            throw new Exception("Database update failed: " . $stmt->error);
                        }
                    } catch (Exception $e) {
                        // Log the full error for debugging
                        error_log("Loan Application Document Upload Error: " . $e->getMessage());
                        
                        echo "<script>
                                alert('Error uploading documents: " . addslashes($e->getMessage()) . "');
                                history.back();
                            </script>";
                        exit();
                    }
                }
                ob_flush();
                ?>
                <div class="container">
                    <div class="form-container">
                    <h1 class="heading"><?php echo ucfirst($loan_type); ?> Loan Application - Document Submission</h1>
                        <form action="collateral-form7.php" method="post" enctype="multipart/form-data" id="documentForm">
                            <div class="row mb-4">
                                <h6>Document Submission</h6>
                                
                                <!-- Document Inputs -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="validID" class="form-label">Valid ID with Signature</label>
                                        <input type="file" class="form-control" name="validID" 
                                            id="validID" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="proof_of_income" class="form-label">Proof Of Income</label>
                                        <input type="file" class="form-control" name="proof_of_income" 
                                            id="proof_of_income" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tax_declaration" class="form-label">Latest Tax Declarion</label>
                                        <input type="file" class="form-control" name="tax_declaration" 
                                            id="tax_declaration" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tax_clearance" class="form-label">Latest Tax Clearance</label>
                                        <input type="file" class="form-control" name="tax_clearance" 
                                            id="tax_clearance" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="original_transfer_certificate_of_title" class="form-label">Orig Transfer Certificate Of Title(TCT)</label>
                                        <input type="file" class="form-control" name="original_transfer_certificate_of_title" 
                                            id="original_transfer_certificate_of_title" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="certified_true_copy" class="form-label">Certified True Copy (TCT)</label>
                                        <input type="file" class="form-control" name="certified_true_copy" 
                                            id="certified_true_copy" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="vicinity_map" class="form-label">Orig Loc. Plan & Vicinity Map</label>
                                        <input type="file" class="form-control" name="vicinity_map" 
                                            id="vicinity_map" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="barangay_clearance" class="form-label">Barangay Clearance</label>
                                        <input type="file" class="form-control" name="barangay_clearance" 
                                            id="barangay_clearance" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cedula" class="form-label">Cedula</label>
                                        <input type="file" class="form-control" name="cedula" 
                                            id="cedula" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="post_dated_check" class="form-label">Post Dated Check (PDC)</label>
                                        <input type="file" class="form-control" name="post_dated_check" 
                                            id="post_dated_check" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="promisory_note" class="form-label">Promisory Note</label>
                                        <input type="file" class="form-control" name="promisory_note" 
                                            id="promisory_note" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
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
        $(document).ready(function () {
            // Configuration
            const maxFileSize = 5 * 1024 * 1024; // 5MB max file size
            const allowedFileTypes = {
                'pdf': 'application/pdf',
                'jpg': 'image/jpeg',
                'jpeg': 'image/jpeg',
                'png': 'image/png',
                'gif': 'image/gif'
            };

            // Function to show validation message (remains the same)
            function showValidation(element, isValid, message) {
                const formGroup = $(element).closest('.form-group');
                formGroup.find('.validation-message').remove();

                if (!isValid) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                    formGroup.append(`<div class="validation-message text-danger small mt-1">${message}</div>`);
                } else {
                    $(element).addClass('is-valid').removeClass('is-invalid');
                }
            }

            // File validation function (remains the same)
            function validateFile(file, isRequired = true) {
                if (!file && !isRequired) {
                    return { valid: true, message: '' };
                }

                if (!file && isRequired) {
                    return { valid: false, message: 'This document is required' };
                }

                if (file.size > maxFileSize) {
                    return { valid: false, message: 'File size must be less than 5MB' };
                }

                const fileExtension = file.name.split('.').pop().toLowerCase();
                const fileType = file.type;
                
                if (!allowedFileTypes[fileExtension] || allowedFileTypes[fileExtension] !== fileType) {
                    return { valid: false, message: 'Invalid file type. Allowed types: PDF, JPG, PNG, GIF' };
                }

                return { valid: true, message: '' };
            }

            // Add validation on file input change
            $('input[type="file"]').on('change', function() {
                const file = this.files[0];
                const isRequired = $(this).prop('required');
                const validation = validateFile(file, isRequired);
                showValidation(this, validation.valid, validation.message);
            });

            // Form submission validation
            $('#documentForm').on('submit', function(e) {
                let isValid = true;
                const requiredMessage = 'This document is required';
                
                // Validate all required file inputs
                const requiredInputs = {
                    'validID': 'Valid ID',
                    'proof_of_income': 'Proof of Income',
                    'tax_declaration': 'Tax Declaration',
                    'tax_clearance': 'Tax Clearance',
                    'original_transfer_certificate_of_title': 'Original Transfer Certificate',
                    'certified_true_copy': 'Certified True Copy',
                    'vicinity_map': 'Vicinity Map',
                    'barangay_clearance': 'Barangay Clearance',
                    'cedula': 'Cedula',
                    'post_dated_check': 'Post Dated Check',
                    'promisory_note': 'Promisory Note'
                };

                Object.keys(requiredInputs).forEach(inputId => {
                    const file = $(`#${inputId}`)[0].files[0];
                    const validation = validateFile(file);
                    showValidation(`#${inputId}`, validation.valid, validation.message || requiredMessage);
                    if (!validation.valid) isValid = false;
                });

                // Prevent form submission if validation fails
                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Form Validation',
                        text: 'Please check all documents and correct any errors.',
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