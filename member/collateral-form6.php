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

                if (!isset($_SESSION['user_id'])) {
                    header("Location: member-login.php");
                    exit();
                }
                
                $user_id = $_SESSION['user_id'];
                $loan_type = filter_input(INPUT_GET, 'loanType', FILTER_SANITIZE_STRING) ?? 'Collateral';
                
                $locations_query = "SELECT DISTINCT name FROM locations ORDER BY name";
                $locations_result = $conn->query($locations_query);
                $locations = [];
                while ($row = $locations_result->fetch_assoc()) {
                    $locations[] = $row['name'];
                }
                
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
                
                $existing_data = null;
                $check_query = "SELECT * FROM land_appraisal WHERE LoanID = ? AND userID = ?";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("ii", $loan_id, $user_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                if ($check_result->num_rows > 0) {
                    $existing_data = $check_result->fetch_assoc();
                }
                $check_stmt->close();
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    try {
                        $land_title_path = null;
                        if (isset($_FILES['land_title']) && $_FILES['land_title']['error'] === UPLOAD_ERR_OK) {
                            $uploadDir = '../dist/assets/images/proofs/';
                            $fileExtension = strtolower(pathinfo($_FILES['land_title']['name'], PATHINFO_EXTENSION));
                            $newFileName = 'land_title_' . time() . '.' . $fileExtension;
                            $uploadPath = $uploadDir . $newFileName;
                
                            if (move_uploaded_file($_FILES['land_title']['tmp_name'], $uploadPath)) {
                                $land_title_path = $uploadPath;
                            } else {
                                throw new Exception("Failed to upload land title image");
                            }
                        }
                
                        if ($existing_data) {
                            $query = "UPDATE land_appraisal SET ";
                            $updateParts = [];
                            $bind_params = [];
                            $types = '';
                
                            if ($land_title_path !== null) {
                                $updateParts[] = "land_title_path = ?";
                                $bind_params[] = &$land_title_path;
                                $types .= 's';
                            }
                
                            $fields = [
                                'square_meters' => ['i', $_POST['square_meters'] ?? null],
                                'type_of_land' => ['s', $_POST['type_of_land'] ?? null],
                                'location_name' => ['s', $_POST['location_name'] ?? null],
                                'right_of_way' => ['s', $_POST['right_of_way'] ?? null],
                                'has_hospital' => ['s', isset($_POST['has_hospital']) ? 'Yes' : 'No'],
                                'has_school' => ['s', isset($_POST['has_school']) ? 'Yes' : 'No'],
                                'has_clinic' => ['s', isset($_POST['has_clinic']) ? 'Yes' : 'No'],
                                'has_church' => ['s', isset($_POST['has_church']) ? 'Yes' : 'No'],
                                'has_market' => ['s', isset($_POST['has_market']) ? 'Yes' : 'No'],
                                'has_terminal' => ['s', isset($_POST['has_terminal']) ? 'Yes' : 'No']
                            ];
                
                            foreach ($fields as $field => $info) {
                                $updateParts[] = "$field = ?";
                                $bind_params[] = &$info[1];
                                $types .= $info[0];
                            }
                
                            $query .= implode(', ', $updateParts);
                            $query .= " WHERE LoanID = ? AND userID = ?";
                            
                            $bind_params[] = &$loan_id;
                            $bind_params[] = &$user_id;
                            $types .= 'ii';
                
                            $stmt = $conn->prepare($query);
                            $bind_array = array($types);
                            foreach ($bind_params as &$param) {
                                $bind_array[] = &$param;
                            }
                            
                            call_user_func_array(array($stmt, 'bind_param'), $bind_array);
                            $stmt->execute();
                            $stmt->close();
                        } else {
                            $query = "INSERT INTO land_appraisal (
                                LoanID,
                                userID,
                                land_title_path,
                                square_meters,
                                type_of_land,
                                location_name,
                                right_of_way,
                                has_hospital,
                                has_school,
                                has_clinic,
                                has_church,
                                has_market,
                                has_terminal
                            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                            $stmt = $conn->prepare($query);
                            
                            $square_meters = $_POST['square_meters'];
                            $type_of_land = $_POST['type_of_land'];
                            $location_name = $_POST['location_name'];
                            $right_of_way = $_POST['right_of_way'];
                            $has_hospital = isset($_POST['has_hospital']) ? 'Yes' : 'No';
                            $has_school = isset($_POST['has_school']) ? 'Yes' : 'No';
                            $has_clinic = isset($_POST['has_clinic']) ? 'Yes' : 'No';
                            $has_church = isset($_POST['has_church']) ? 'Yes' : 'No';
                            $has_market = isset($_POST['has_market']) ? 'Yes' : 'No';
                            $has_terminal = isset($_POST['has_terminal']) ? 'Yes' : 'No';
                
                            $types = "iisisssssssss";
                            $bind_array = array($types);
                            $params = array(
                                &$loan_id,
                                &$user_id,
                                &$land_title_path,
                                &$square_meters,
                                &$type_of_land,
                                &$location_name,
                                &$right_of_way,
                                &$has_hospital,
                                &$has_school,
                                &$has_clinic,
                                &$has_church,
                                &$has_market,
                                &$has_terminal
                            );
                            
                            foreach ($params as &$param) {
                                $bind_array[] = &$param;
                            }
                            
                            call_user_func_array(array($stmt, 'bind_param'), $bind_array);
                            $stmt->execute();
                            $stmt->close();
                        }
                
                        header("Location: collateral-form7.php?loanType=" . urlencode($loan_type));
                        exit();
                
                    } catch (Exception $e) {
                        error_log("Property Information Update Error: " . $e->getMessage());
                        echo "<script>
                                alert('Error updating property information: " . addslashes($e->getMessage()) . "');
                                history.back();
                            </script>";
                        exit();
                    }
                }
                ?>
                <div class="container">
                    <div class="form-container">
                        <h1 class="heading"><?php echo ucfirst($loan_type); ?> Loan Application 6 - Property Information</h1>
                        <form action="collateral-form6.php" method="post" enctype="multipart/form-data" id="documentForm">
                            <!-- Land Title Upload Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="land_title" class="form-label">Land Title Image<span style="color: red;">*</span></label>
                                        <input type="file" class="form-control" name="land_title" id="land_title" accept="image/*" required>
                                        <div id="land_title_preview" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Property Information Section -->
                            <div class="row mb-4">
                                <h6>Property Information</h6>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="square_meters" class="form-label">Square Meters<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" name="square_meters" 
                                            id="square_meters" required min="1">
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="type_of_land" class="form-label">Type of Land<span style="color: red;">*</span></label>
                                        <select class="form-control" name="type_of_land" id="type_of_land" required>
                                            <option value="">Select Type</option>
                                            <option value="Industrial">Industrial</option>
                                            <option value="Residential">Residential</option>
                                            <option value="Agricultural">Agricultural</option>
                                            <option value="Commercial">Commercial</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="location_name" class="form-label">Location<span style="color: red;">*</span></label>
                                        <select class="form-control" name="location_name" id="location_name" required>
                                            <option value="">Select Location</option>
                                            <?php foreach ($locations as $location): ?>
                                                <option value="<?php echo htmlspecialchars($location); ?>">
                                                    <?php echo htmlspecialchars($location); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="right_of_way" class="form-label">Right of Way<span style="color: red;">*</span></label>
                                        <select class="form-control" name="right_of_way" id="right_of_way" required>
                                            <option value="">Select Option</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Commodities Section -->
                            <div class="row mb-4">
                                <h6>Commodities within the Vicinity<span style="color: red;">*</span></h6>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="has_hospital" id="has_hospital">
                                        <label class="form-check-label" for="has_hospital">Hospital</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="has_school" id="has_school">
                                        <label class="form-check-label" for="has_school">School</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="has_clinic" id="has_clinic">
                                        <label class="form-check-label" for="has_clinic">Clinic</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="has_church" id="has_church">
                                        <label class="form-check-label" for="has_church">Church</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="has_market" id="has_market">
                                        <label class="form-check-label" for="has_market">Market</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="has_terminal" id="has_terminal">
                                        <label class="form-check-label" for="has_terminal">Public Terminal</label>
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
            // Function to show validation message
            function showValidation(element, isValid, message) {
                const formGroup = $(element).closest('.form-group');
                formGroup.find('.validation-message').remove();

                if (!isValid) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                    formGroup.append(`<div class="validation-message text-danger small mt-1">${message}</div>`);
                    return false;
                } else {
                    $(element).addClass('is-valid').removeClass('is-invalid');
                    return true;
                }
            }

            // Function to preview image
            function readURL(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        $('#land_title_preview').html(`
                            <img src="${e.target.result}" alt="Land Title Preview" 
                                style="max-width: 200px; max-height: 200px;" class="mt-2">
                        `);
                    }
                    
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Handle image preview
            $('#land_title').change(function() {
                readURL(this);
                validateFormField(this);
            });

            // Validate form fields
            function validateFormField(element) {
                const value = $(element).val();
                const fieldName = $(element).attr('name');
                let isValid = true;
                let message = '';

                if (!value) {
                    isValid = false;
                    message = 'This field is required';
                } else if (fieldName === 'square_meters') {
                    if (parseInt(value) <= 0) {
                        isValid = false;
                        message = 'Square meters must be greater than 0';
                    }
                } else if (fieldName === 'land_title') {
                    const file = element.files[0];
                    if (file) {
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        if (!validTypes.includes(file.type)) {
                            isValid = false;
                            message = 'Please upload a valid image file (JPEG, PNG, or GIF)';
                        }
                    }
                }

                return showValidation(element, isValid, message);
            }

            // Validate on input change
            $('#square_meters, #type_of_land, #location_name, #right_of_way, #land_title').on('change', function() {
                validateFormField(this);
            });

            // Form submission validation
            $('#documentForm').on('submit', function(e) {
                let isValid = true;
                
                // Validate all form fields
                $('#square_meters, #type_of_land, #location_name, #right_of_way, #land_title').each(function() {
                    if (!validateFormField(this)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Form Validation',
                        text: 'Please fill in all required fields correctly.',
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