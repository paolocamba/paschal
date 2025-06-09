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
    <title>Regular Loan Form 2 | Member</title>
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
                    input[readonly] {
                        background-color: #f8f9fa; /* Light gray background */
                        cursor: not-allowed; /* Change cursor to indicate it's not editable */
                    }

                   
                </style>
                <?php
                include '../connection/config.php';

                // Debug session and login status
                if (!isset($_SESSION['user_id'])) {
                    // Redirect to login if no user is logged in
                    header("Location: member-login.php");
                    exit();
                }

                $user_id = $_SESSION['user_id'];

                // Get the loan type from the URL (either 'regular' or 'collateral')
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
                    // Ensure all required fields are properly sanitized and validated
                    $yearStay = filter_input(INPUT_POST, 'yearStay', FILTER_VALIDATE_INT);
                    $ownHouse = filter_input(INPUT_POST, 'ownHouse', FILTER_SANITIZE_STRING);
                    $renting = filter_input(INPUT_POST, 'renting', FILTER_SANITIZE_STRING);
                    $livingWithRelative = filter_input(INPUT_POST, 'livingWithRelative', FILTER_SANITIZE_STRING);
                    $maritalStatus = filter_input(INPUT_POST, 'maritalStatus', FILTER_SANITIZE_STRING);
                    $spouseName = filter_input(INPUT_POST, 'spouseName', FILTER_SANITIZE_STRING);
                    $dependentCount = filter_input(INPUT_POST, 'dependentCount', FILTER_VALIDATE_INT);
                    $dependentInSchool = filter_input(INPUT_POST, 'dependentInSchool', FILTER_VALIDATE_INT);

                    // Employment details
                    $employerName = filter_input(INPUT_POST, 'employer_name', FILTER_SANITIZE_STRING);
                    $employerAddress = filter_input(INPUT_POST, 'employer_address', FILTER_SANITIZE_STRING);
                    $presentPosition = filter_input(INPUT_POST, 'present_position', FILTER_SANITIZE_STRING);
                    $dateOfEmployment = filter_input(INPUT_POST, 'date_of_employment', FILTER_SANITIZE_STRING);
                    $monthlyIncome = filter_input(INPUT_POST, 'monthly_income', FILTER_VALIDATE_FLOAT) ?: 0;
                    $contactPerson = filter_input(INPUT_POST, 'contact_person', FILTER_SANITIZE_STRING);
                    $contactTelephoneNo = filter_input(INPUT_POST, 'contact_telephone_no', FILTER_SANITIZE_STRING);
                    $selfEmployedBusinessType = filter_input(INPUT_POST, 'self_employed_business_type', FILTER_SANITIZE_STRING);
                    $businessStartDate = filter_input(INPUT_POST, 'business_start_date', FILTER_SANITIZE_STRING);

                    // Family and income details
                    $familyMemberCount = filter_input(INPUT_POST, 'family_member_count', FILTER_VALIDATE_INT);
                    $spouseIncome = filter_input(INPUT_POST, 'spouse_income', FILTER_SANITIZE_STRING);
                    $spouseIncomeAmount = filter_input(INPUT_POST, 'spouse_income_amount', FILTER_VALIDATE_FLOAT) ?: 0;
                    $spouseOtherIncome = filter_input(INPUT_POST, 'spouse_other_income', FILTER_SANITIZE_STRING);
                    $spouseOtherIncomeAmount = filter_input(INPUT_POST, 'spouse_other_income_amount', FILTER_VALIDATE_FLOAT) ?: 0;
                    $selfOtherIncomeAmount = filter_input(INPUT_POST, 'self_other_income_amount', FILTER_VALIDATE_FLOAT) ?: 0;

                    // Expense details
                    $foodGroceriesExpense = filter_input(INPUT_POST, 'food_groceries_expense', FILTER_VALIDATE_FLOAT) ?: 0;
                    $gasOilTransportationExpense = filter_input(INPUT_POST, 'gas_oil_transportation_expense', FILTER_VALIDATE_FLOAT) ?: 0;
                    $schoolingExpense = filter_input(INPUT_POST, 'schooling_expense', FILTER_VALIDATE_FLOAT) ?: 0;
                    $utilitiesExpense = filter_input(INPUT_POST, 'utilities_expense', FILTER_VALIDATE_FLOAT) ?: 0;
                    $miscellaneousExpense = filter_input(INPUT_POST, 'miscellaneous_expense', FILTER_VALIDATE_FLOAT) ?: 0;

                    // Calculate net family income and total expenses
                    $totalIncome = $monthlyIncome + $selfOtherIncomeAmount + $spouseIncomeAmount + $spouseOtherIncomeAmount;
                    $totalExpenses = $foodGroceriesExpense + $gasOilTransportationExpense + $schoolingExpense + $utilitiesExpense + $miscellaneousExpense;
                    $netFamilyIncome = $totalIncome - $totalExpenses;
                    

                    // Minimum income validation
                    $MIN_INCOME_THRESHOLD = 15000;
                    $INCOME_TO_EXPENSE_RATIO = 3;
                    if ($netFamilyIncome < $MIN_INCOME_THRESHOLD || $netFamilyIncome < ($totalExpenses * $INCOME_TO_EXPENSE_RATIO)) {
                        echo "<script>
                                alert('Your income does not meet the minimum requirements for this loan.');
                                window.location.href = 'regular-form2.php?loanType=" . urlencode($loan_type) . "';
                            </script>";
                        exit();
                    }

                    $query = "SELECT LoanID FROM loanapplication WHERE userID = ? ORDER BY LoanID DESC LIMIT 1";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $_SESSION['loan_application_id'] = $row['LoanID']; // Force update session
                        $loan_id = $row['LoanID']; // Set correct LoanID
                    } else {
                        die("Error: No loan application found for this user.");
                    }

                    $stmt->close();
                    
                    echo "Debug: LoanID being updated is " . htmlspecialchars($loan_id);


                    // Update query
                    $query = "UPDATE loanapplication 
                            SET years_stay_present_address=?, own_house=?, renting=?, living_with_relative=?, 
                                marital_status=?, spouse_name=?, number_of_dependents=?, dependents_in_school=?, 
                                employer_name=?, employer_address=?, present_position=?, date_of_employment=?, 
                                monthly_income=?, contact_person=?, contact_telephone_no=?, 
                                self_employed_business_type=?, business_start_date=?, family_member_count=?, 
                                self_other_income_amount=?, spouse_income=?, spouse_income_amount=?, 
                                spouse_other_income=?, spouse_other_income_amount=?, 
                                food_groceries_expense=?, gas_oil_transportation_expense=?, 
                                schooling_expense=?, utilities_expense=?, miscellaneous_expense=?, 
                                total_expenses=?, net_family_income=? 
                            WHERE LoanID=?";

                    $stmt = $conn->prepare($query);
                    if (!$stmt) {
                        die("SQL preparation error: " . $conn->error);
                    }

                // Bind parameters (correct number of placeholders & valid type specifiers)
                $stmt->bind_param("isssssisissdsssissddssddddddddi", 
                $yearStay,                  // i (Integer)
                $ownHouse,                  // s (String)
                $renting,                   // s
                $livingWithRelative,        // s
                $maritalStatus,             // s
                $spouseName,                // s
                $dependentCount,            // i (Integer)
                $dependentInSchool,         // i (Integer)
                $employerName,              // s
                $employerAddress,           // s
                $presentPosition,           // s
                $dateOfEmployment,          // s
                $monthlyIncome,             // d (Double/Float)
                $contactPerson,             // s
                $contactTelephoneNo,        // s
                $selfEmployedBusinessType,  // s
                $businessStartDate,         // s
                $familyMemberCount,         // i (Integer)
                $selfOtherIncomeAmount,     // d (Double/Float)
                $spouseIncome,              // s
                $spouseIncomeAmount,        // d (Double/Float)
                $spouseOtherIncome,         // s
                $spouseOtherIncomeAmount,   // d (Double/Float)
                $foodGroceriesExpense,      // d (Double/Float)
                $gasOilTransportationExpense, // d (Double/Float)
                $schoolingExpense,          // d (Double/Float)
                $utilitiesExpense,          // d (Double/Float)
                $miscellaneousExpense,      // d (Double/Float)
                $totalExpenses,             // d (Double/Float)
                $netFamilyIncome,           // d (Double/Float)
                $loan_id                    // i (Primary Key, Integer)
                );


                    // Execute and check success
                    if ($stmt->execute()) {
                        if ($stmt->affected_rows === 0) {
                            echo "No rows were updated. Check if LoanID exists.";
                            exit();
                        }
                        $stmt->close();
                        header("Location: regular-form3.php?loanType=" . urlencode($loan_type));
                        exit();
                    } else {
                        die("Database update failed: " . $stmt->error);
                    }
                }

                ob_end_flush();
                ?>

                <div class="container">
                    <div class="form-container">
                    <h1 class="heading"><?php echo ucfirst($loan_type); ?> Loan Application - Step 2</h1>
                        <form action="regular-form2.php" method="post">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="yearStay" class="form-label">Year Of Stay At Present Address<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" name="yearStay" id="yearStay"
                                            placeholder="Year Stay At Present Address" required>
                                    </div>
                                </div>
                               
                                
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ownHouse" class="form-label">Own House<span style="color: red;">*</span></label>
                                            <select class="form-select" name="ownHouse" id="ownHouse" required>
                                                <option selected>Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="renting" class="form-label">Renting<span style="color: red;">*</span></label>
                                            <select class="form-select" name="renting" id="renting" required>
                                                <option selected>Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="livingWithRelative" class="form-label">Living with Relative<span style="color: red;">*</span></label>
                                        <select class="form-select" name="livingWithRelative" id="livingWithRelative"
                                            required>
                                            <option selected>Select</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-4">
                            <!-- Marital Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maritalStatus" class="form-label">Marital Status<span style="color: red;">*</span></label>
                                    <select class="form-select same-size-input" name="maritalStatus" id="maritalStatus" required>
                                        <option value="" selected>Select Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Separated">Separated</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Spouse Name (Initially Hidden) -->
                            <div class="col-md-6" id="spouseNameContainer" style="display: none;">
                                <div class="form-group">
                                    <label for="spouseName" class="form-label">Spouse Name<span style="color: red;">*</span></label>
                                    <input type="text" class="form-control same-size-input" name="spouseName" id="spouseName">
                                </div>
                            </div>
                        </div>

                        <!-- JavaScript to Show/Hide Spouse Name -->
                        <script>
                            document.getElementById('maritalStatus').addEventListener('change', function () {
                            const status = this.value;
                            const spouseFields = document.getElementById('spouseFields');
                            const spouseNameContainer = document.getElementById('spouseNameContainer');

                            if (status === 'Married') {
                                spouseFields.style.display = 'block'; // Show spouse fields
                                spouseNameContainer.style.display = 'block'; // Show spouse name field
                            } else {
                                spouseFields.style.display = 'none'; // Hide spouse fields
                                spouseNameContainer.style.display = 'none'; // Hide spouse name field
                            }
                        });
                        </script>

                        <!-- CSS to Ensure Equal Widths -->
                        <style>
                            .same-size-input {
                                width: 100%; /* Ensures both have the same full width */
                            }
                        </style>



                            <div class="row mb-4">
                                <h6>Dependents Information</h6>
                                <div class="row mb-4">
                                    <h6>Dependents Information</h6>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="family_member_count" class="form-label">Family Member Count<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" name="family_member_count"
                                            id="family_member_count" required>
                                    </div>
                                </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dependentCount" class="form-label">Number of Dependents <span style="color: red;">*</span></label>
                                            <input type="number" class="form-control" name="dependentCount" id="dependentCount" min="0" max="4" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dependentInSchool" class="form-label">Dependents in School <span style="color: red;">*</span></label>
                                            <input type="number" class="form-control" name="dependentInSchool" id="dependentInSchool" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Container for dynamic dependent fields -->
                                <div id="dependentFields"></div>

                                <script>
                                    // Function to create dependent fields dynamically
                                    function generateDependentFields(count) {
                                        const container = document.getElementById('dependentFields');
                                        container.innerHTML = ''; // Clear previous fields
                                        
                                        for (let i = 1; i <= count; i++) {
                                            const dependentHtml = `
                                                <div class="row mb-4 dependent-row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="dependent${i}Name" class="form-label">Dependent ${i} Name <span style="color: red;">*</span></label>
                                                            <input type="text" class="form-control" name="dependent${i}Name" id="dependent${i}Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="dependent${i}Age" class="form-label">Dependent ${i} Age <span style="color: red;">*</span></label>
                                                            <input type="number" class="form-control" name="dependent${i}Age" id="dependent${i}Age" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="dependent${i}Grade" class="form-label">Dependent ${i} Grade Level <span style="color: red;">*</span></label>
                                                            <input type="text" class="form-control" name="dependent${i}Grade" id="dependent${i}Grade" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                            container.insertAdjacentHTML('beforeend', dependentHtml);
                                        }
                                    }

                                    // Listen to input changes on dependentCount
                                    document.getElementById('dependentCount').addEventListener('input', function () {
                                        const count = parseInt(this.value) || 0;
                                        
                                        // Ensure count is between 0 and 4
                                        if (count >= 0 && count <= 4) {
                                            generateDependentFields(count);
                                        }
                                    });
                                </script>


                            <div class="row mb-4">
                                <h6>Employment Information</h6>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="employer_name" class="form-label">Employer Name<span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="employer_name"
                                            id="employer_name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="employer_address" class="form-label">Employer Address<span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="employer_address"
                                            id="employer_address" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="present_position" class="form-label">Present Position<span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="present_position"
                                            id="present_position" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_of_employment" class="form-label">Date of Employment<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" name="date_of_employment" id="date_of_employment" placeholder="mm/dd/yyyy" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="monthly_income" class="form-label">Monthly Income<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" name="monthly_income"
                                            id="monthly_income" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contact_person" class="form-label">Contact Person<span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" name="contact_person"
                                            id="contact_person" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contact_telephone_no" class="form-label">Contact No.<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" name="contact_telephone_no"
                                            id="contact_telephone_no" required>
                                    </div>
                                </div>
                            <div class="row mb-4">
                                <h6>Income Information</h6>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="self_employed_business_type" class="form-label">Type Of Business</label>
                                        <input type="text" class="form-control" name="self_employed_business_type"
                                            id="self_employed_business_type" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="business_start_date" class="form-label">Start Of Business</label>
                                        <input type="date" class="form-control" name="business_start_date"
                                            id="business_start_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="other_income" class="form-label">Other Self Income</label>
                                        <input type="text" class="form-control" name="other_income"
                                            id="other_income" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="self_other_income_amount" class="form-label">Self Other Income(Amount in Peso)</label>
                                        <input type="number" class="form-control" name="self_other_income_amount"
                                            id="self_other_income_amount" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Spouse Information -->
                            <div id="spouseFields" style="display: none;">
                                <!-- First row for first two fields -->
                                <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="spouse_income" class="form-label">Spouse Income Description</label>
                                        <input type="text" class="form-control" name="spouse_income" id="spouse_income">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="spouse_income_amount" class="form-label">Spouse Income (Amount in Peso)</label>
                                        <input type="number" class="form-control" name="spouse_income_amount" id="spouse_income_amount">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="spouse_other_income" class="form-label">Spouse Other Income Description</label>
                                        <input type="text" class="form-control" name="spouse_other_income" id="spouse_other_income">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="spouse_other_income_amount" class="form-label">Spouse Other Income (Amount in Peso)</label>
                                        <input type="number" class="form-control" name="spouse_other_income_amount" id="spouse_other_income_amount">
                                    </div>
                                </div>

                                </div>
                            </div>



                            <script>
                            document.getElementById('maritalStatus').addEventListener('change', function () {
                                const status = this.value;
                                const spouseFields = document.getElementById('spouseFields');

                                if (status === 'Married') {
                                    spouseFields.style.display = 'block';

                                    document.getElementById('spouse_income').removeAttribute('disabled');
                                    document.getElementById('spouse_income_amount').removeAttribute('disabled');
                                    document.getElementById('spouse_other_income').removeAttribute('disabled');
                                    document.getElementById('spouse_other_income_amount').removeAttribute('disabled');
                                } else {
                                    spouseFields.style.display = 'none';

                                    document.getElementById('spouse_income').setAttribute('disabled', 'true');
                                    document.getElementById('spouse_income_amount').setAttribute('disabled', 'true');
                                    document.getElementById('spouse_other_income').setAttribute('disabled', 'true');
                                    document.getElementById('spouse_other_income_amount').setAttribute('disabled', 'true');
                                    
                                    // Optional: Clear input values when hiding the spouse fields
                                    document.getElementById('spouse_income').value = "";
                                    document.getElementById('spouse_income_amount').value = "";
                                    document.getElementById('spouse_other_income').value = "";
                                    document.getElementById('spouse_other_income_amount').value = "";
                                }
                            });

                            </script>
                            <div class="row mb-4">
                                <h6>Expenses Information</h6>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="food_groceries_expense" class="form-label">Food and Groceries Expense<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" name="food_groceries_expense"
                                            id="food_groceries_expense" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gas_oil_transportation_expense" class="form-label">Gas, Oil, and Transportation Expense<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" name="gas_oil_transportation_expense"
                                            id="gas_oil_transportation_expense" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="schooling_expense" class="form-label">Schooling Expense<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" name="schooling_expense"
                                            id="schooling_expense" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="utilities_expense" class="form-label">Utilities Expense (Lights, Water, and Telephone)<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" name="utilities_expense"
                                            id="utilities_expense" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="miscellaneous_expense" class="form-label">Miscellaneous Expense<span style="color: red;">*</span></label>
                                        <input type="number" class="form-control" name="miscellaneous_expense"
                                            id="miscellaneous_expense" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_expenses" class="form-label">Total Expenses</label>
                                        <input type="number" class="form-control" name="total_expenses"
                                            id="total_expenses"  readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="net_family_income" class="form-label">Net Family Income</label>
                                        <input type="number" class="form-control" name="net_family_income"
                                            id="net_family_income" readonly>
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

            // Validation functions
            const validationRules = {
                // Text fields (non-empty)
                textField: (value) => value && value.trim() !== '',

                // Numeric fields (positive numbers)
                nonNegativeNumber: (value) => !isNaN(parseFloat(value)) && parseFloat(value) >= 0,

                // Select fields (must have a selected value)
                selectField: (value) => value && value !== '',

                // Date field (not empty and not in the past)
                dateField: (value) => {
                // Check if a value was provided
                if (!value) return false;

                // Parse the input value into a Date object
                const inputDate = new Date(value);

                // No restrictions - allow previous, current, and future dates
                return true;
                }
            };

            // Apply validation to specific fields
            const fieldsToValidate = [
                { selector: '#yearStay', validate: validationRules.positiveNumber, message: 'Please enter a valid year of stay' },
                { selector: '#ownHouse', validate: validationRules.selectField, message: 'Please enter a valid house value' },
                { selector: '#renting', validate: validationRules.selectField, message: 'Please select a renting option' },
                { selector: '#livingWithRelative', validate: validationRules.selectField, message: 'Please select living arrangement' },
                { selector: '#dependentCount', validate: validationRules.nonNegativeNumber, message: 'Please enter a valid number of dependents' },
                { selector: '#dependentInSchool', validate: validationRules.nonNegativeNumber, message: 'Please enter a valid number of dependents in school' },
                { selector: '#maritalStatus', validate: validationRules.selectField, message: 'Please select your marital status' },
                { selector: '#dateLoan', validate: validationRules.dateField, message: 'Please select a valid future date' },
                { selector: '#employer_name', validate: validationRules.textField, message: 'Please enter a valid employer name' },
                { selector: '#employer_address', validate: validationRules.textField, message: 'Please enter a valid employer address' },
                { selector: '#present_position', validate: validationRules.textField, message: 'Present position is required' },
                { selector: '#date_of_employment', validate: validationRules.dateField, message: 'Valid employment date is required' },
                { selector: '#monthly_income', validate: validationRules.positiveNumber, message: 'Valid monthly income is required' },
                { selector: '#contact_person', validate: validationRules.textField, message: 'Contact person is required' },
                { selector: '#contact_telephone_no', validate: validationRules.positiveNumber, message: 'Contact number is required' },
                { selector: '#self_employed_business_type', validate: validationRules.textField, message: 'Business type is required' },
                { selector: '#business_start_date', validate: validationRules.dateField, message: 'Valid business start date is required' },
                { selector: '#family_member_count', validate: validationRules.positiveNumber, message: 'Valid family member count is required' },
                { selector: '#other_income', validate: validationRules.textField, message: 'Other income description is required' },
                { selector: '#self_other_income_amount', validate: validationRules.positiveNumber, message: 'Valid other income amount is required' },
                // Spouse Income Information (will be conditionally validated)
                { selector: '#spouse_income', validate: validationRules.textField, message: 'Spouse income description is required' },
                { selector: '#spouse_income_amount', validate: validationRules.positiveNumber, message: 'Valid spouse income amount is required' },
                { selector: '#food_groceries_expense', validate: validationRules.positiveNumber, message: 'Valid food and groceries expense is required' },
                { selector: '#gas_oil_transportation_expense', validate: validationRules.positiveNumber, message: 'Valid transportation expense is required' },
                { selector: '#schooling_expense', validate: validationRules.positiveNumber, message: 'Valid schooling expense is required' },
                { selector: '#utilities_expense', validate: validationRules.positiveNumber, message: 'Valid utilities expense is required' },
                { selector: '#miscellaneous_expense', validate: validationRules.positiveNumber, message: 'Valid miscellaneous expense is required' },
                { selector: '#total_expenses', validate: validationRules.positiveNumber, message: 'Valid total expenses is required' },
                { selector: '#net_family_income', validate: validationRules.positiveNumber, message: 'Valid net family income is required' },
                
                {
                    selector: '#spouseName', validate: function (value) {
                        const maritalStatus = $('#maritalStatus').val();
                        if (maritalStatus === 'Married') {
                            return validationRules.textField(value);
                        }
                        return true;
                    }, message: 'Spouse name is required for married status'
                }
            ];

                $('#maritalStatus').on('change', function () {
                    const maritalStatus = $(this).val();
                    const singleStatuses = ['Single', 'Widowed', 'Separated', 'Divorced'];
                    
                    if (singleStatuses.includes(maritalStatus)) {
                        // Hide spouse-related inputs
                        $('#spouse_income, #spouse_income_amount, #spouse_other_income, #spouse_other_income_amount')
                            .val('')
                            .prop('disabled', true); // Disable inputs to prevent form submission
                    } else {
                        // Show spouse-related inputs
                        $('#spouse_income, #spouse_income_amount, #spouse_other_income, #spouse_other_income_amount')
                            .prop('disabled', false);
                    }
                });

              // Function to parse float with fallback to 0
                function safeParseFloat(value) {
                    const parsed = parseFloat(value);
                    return isNaN(parsed) ? 0 : parsed;
                }

                // Function to calculate net family income and total expenses
                function calculateFinancials() {
                const monthly_income = safeParseFloat($('#monthly_income').val());
                const selfOtherIncomeAmount = safeParseFloat($('#self_other_income_amount').val());
                const maritalStatus = $('#maritalStatus').val();
                
                let spouseIncomeAmount = 0;
                let spouseOtherIncomeAmount = 0;

                if (maritalStatus === 'Married') {
                    spouseIncomeAmount = safeParseFloat($('#spouse_income_amount').val()) || 0;
                    spouseOtherIncomeAmount = safeParseFloat($('#spouse_other_income_amount').val()) || 0;
                }

                console.log("Spouse Income:", spouseIncomeAmount);  
                console.log("Spouse Other Income:", spouseOtherIncomeAmount);  

                const foodGroceriesExpense = safeParseFloat($('#food_groceries_expense').val());
                const gasOilTransportationExpense = safeParseFloat($('#gas_oil_transportation_expense').val());
                const schoolingExpense = safeParseFloat($('#schooling_expense').val());
                const utilitiesExpense = safeParseFloat($('#utilities_expense').val());
                const miscellaneousExpense = safeParseFloat($('#miscellaneous_expense').val());

                const totalIncome = monthly_income + selfOtherIncomeAmount + spouseIncomeAmount + spouseOtherIncomeAmount;
                const totalExpenses = foodGroceriesExpense + gasOilTransportationExpense + schoolingExpense + utilitiesExpense + miscellaneousExpense;
                const netFamilyIncome = totalIncome - totalExpenses;

                console.log("Total Income After Calculation:", totalIncome);
                console.log("Net Family Income After Calculation:", netFamilyIncome);

                $('#net_family_income').val(netFamilyIncome.toFixed(2));
                $('#total_expenses').val(totalExpenses.toFixed(2));

                return { netFamilyIncome, totalExpenses };
            }



                // Add calculation event listeners to relevant fields
                [
                    '#monthly_income', 
                    '#self_other_income_amount', 
                    '#spouse_income_amount', 
                    '#spouse_other_income_amount',
                    '#food_groceries_expense',
                    '#gas_oil_transportation_expense',
                    '#schooling_expense',
                    '#utilities_expense',
                    '#miscellaneous_expense'
                ].forEach(selector => {
                    $(selector).on('input change', calculateFinancials);
                });
           // Override form submission
    $('form').on('submit', function (e) {
        // Trigger validation for all fields
        const fieldsToValidate = [/* ... existing field validation ... */];
        fieldsToValidate.forEach(field => {
            $(field.selector).trigger('change');
        });

    // Get dependent count
    const dependentCount = parseInt($('#dependentCount').val(), 10) || 0;
    const dependentInSchool = parseInt($('#dependentInSchool').val(), 10) || 0;

    // If dependents exist, validate them; otherwise, remove validation
    if (dependentCount > 0) {
        for (let i = 1; i <= dependentCount; i++) {
            $(`#dependent${i}Name, #dependent${i}Age`).trigger('input');

            // Only validate grade level if dependent is in school
            if (dependentInSchool > 0) {
                $(`#dependent${i}Grade`).trigger('input');
            }
        }
    } else {
        // Remove validation for all dependent fields
        $('[id^=dependent]').removeClass('is-invalid is-valid');
        $('.validation-message').remove();
    }

        // Check financials
        const financials = calculateFinancials();

        // Define income thresholds (these can be adjusted)
        const MIN_INCOME_THRESHOLD = 15000; // Minimum monthly income to qualify
        const INCOME_TO_EXPENSE_RATIO = 3; // Income should be at least 3x expenses

        // Validate income
        if (financials.netFamilyIncome < MIN_INCOME_THRESHOLD) {
            e.preventDefault();
            Swal.fire({
                title: 'Income Too Low',
                text: `Your monthly net family income (₱${financials.netFamilyIncome.toFixed(2)}) is below the minimum threshold of ₱${MIN_INCOME_THRESHOLD}. 
                       Unfortunately, you do not qualify for this loan at this time.`,
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        // Validate income to expense ratio
        if (financials.totalIncome < (financials.totalExpenses * INCOME_TO_EXPENSE_RATIO)) {
            e.preventDefault();
            Swal.fire({
                title: 'Insufficient Income',
                text: `Your total family income (₱${financials.totalIncome.toFixed(2)}) 
                    is not sufficiently higher than your expenses (₱${financials.totalExpenses.toFixed(2)}). 
                    We recommend reducing expenses or increasing income before applying.`,
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
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
            return false;
        }

        // If all validations pass, the form will submit
        return true;
    });

    // Initial calculation on page load
    calculateFinancials();
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