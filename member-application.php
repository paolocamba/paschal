<?php
session_start();
include 'connection/config.php';

// Function to log activity
function logActivity($user_id, $user_type, $activity) {
    global $conn;
    $sql = "INSERT INTO activity_logs (user_id, user_type, action, details) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $user_type, $activity, $activity);
    $stmt->execute();
}

// Function to get next user ID
function getNextUserId($conn) {
    $query = "SELECT MAX(CAST(user_id AS UNSIGNED)) AS max_id FROM users";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = $row['max_id'] ? intval($row['max_id']) : 0;
        return strval($lastId + 1);
    }
    return '1';
}

// Function to determine user type
function determineUserType($conn) {
    $checkAdmin = "SELECT COUNT(*) as count FROM users WHERE user_type = 'Admin'";
    $result = $conn->query($checkAdmin);
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        return array('user_type' => 'Admin', 'status' => 1);
    }
    return array('user_type' => 'Member', 'status' => 0);
}

// Process registration form
if (isset($_POST['register'])) {
    // Basic information
    $first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middlename']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    
    // Address information
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $municipality = mysqli_real_escape_string($conn, $_POST['municipality']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    
    // Contact and account information
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];
    
    // Additional information
    $tin_number = mysqli_real_escape_string($conn, $_POST['tin-number']);
    
    // Handle TIN ID image upload
    $tin_id_image = $_FILES['tinIdImage'];
    $upload_dir = "dist/assets/images/tin_id/";
    $tin_id_filename = time() . '_' . $tin_id_image['name'];
    $upload_path = $upload_dir . $tin_id_filename;

            // Store all form data except password for security
            $_SESSION['form_data'] = array_filter($_POST, function($key) {
                return !in_array($key, ['password', 'confirmPassword']);
            }, ARRAY_FILTER_USE_KEY);
            
            // Store file info
            $_SESSION['form_data']['tinIdImage_name'] = $_FILES['tinIdImage']['name'];

    // Validate age
    if ($age < 18) {
        $_SESSION['error'] = 'You must be 18 years or older to register';
        header('Location: member-application.php');
        exit();
    }

    // Validate TIN number
    if (strlen($tin_number) != 9 || !ctype_digit($tin_number)) {
        $_SESSION['error'] = 'TIN number must be exactly 9 digits';
        header('Location: member-application.php');
        exit();
    }

    // Validate password match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Passwords do not match';
        header('Location: member-application.php');
        exit();
    }

    // Hash password
    $password = password_hash($password, PASSWORD_DEFAULT);
    $user_id = getNextUserId($conn);
    $user_type_info = determineUserType($conn);
    $user_type = $user_type_info['user_type'];
    $status = $user_type_info['status'];

    // Check for existing email
$checkEmail = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($checkEmail);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error'] = 'Email is already registered!';
    header('Location: member-application.php');
    exit();
}

// Check for existing mobile
$checkMobile = "SELECT * FROM users WHERE mobile = ?";
$stmt = $conn->prepare($checkMobile);
$stmt->bind_param("s", $mobile);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error'] = 'Mobile number is already registered!';
    header('Location: member-application.php');
    exit();
}

// Check for existing TIN
$checkTIN = "SELECT * FROM users WHERE tin_number = ?";
$stmt = $conn->prepare($checkTIN);
$stmt->bind_param("s", $tin_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error'] = 'TIN number is already registered!';
    header('Location: member-application.php');
    exit();
}

    // Start transaction
    $conn->begin_transaction();

    try {
        // Move uploaded file
        if (!move_uploaded_file($tin_id_image['tmp_name'], $upload_path)) {
            throw new Exception("Failed to upload TIN ID image");
        }

        // Insert into users table
        $insert = "INSERT INTO users (user_id, first_name, middle_name, last_name, gender, 
            birthday, age, street, barangay, municipality, province, email, mobile, 
            password, tin_number, tin_id_image, user_type, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("sssssssssssssssssi", 
            $user_id, $first_name, $middle_name, $last_name, $gender,
            $birthday, $age, $street, $barangay, $municipality, $province,
            $email, $mobile, $password, $tin_number, $tin_id_filename,
            $user_type, $status);
        $stmt->execute();

        // Insert into member_applications table
        $fillUpForm = 1;
        $watchedVideoSeminar = 0;
        $status = "In Progress";
        $membershipFee = 0.00;

        $insert_application = "INSERT INTO member_applications 
            (user_id, fillupform, watchvideoseminar, status) 
            VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_application);
        $stmt->bind_param("siis", 
            $user_id, $fillUpForm, $watchedVideoSeminar, $status);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        // Log activity
        logActivity($user_id, $user_type, 'Registered');

        // Redirect to video seminar page
        header('Location: videoseminar.php?member-id=' . $user_id);
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
        header('Location: member-application.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PASCHAL Multi-Purpose Cooperative</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00FFAF;
            --secondary-color: #0F4332;
            --dark-color: #1f1f1f;
            --accent-color: #00FFAF;
        }

        input[name="tin-number"] {
    letter-spacing: 1px;
}

        /* Make error messages more visible */
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        .input-error {
            border-color: #dc3545 !important;
            background-color: #fff5f5;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* Enhanced Navigation */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 15px 0;
        }

        .navbar-brand .navbar-text {
            color: white;
            font-weight: bold;
            font-size: 1.25rem;
            vertical-align: middle;
        }

        .navbar-brand img {
            height: 60px;
            transition: transform 0.3s;
        }

        .navbar-brand img:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 15px;
            padding: 8px 0;
            position: relative;
            transition: all 0.3s;
            opacity: 0.9;
            text-decoration: none;
        }

        .nav-link:hover,
        .nav-link.active {
            opacity: 1;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .apply-loan {
            background: white !important;
            color: black !important;
            padding: 12px 25px !important;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(26, 115, 232, 0.2);
            transition: transform 0.3s, box-shadow 0.3s !important;
        }

        .apply-loan:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 115, 232, 0.3);
        }

        /* Enhanced Footer */
        .footer {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            color: white;
            padding: 80px 0 30px;
        }

        .footer-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-description {
            color: white;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .social-icons {
            margin-top: 2rem;
        }

        .social-icons a {
            color: white;
            background: rgba(255,255,255,0.1);
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 10px;
            font-size: 1.2rem;
            transition: all 0.3s;
        }

        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        .footer hr {
            border-color: rgba(255,255,255,0.1);
            margin: 30px 0;
        }

        .copyright {
            color: white;
            font-size: 0.9rem;
        }

        .swal2-popup {
            border-radius: 12px !important;
            box-shadow: 0 5px 20px rgba(15, 67, 50, 0.2) !important;
        }

        .swal2-title {
            color: #0F4332 !important;
            font-weight: 600 !important;
        }

        .swal2-icon.swal2-error {
            color: #dc3545 !important;
            border-color: #dc3545 !important;
        }

        .swal2-popup .swal2-title {
            color: #dc3545 !important;
        }

        /* Form Styles */
        .signup-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            width: 100%;
            box-sizing: border-box;
        }

        .form-title {
            color: black;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .form-label {
            color: black;
            font-weight: 500;
        }

        .form-control {
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
            position: relative;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-weight: 600;
            margin: 0 2rem;
            z-index: 1;
        }

        .step.active {
            background: var(--accent-color);
            color: white;
        }

        .step-line {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 2px;
            background: #e9ecef;
            width: 60%;
            z-index: 0;
        }

        /* Navigation Buttons */
        .btn-navs {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
        }

        /* File Input Styles */
        .file-input-container {
            position: relative;
            width: 100%;
            margin-bottom: 1rem;
        }

        .custom-file-input {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .custom-file-input input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
        }

        .custom-file-input input[type="file"]::-webkit-file-upload-button {
            background-color: var(--secondary-color);
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .custom-file-input input[type="file"]::-webkit-file-upload-button:hover {
            background-color: #0a2e22;
        }

        /* Button Styles */
        .btn-previous:hover {
            background-color: #d1d5d9 !important;
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }

        .btn-next:hover {
            background-color: #0a2e22 !important;
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }

        /* Enhanced Responsive Styles */
        /* Large devices (desktops, less than 1200px) */
        @media (max-width: 1200px) {
            .signup-container {
                max-width: 90%;
                margin: 1.5rem auto;
            }
        }

        /* Medium devices (tablets, less than 992px) */
        @media (max-width: 992px) {
            .signup-container {
                max-width: 95%;
                padding: 1.5rem;
            }
            
            .form-title {
                font-size: 1.75rem;
            }
            
            .form-subtitle {
                font-size: 1rem;
            }

            .footer {
                padding: 60px 0 20px;
            }

            .footer-title {
                font-size: 1.6rem;
            }
        }

        /* Small devices (landscape phones, less than 768px) */
        @media (max-width: 768px) {
            .signup-container {
                margin: 1rem auto;
                padding: 1.25rem;
            }
            
            .form-title {
                font-size: 1.5rem;
                margin-bottom: 0.25rem;
            }
            
            .form-subtitle {
                font-size: 0.95rem;
                margin-bottom: 1.5rem;
            }
            
            .form-control {
                padding: 0.6rem;
                font-size: 0.95rem;
            }
            
            .form-label {
                font-size: 0.95rem;
                margin-bottom: 0.25rem;
            }

            .apply-loan {
                margin-top: 10px;
                background-color: white !important;
                color: black !important;
                text-align: center;
                width: 100%;
                max-width: 200px;
            }

            .row {
                margin-right: 0;
                margin-left: 0;
            }
            
            .col-md-4 {
                padding: 0 10px;
            }
            
            .custom-file-input input[type="file"] {
                font-size: 14px;
                padding: 0.5rem;
            }
            
            .custom-file-input input[type="file"]::-webkit-file-upload-button {
                padding: 6px 12px;
                font-size: 14px;
            }
            
            .progress-steps {
                margin: 1.5rem 0;
            }
            
            .step {
                width: 35px;
                height: 35px;
                margin: 0 1rem;
                font-size: 14px;
            }

            .footer-description {
                font-size: 1rem;
            }

            .social-icons a {
                width: 35px;
                height: 35px;
                margin: 0 8px;
            }
        }

        /* Extra small devices (phones, less than 576px) */
        @media (max-width: 576px) {
            .signup-container {
                max-width: 100%;
                margin: 0.5rem;
                padding: 1rem;
                border-radius: 10px;
            }
            
            .form-title {
                font-size: 1.25rem;
                text-align: center;
            }
            
            .form-subtitle {
                font-size: 0.9rem;
                text-align: center;
                margin-bottom: 1.25rem;
            }
            
            .form-control {
                padding: 0.5rem;
                font-size: 0.9rem;
                margin-bottom: 0.75rem;
            }
            
            .form-label {
                font-size: 0.9rem;
            }
            
            .form-group {
                margin-bottom: 0.75rem;
            }
            
            input, select, textarea {
                max-width: 100%;
                box-sizing: border-box;
            }

            .d-flex.justify-content-center.gap-4 {
                flex-direction: column;
                align-items: center;
                gap: 1rem !important;
            }
            
            .btn-previous,
            .btn-next {
                width: 100% !important;
                max-width: 200px;
            }

            .footer {
                padding: 40px 0 20px;
                text-align: center;
            }

            .footer-title {
                font-size: 1.4rem;
            }

            .footer-description {
                font-size: 0.9rem;
            }

            .social-icons {
                margin-top: 1.5rem;
            }
        }

        /* Very small devices (small phones, less than 400px) */
        @media (max-width: 400px) {
            .signup-container {
                margin: 0.25rem;
                padding: 0.75rem;
            }
            
            .form-title {
                font-size: 1.1rem;
            }
            
            .form-subtitle {
                font-size: 0.85rem;
            }
            
            .form-control {
                padding: 0.4rem;
                font-size: 0.85rem;
            }
            
            .form-label {
                font-size: 0.85rem;
            }
            
            .btn-navs {
                padding: 0.5rem 1.5rem;
                font-size: 0.9rem;
            }

            .progress-steps {
                margin: 1rem 0;
            }
            
            .step {
                width: 30px;
                height: 30px;
                margin: 0 0.5rem;
                font-size: 12px;
            }

            .footer-title {
                font-size: 1.2rem;
            }

            .social-icons a {
                width: 30px;
                height: 30px;
                font-size: 1rem;
                margin: 0 5px;
            }
        }
        /* Validation styles */
    
        .error-message {
            color: red;
            font-size: 0.8rem;
            margin-top: 5px;
            display: none;
            }

        .input-error {
            border-color: red !important;
            }

        .password-container {
            position: relative;
            }

        .toggle-password {
            cursor: pointer;
            color: #6c757d;
            height: 50px; /* Make it match the height of the input field */
            display: flex;
            align-items: center; /* Center the icon vertically */
        }

        .password-input,
        .confirm-password-input {
            width: 100%;
            padding-right: 40px; /* Adjust this value to control the spacing between the input and the eye icon */
            height: 50px;
          
        }

                        
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="dist/assets/images/pmpc-logo.png" alt="PASCHAL Logo">
                <span class="navbar-text ml-2">PASCHAL</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="benefits.php">Benefits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Sign Up</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link apply-loan" href="apply-loan.php">Apply for Loan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Display error messages from session -->
    <?php if (isset($_SESSION['error'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error',
                    text: '<?php echo $_SESSION['error']; ?>',
                    icon: 'error',
                    confirmButtonColor: '#0F4332'
                });
                <?php unset($_SESSION['error']); ?>
            });
        </script>
    <?php endif; ?>

    <div class="container signup-container">
        <h2 class="form-title">Sign Up | Membership Application</h2>
        <p class="form-subtitle">To register, please take the time to fill out the information below.</p>
        
        <form id="signupForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" novalidate>
<!-- First Row -->
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">First Name <span class="text-danger">*</span></label>
        <input type="text" name="firstname" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['firstname'] ?? ''); ?>" required>
        <div class="error-message" id="firstname-error">First name is required</div>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Last Name <span class="text-danger">*</span></label>
        <input type="text" name="lastname" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['lastname'] ?? ''); ?>" required>
        <div class="error-message" id="lastname-error">Last name is required</div>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Middle Name</label>
        <input type="text" name="middlename" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['middlename'] ?? ''); ?>">
    </div>
</div>

<!-- Second Row -->
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">Street</label>
        <input type="text" name="street" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['street'] ?? ''); ?>">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Barangay <span class="text-danger">*</span></label>
        <input type="text" name="barangay" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['barangay'] ?? ''); ?>" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Municipality <span class="text-danger">*</span></label>
        <input type="text" name="municipality" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['municipality'] ?? ''); ?>" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Province <span class="text-danger">*</span></label>
        <input type="text" name="province" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['province'] ?? ''); ?>" required>
    </div>
</div>

<!-- Third Row -->
<div class="row">
    <div class="col-md-3 mb-3">
        <label class="form-label">Gender <span class="text-danger">*</span></label>
        <select name="gender" class="form-control" required>
            <option value="male" <?php echo ($_SESSION['form_data']['gender'] ?? '') == 'male' ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo ($_SESSION['form_data']['gender'] ?? '') == 'female' ? 'selected' : ''; ?>>Female</option>
            <option value="other" <?php echo ($_SESSION['form_data']['gender'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Birthday <span class="text-danger">*</span></label>
        <input type="date" name="birthday" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['birthday'] ?? ''); ?>" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Age <span class="text-danger">*</span></label>
        <input type="number" name="age" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['age'] ?? ''); ?>" readonly>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
        <input type="tel" name="mobile" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['mobile'] ?? ''); ?>" required maxlength="11">
        <div class="error-message" id="mobile-error">Please enter a valid 11-digit phone number</div>
    </div>
</div>

<!-- TIN Row -->
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">TIN No. (Required) <span style="color: red;">*</span></label>
        <input type="text" name="tin-number" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['tin-number'] ?? ''); ?>" required maxlength="9">
        <div class="error-message" id="tin-error">TIN must be exactly 9 digits</div>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">TIN ID Image <span style="color: red;">*</span></label>
        <div class="file-input-container">
            <div class="custom-file-input">
                <input type="file" name="tinIdImage" accept="image/*" required>
            </div>
        </div>
    </div>
</div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                <label class="form-label">Email Address <span style="color: red;">*</span></label>
        <input type="email" name="email" class="form-control"
               value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>" required>
        <div class="error-message" id="email-error">Please enter a valid email address</div>
                </div>
                <div class="col-md-4 mb-3 password-container">
                    <label class="form-label">Password <span style="color: red;">*</span></label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control password-input" required minlength="8">
                        <span class="input-group-text toggle-password" onclick="togglePasswordVisibility(this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="error-message" id="password-error">Password must be at least 8 characters long</div>
                </div>
                <div class="col-md-4 mb-3 password-container">
                    <label class="form-label">Confirm Password <span style="color: red;">*</span></label>
                    <div class="input-group">
                        <input type="password" name="confirmPassword" class="form-control password-input" required minlength="8">
                        <span class="input-group-text toggle-password" onclick="togglePasswordVisibility(this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="error-message" id="confirmPassword-error">Passwords must match</div>
                </div>
            </div>   
            <div class="progress-steps">
                <div class="step-line"></div>
                <div class="step active">1</div>
                <div class="step">2</div>
                <div class="step">3</div>
                <div class="step">4</div>
            </div>
            
            <div class="d-flex justify-content-center gap-4 mt-5">
                <button type="button" name="previousBtn" class="btn btn-previous px-5 py-2" style="background-color: #E9ECEF; border: none; border-radius: 5px; color: #666; width: 150px;">Previous</button>
                <button type="submit" name="register" class="btn btn-next px-5 py-2" style="background-color: #0F4332; border: none; border-radius: 5px; color: white; width: 150px;">Next</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="footer-title">PASCHAL MULTIPURPOSE COOPERATIVE</h3>
                    <p class="footer-description">Corner Acacia St., Bunsuran 1st, Pandi, Bul.(Main Office)</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/paschalcoop" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="mailto:paschal_mpc@yahoo.com" aria-label="Email"><i class="fa-solid fa-envelope"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 mt-4 mt-lg-0">
                    <h4 class="h5 mb-3">Quick Links</h4>
                    <ul class="list-unstyled">
                        <li><a href="services.php" class="text-white text-decoration-none">Our Services</a></li>
                        <li><a href="benefits.php" class="text-white text-decoration-none">Member Benefits</a></li>
                        <li><a href="about.php" class="text-white text-decoration-none">About Us</a></li>
                        <li><a href="apply-loan.php" class="text-white text-decoration-none">Apply for Loan</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mt-4 mt-lg-0">
                    <h4 class="h5 mb-3">Contact Us</h4>
                    <ul class="list-unstyled text-white">
                        <li><i class="fas fa-phone me-2"></i>0917-520-1287 / 0932-864-5536</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center copyright">
                <p>©2024 | PASCHAL Multi-Purpose Cooperative. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Initialize AOS (Animate on Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false,
        offset: 100
    });

    // DOM Ready Event Handler
    document.addEventListener('DOMContentLoaded', function() {
        // Get current page URL
        const currentLocation = location.pathname.split('/').pop();

        // Select all nav links
        const navLinks = document.querySelectorAll('.nav-link');

        // Set active class based on current page
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === currentLocation || 
                (currentLocation === '' && link.getAttribute('href') === 'index.php')) {
                link.classList.add('active');
            }
        });

        // Add lazy loading to images
        const images = document.querySelectorAll('img:not([loading])');
        images.forEach(img => {
            img.setAttribute('loading', 'lazy');
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Age calculation from birthday
        const birthdayInput = document.querySelector('input[name="birthday"]');
        const ageInput = document.querySelector('input[name="age"]');

        if (birthdayInput && ageInput) {
            birthdayInput.addEventListener('change', function() {
                const birthDate = new Date(this.value);
                const today = new Date();
                
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                const dayDiff = today.getDate() - birthDate.getDate();
                
                if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                    age--;
                }

                if (age < 18 || isNaN(age)) {
                    Swal.fire({
                        title: 'Age Restriction',
                        html: '<div style="color:#dc3545;font-size:1.1rem">You must be <strong>18 years or older</strong> to register.</div>' +
                              '<div style="margin-top:15px;color:#6c757d">Please provide a valid date of birth.</div>',
                        icon: 'error',
                        confirmButtonColor: '#0F4332',
                        confirmButtonText: 'OK',
                        backdrop: `
                            rgba(15,67,50,0.4)
                            url("dist/assets/images/age-restriction.png")
                            center top
                            no-repeat
                        `
                    });
                    this.value = '';
                    ageInput.value = '';
                } else {
                    ageInput.value = age;
                }
            });
        }

        // Form validation functions
        function validateFirstName() {
            const firstname = document.querySelector('input[name="firstname"]');
            const error = document.getElementById('firstname-error');
            
            if (!firstname.value.trim()) {
                firstname.classList.add('input-error');
                error.style.display = 'block';
                return false;
            } else {
                firstname.classList.remove('input-error');
                error.style.display = 'none';
                return true;
            }
        }

        function validateLastName() {
            const lastname = document.querySelector('input[name="lastname"]');
            const error = document.getElementById('lastname-error');
            
            if (!lastname.value.trim()) {
                lastname.classList.add('input-error');
                error.style.display = 'block';
                return false;
            } else {
                lastname.classList.remove('input-error');
                error.style.display = 'none';
                return true;
            }
        }

        function validateMobile() {
            const mobile = document.querySelector('input[name="mobile"]');
            const error = document.getElementById('mobile-error');
            const mobileRegex = /^\d{11}$/;
            
            if (!mobileRegex.test(mobile.value)) {
                mobile.classList.add('input-error');
                error.style.display = 'block';
                return false;
            } else {
                mobile.classList.remove('input-error');
                error.style.display = 'none';
                return true;
            }
        }

        function validateEmail() {
            const email = document.querySelector('input[name="email"]');
            const error = document.getElementById('email-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email.value)) {
                email.classList.add('input-error');
                error.style.display = 'block';
                return false;
            } else {
                email.classList.remove('input-error');
                error.style.display = 'none';
                return true;
            }
        }

        function validatePassword() {
            const password = document.querySelector('input[name="password"]');
            const error = document.getElementById('password-error');
            
            if (password.value.length < 8) {
                password.classList.add('input-error');
                error.style.display = 'block';
                return false;
            } else {
                password.classList.remove('input-error');
                error.style.display = 'none';
                return true;
            }
        }

        function validateConfirmPassword() {
            const password = document.querySelector('input[name="password"]');
            const confirmPassword = document.querySelector('input[name="confirmPassword"]');
            const error = document.getElementById('confirmPassword-error');
            
            if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('input-error');
                error.style.display = 'block';
                return false;
            } else {
                confirmPassword.classList.remove('input-error');
                error.style.display = 'none';
                return true;
            }
        }

        function validateTIN() {
            const tin = document.querySelector('input[name="tin-number"]');
            const error = document.getElementById('tin-error');
            const tinRegex = /^\d{9}$/;
            
            if (!tinRegex.test(tin.value)) {
                tin.classList.add('input-error');
                error.textContent = 'TIN must be exactly 9 digits';
                error.style.display = 'block';
                return false;
            } else {
                tin.classList.remove('input-error');
                error.style.display = 'none';
                return true;
            }
        }

        // Toggle Password Visibility
        function togglePasswordVisibility(el) {
            const input = el.previousElementSibling;
            const icon = el.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Real-time Validation Event Listeners
        const firstname = document.querySelector('input[name="firstname"]');
        const lastname = document.querySelector('input[name="lastname"]');
        const mobile = document.querySelector('input[name="mobile"]');
        const email = document.querySelector('input[name="email"]');
        const password = document.querySelector('input[name="password"]');
        const confirmPassword = document.querySelector('input[name="confirmPassword"]');
        const tin = document.querySelector('input[name="tin-number"]');
        const form = document.getElementById('signupForm');

        if (firstname) firstname.addEventListener('input', validateFirstName);
        if (lastname) lastname.addEventListener('input', validateLastName);
        if (mobile) mobile.addEventListener('input', validateMobile);
        if (email) email.addEventListener('input', validateEmail);
        if (password) password.addEventListener('input', validatePassword);
        if (confirmPassword) confirmPassword.addEventListener('input', validateConfirmPassword);
        if (tin) tin.addEventListener('input', validateTIN);

        if (form) {
    form.addEventListener('submit', function(e) {
        // Prevent default only if validation fails
        const isFirstNameValid = validateFirstName();
        const isLastNameValid = validateLastName();
        const isMobileValid = validateMobile();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isConfirmPasswordValid = validateConfirmPassword();
        const isTINValid = validateTIN();

        // Check age again
        const ageInput = document.querySelector('input[name="age"]');
        const isAgeValid = parseInt(ageInput.value) >= 18 && !isNaN(parseInt(ageInput.value));

        if (!isFirstNameValid || !isLastNameValid || !isMobileValid || 
            !isEmailValid || !isPasswordValid || !isConfirmPasswordValid || 
            !isTINValid || !isAgeValid) {
            
            e.preventDefault();
            
            if (!isAgeValid) {
                Swal.fire({
                    title: 'Age Restriction',
                    html: '<div style="color:#dc3545;font-size:1.1rem">You must be <strong>18 years or older</strong> to register.</div>' +
                          '<div style="margin-top:15px;color:#6c757d">Please provide a valid date of birth.</div>',
                    icon: 'error',
                    confirmButtonColor: '#0F4332',
                    confirmButtonText: 'OK',
                    backdrop: `
                        rgba(15,67,50,0.4)
                        url("dist/assets/images/age-restriction.png")
                        center top
                        no-repeat
                    `
                });
            }
            
            // Scroll to first error
            const firstError = document.querySelector('.input-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            return false;
        }
        
        // If all validations pass, allow form to submit
    });
}
    });

    // Navbar scroll handler
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.background = 'linear-gradient(135deg, var(--primary-color), var(--secondary-color))';
            navbar.style.boxShadow = '0 2px 15px rgba(0,0,0,0.1)';
        } else {
            navbar.style.background = 'linear-gradient(135deg, var(--primary-color), var(--secondary-color))';
            navbar.style.boxShadow = 'none';
        }
    });
    </script>
</body>
</html>