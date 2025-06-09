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
    <title>Regular Loan Form 7 | Member</title>
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

                if (!isset($_SESSION['user_id'])) {
                    header("Location: member-login.php");
                    exit();
                }

                $user_id = $_SESSION['user_id'];
                
                // Get the loan application details
                $query = "SELECT * FROM loanapplication WHERE userID = ? ORDER BY LoanID DESC LIMIT 1";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $loan = $result->fetch_assoc();
                ?>

                <div class="container py-5">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0" style="text-align:center; background-color:var(--secondary-color;)">Loan Application Review - <?php echo htmlspecialchars($loan['LoanType']); ?> Loan</h3>
                        </div>
                        <div class="card-body">
                            <!-- Loan Details Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Loan Details</h5>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Loan ID:</strong> <?php echo htmlspecialchars($loan['LoanID']); ?></p>
                                    <p><strong>Date of Application:</strong> <?php echo htmlspecialchars($loan['DateOfLoan']); ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Amount Requested:</strong> ₱<?php echo number_format($loan['AmountRequested'], 2); ?></p>
                                    <p><strong>Loan Term:</strong> <?php echo htmlspecialchars($loan['LoanTerm']); ?> months</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Purpose:</strong> <?php echo htmlspecialchars($loan['Purpose']); ?></p>
                                    <p><strong>Mode of Payment:</strong> <?php echo htmlspecialchars($loan['ModeOfPayment']); ?></p>
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Personal Information</h5>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Years at Present Address:</strong> <?php echo htmlspecialchars($loan['years_stay_present_address']); ?></p>
                                    <p><strong>Housing Status:</strong> 
                                        <?php
                                        if ($loan['own_house'] === 'Yes') echo 'Own House';
                                        elseif ($loan['renting'] === 'Yes') echo 'Renting';
                                        elseif ($loan['living_with_relative'] === 'Yes') echo 'Living with Relative';
                                        ?>
                                    </p>
                                    <p><strong>Marital Status:</strong> <?php echo htmlspecialchars($loan['marital_status']); ?></p>
                                    <?php if ($loan['marital_status'] === 'Married'): ?>
                                        <p><strong>Spouse Name:</strong> <?php echo htmlspecialchars($loan['spouse_name']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Dependents Information -->
                            <?php if ($loan['number_of_dependents'] > 0): ?>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Dependents Information</h5>
                                </div>
                                <div class="col-12">
                                    <p><strong>Number of Dependents:</strong> <?php echo htmlspecialchars($loan['number_of_dependents']); ?></p>
                                    <p><strong>Dependents in School:</strong> <?php echo htmlspecialchars($loan['dependents_in_school']); ?></p>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Age</th>
                                                    <th>Grade Level</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for($i = 1; $i <= 4; $i++): ?>
                                                    <?php if (!empty($loan["dependent{$i}_name"])): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($loan["dependent{$i}_name"]); ?></td>
                                                            <td><?php echo htmlspecialchars($loan["dependent{$i}_age"]); ?></td>
                                                            <td><?php echo htmlspecialchars($loan["dependent{$i}_grade_level"]); ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Employment Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Employment/Business Information</h5>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Employer Name:</strong> <?php echo htmlspecialchars($loan['employer_name']); ?></p>
                                    <p><strong>Employer Address:</strong> <?php echo htmlspecialchars($loan['employer_address']); ?></p>
                                    <p><strong>Position:</strong> <?php echo htmlspecialchars($loan['present_position']); ?></p>
                                    <p><strong>Date Of Employment:</strong> 
                                        <?php 
                                        if (!empty($loan['date_of_employment'])) {
                                            $employmentDate = new DateTime($loan['date_of_employment']);
                                            echo htmlspecialchars($employmentDate->format('F j, Y'));
                                        }
                                        ?>
                                    </p>
                    
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($loan['contact_person']); ?></p>
                                    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($loan['contact_telephone_no']); ?></p>
                                    <?php if (!empty($loan['self_employed_business_type'])): ?>
                                    <p><strong>Business Type:</strong> <?php echo htmlspecialchars($loan['self_employed_business_type']); ?></p>
                                    <p><strong>Business Start Date:</strong> 
                                        <?php 
                                        if (!empty($loan['business_start_date'])) {
                                            $businessStartDate = new DateTime($loan['business_start_date']);
                                            echo htmlspecialchars($businessStartDate->format('F j, Y'));
                                        }
                                        ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Financial Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Financial Information</h5>
                                </div>
                                <div class="col-md-6">
                                    <h6>Income</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Monthly Income (<?php echo htmlspecialchars($loan['present_position']); ?>)</td>
                                            <td>₱<?php echo number_format($loan['monthly_income'], 2); ?></td>
                                        </tr>
                                        <?php if (!empty($loan['self_other_income_amount'])): ?>
                                        <tr>
                                            <td>Other Income (<?php echo htmlspecialchars($loan['other_income']); ?>)</td>
                                            <td>₱<?php echo number_format($loan['self_other_income_amount'], 2); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($loan['spouse_income'])): ?>
                                        <tr>
                                            <td>Spouse Income (<?php echo htmlspecialchars($loan['spouse_income']); ?>)</td>
                                            <td>₱<?php echo number_format($loan['spouse_income_amount'], 2); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if (!empty($loan['spouse_other_income_amount'])): ?>
                                        <tr>
                                            <td>Spouse Other Income</td>
                                            <td>₱<?php echo number_format($loan['spouse_other_income_amount'], 2); ?></td>
                                        </tr>
                                        <?php endif; ?>

                                        <!-- Always Display Total Income -->
                                        <tr class="table-primary">
                                            <td><strong>Total Income</strong></td>
                                            <td><strong>₱<?php 
                                                $total_income = 
                                                    (float) ($loan['monthly_income'] ?? 0) + 
                                                    (float) ($loan['self_other_income_amount'] ?? 0) + 
                                                    (float) ($loan['spouse_income_amount'] ?? 0) + 
                                                    (float) ($loan['spouse_other_income_amount'] ?? 0);

                                                echo number_format($total_income, 2); 
                                            ?></strong></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h6>Monthly Expenses</h6>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Food & Groceries</td>
                                            <td>₱<?php echo number_format($loan['food_groceries_expense'], 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Transportation</td>
                                            <td>₱<?php echo number_format($loan['gas_oil_transportation_expense'], 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Schooling</td>
                                            <td>₱<?php echo number_format($loan['schooling_expense'], 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Utilities</td>
                                            <td>₱<?php echo number_format($loan['utilities_expense'], 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Miscellaneous</td>
                                            <td>₱<?php echo number_format($loan['miscellaneous_expense'], 2); ?></td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td><strong>Total Expenses</strong></td>
                                            <td><strong>₱<?php echo number_format($loan['total_expenses'], 2); ?></strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>


                            <!-- Bank Accounts -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Bank Accounts</h5>
                                </div>
                                <?php if ($loan['savings_account']): ?>
                                <div class="col-md-4">
                                    <h6>Savings Account</h6>
                                    <p><strong>Bank:</strong> <?php echo htmlspecialchars($loan['savings_bank']); ?></p>
                                    <p><strong>Branch:</strong> <?php echo htmlspecialchars($loan['savings_branch']); ?></p>
                                </div>
                                <?php endif; ?>
                                <?php if ($loan['current_account']): ?>
                                <div class="col-md-4">
                                    <h6>Current Account</h6>
                                    <p><strong>Bank:</strong> <?php echo htmlspecialchars($loan['current_bank']); ?></p>
                                    <p><strong>Branch:</strong> <?php echo htmlspecialchars($loan['current_branch']); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Assets -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Assets</h5>
                                </div>
                                <div class="col-12">
                                    <ul class="list-unstyled">
                                        <?php for($i = 1; $i <= 4; $i++): ?>
                                            <?php if (!empty($loan["assets$i"])): ?>
                                                <li><?php echo htmlspecialchars($loan["assets$i"]); ?></li>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                            </div>

                            <!-- Credit Information -->
                            <?php
                            $has_creditors = false;
                            for($i = 1; $i <= 4; $i++) {
                                if (!empty($loan["creditor{$i}_name"])) {
                                    $has_creditors = true;
                                    break;
                                }
                            }
                            ?>
                            <?php if ($has_creditors): ?>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Credit Information</h5>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Creditor Name</th>
                                                    <th>Address</th>
                                                    <th>Original Amount</th>
                                                    <th>Present Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for($i = 1; $i <= 4; $i++): ?>
                                                    <?php if (!empty($loan["creditor{$i}_name"])): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($loan["creditor{$i}_name"]); ?></td>
                                                            <td><?php echo htmlspecialchars($loan["creditor{$i}_address"]); ?></td>
                                                            <td>₱<?php echo number_format($loan["creditor{$i}_original_amount"], 2); ?></td>
                                                            <td>₱<?php echo number_format($loan["creditor{$i}_present_balance"], 2); ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- References -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Character References</h5>
                                </div>
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Address</th>
                                                    <th>Contact Number</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php for($i = 1; $i <= 3; $i++): ?>
                                                    <?php if (!empty($loan["reference{$i}_name"])): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($loan["reference{$i}_name"]); ?></td>
                                                            <td><?php echo htmlspecialchars($loan["reference{$i}_address"]); ?></td>
                                                            <td><?php echo htmlspecialchars($loan["reference{$i}_contact_no"]); ?></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Submitted Documents -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">Submitted Documents</h5>
                                </div>
                                <div class="col-12">
                                    <div class="row g-3">
                                        <?php if (!empty($loan['valid_id_path'])): ?>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">Valid ID</h6>
                                                    <p class="card-text">Filename: <?php echo htmlspecialchars($loan['valid_id_path']); ?></p>
                                                    <a href="../dist/assets/images/proofs/<?php echo htmlspecialchars($loan['valid_id_path']); ?>" 
                                                    class="btn btn-primary btn-sm" target="_blank">View Document</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <?php if (!empty($loan['deed_of_sale_path'])): ?>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">Deed of Sale</h6>
                                                    <p class="card-text">Filename: <?php echo htmlspecialchars($loan['deed_of_sale_path']); ?></p>
                                                    <a href="../dist/assets/images/proofs/<?php echo htmlspecialchars($loan['deed_of_sale_path']); ?>" 
                                                    class="btn btn-primary btn-sm" target="_blank">View Document</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <?php if (!empty($loan['vehicle_orcr_path'])): ?>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">Vehicle ORCR</h6>
                                                    <p class="card-text">Filename: <?php echo htmlspecialchars($loan['vehicle_orcr_path']); ?></p>
                                                    <a href="../dist/assets/images/proofs/<?php echo htmlspecialchars($loan['vehicle_orcr_path']); ?>" 
                                                    class="btn btn-primary btn-sm" target="_blank">View Document</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <?php if (!empty($loan['proof_of_income_path'])): ?>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">Proof of Income</h6>
                                                    <p class="card-text">Filename: <?php echo htmlspecialchars($loan['proof_of_income_path']); ?></p>
                                                    <a href="../dist/assets/images/proofs/<?php echo htmlspecialchars($loan['proof_of_income_path']); ?>" 
                                                    class="btn btn-primary btn-sm" target="_blank">View Document</a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php 
                            $loan['total_income'] = 
                                (float) ($loan['monthly_income'] ?? 0) + 
                                (float) ($loan['self_other_income_amount'] ?? 0) + 
                                (float) ($loan['spouse_income_amount'] ?? 0) + 
                                (float) ($loan['spouse_other_income_amount'] ?? 0);
                            ?>

                            <!-- Application Status Summary -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Application Summary</h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p><strong>Net Family Income:</strong> ₱<?php echo number_format($loan['net_family_income'], 2); ?></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>Total Monthly Income:</strong> ₱<?php echo number_format($loan['total_income'], 2); ?></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>Total Monthly Expenses:</strong> ₱<?php echo number_format($loan['total_expenses'], 2); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-12 text-end">
                                    <a href="print-application.php?id=<?php echo $loan['LoanID']; ?>" 
                                    class="btn btn-primary me-2" target="_blank">
                                        <i class="fas fa-print"></i> Print Application
                                    </a>
                                    <a href="javascript:void(0);" class="btn btn-success" id="nextButton">Next</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                $(document).ready(function() {
                    document.getElementById("nextButton").addEventListener("click", function() {
                        // Get the LoanID from PHP
                        const loanID = <?php echo $loan['LoanID']; ?>;
                        
                        // Call run_prediction.php with LoanID as a parameter
                        fetch(`run_prediction.php?LoanID=${loanID}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.text(); // First get the raw text
                        })
                        .then(text => {
                            console.log("📌 Raw Response:", text);
                            try {
                                let data = JSON.parse(text);
                                console.log("✅ Parsed Response:", data);
                                
                                // Check for success based on the actual response structure
                                if (data.success === true) {
                                    // Redirect to success page with loan ID and eligibility status
                                    window.location.href = `success_regular.php?loanID=${data.LoanID}&status=${data.UpdatedEligibility}`;
                                } else {
                                    console.error("❌ API returned false success:", data);
                                    alert("Application processing failed. Please try again.");
                                }
                            } catch (error) {
                                console.error("❌ JSON Parse Error:", error, "Response text:", text);
                                alert("Error processing application data. Please contact support.");
                            }
                        })
                        .catch(error => {
                            console.error("❌ Fetch Error:", error);
                            alert("Network error occurred. Please check your connection and try again.");
                        });
                    });
                });
                </script>
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

            // File validation function
            function validateFile(file, isRequired = true) {
                if (!file && !isRequired) {
                    return { valid: true, message: '' };
                }

                if (!file && isRequired) {
                    return { valid: false, message: 'This document is required' };
                }

                // Check file size
                if (file.size > maxFileSize) {
                    return { valid: false, message: 'File size must be less than 5MB' };
                }

                // Check file type
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

            // Special handling for optional Deed of Sale
            $('#deed_of_sale').prop('required', false);

            // Form submission validation
            $('#documentForm').on('submit', function(e) {
                let isValid = true;
                const requiredMessage = 'This document is required';

                // Validate Valid ID
                const validIDFile = $('#validID')[0].files[0];
                const validIDValidation = validateFile(validIDFile);
                showValidation('#validID', validIDValidation.valid, validIDValidation.message || requiredMessage);
                if (!validIDValidation.valid) isValid = false;

                // Validate Deed of Sale (optional)
                const deedFile = $('#deed_of_sale')[0].files[0];
                if (deedFile) {
                    const deedValidation = validateFile(deedFile, false);
                    showValidation('#deed_of_sale', deedValidation.valid, deedValidation.message);
                    if (!deedValidation.valid) isValid = false;
                }

                // Validate ORCR
                const orcrFile = $('#orcr_vehicle')[0].files[0];
                const orcrValidation = validateFile(orcrFile);
                showValidation('#orcr_vehicle', orcrValidation.valid, orcrValidation.message || requiredMessage);
                if (!orcrValidation.valid) isValid = false;

                // Validate Proof of Income
                const incomeFile = $('#proof_of_income')[0].files[0];
                const incomeValidation = validateFile(incomeFile);
                showValidation('#proof_of_income', incomeValidation.valid, incomeValidation.message || requiredMessage);
                if (!incomeValidation.valid) isValid = false;

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