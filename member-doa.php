<?php
include 'connection/config.php';

// Function to validate and sanitize input
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
$userID = isset($_GET['member-id']) ? intval($_GET['member-id']) : 0;
if (!empty($userID)) {
    $stmt = $conn->prepare("SELECT age FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $userAge = $userData['age'];
}


// Function to calculate membership totals
function calculateMembershipTotals($membershipType, $age) {
    $totals = [
        'share_capital' => 0,
        'savings' => 1000,
        'insurance' => 0,
        'membership_fee' => 300,
        'total_amount' => 0
    ];

    // Set share capital based on membership type
    $totals['share_capital'] = ($membershipType === 'Regular') ? 5000 : 1000;
    
    // Set insurance based on age
    $totals['insurance'] = ($age >= 65 && $age <= 75) ? 550 : 450;
    
    // Calculate total amount
    $totals['total_amount'] = $totals['share_capital'] + $totals['savings'] + 
                             $totals['insurance'] + $totals['membership_fee'];
    
    return $totals;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $userID = isset($_POST['member-id']) ? intval($_POST['member-id']) : 0;
    $appointmentDate = isset($_POST['appointment-date']) ? sanitizeInput($_POST['appointment-date']) : '';
    $membershipType = isset($_POST['membership-type']) ? sanitizeInput($_POST['membership-type']) : '';

    // Validate inputs
    if (empty($userID) || empty($appointmentDate) || empty($membershipType)) {
        die("Error: All fields are required.");
    }

    // Validate appointment date is within range
    $currentDate = date('Y-m-d');
    $maxDate = date('Y-m-d', strtotime('+30 days'));

    if ($appointmentDate < $currentDate || $appointmentDate > $maxDate) {
        die("Error: Appointment date must be within the next 30 days and cannot be in the past.");
    }

    // Start a database transaction for data integrity
    $conn->begin_transaction();

    try {
        // Fetch member details including age
        $memberQuery = $conn->prepare("SELECT first_name, last_name, email
            FROM users WHERE user_id = ?");
        $memberQuery->bind_param('i', $userID);
        $memberQuery->execute();
        $memberResult = $memberQuery->get_result();

        if ($memberResult->num_rows === 0) {
            throw new Exception("Member details not found.");
        }

        $memberData = $memberResult->fetch_assoc();
        $firstName = $memberData['first_name'];
        $lastName = $memberData['last_name'];
        $email = $memberData['email'];
        $age = $memberData['age'];

        // Calculate membership totals
        $totals = calculateMembershipTotals($membershipType, $age);
        
        // Predefined service ID for membership application
        $serviceID = 13;
        $description = "Membership Payment";

      
        $appointmentStmt = $conn->prepare("
        INSERT INTO appointments (
            user_id, appointmentdate, last_name, first_name, email, 
            description, serviceID, share_capital, savings, insurance, 
            membership_fee, total_amount
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $appointmentStmt->bind_param(
        'isssssiiiiid',
        $userID,
        $appointmentDate,
        $lastName,
        $firstName,
        $email,
        $description,
        $serviceID,
        $totals['share_capital'],
        $totals['savings'],
        $totals['insurance'],
        $totals['membership_fee'],
        $totals['total_amount']
        );

        // Add this line to execute the insert:
        $appointmentStmt->execute();

        // Also, let's add error checking:
        if ($appointmentStmt->errno) {
        throw new Exception("Error inserting appointment: " . $appointmentStmt->error);
        }
        

        // Update membership type and set membership status to Pending
        $updateUserStmt = $conn->prepare("UPDATE users SET membership_type = ?, membership_status = 'Pending' WHERE user_id = ?");
        $updateUserStmt->bind_param('si', $membershipType, $userID);
        $updateUserStmt->execute();

        // Update appointment date and status in member_applications
        $updateAppStmt = $conn->prepare("UPDATE member_applications SET appointment_date = ?, status = ? WHERE user_id = ?");
        $updateAppStmt->bind_param('ssi', $appointmentDate, $status, $userID);
        $updateAppStmt->execute();


        // Commit the transaction
        $conn->commit();

        // Redirect to submit page
        header("Location: submit.php?member-id=" . $userID);
        exit();

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
}

// Handle GET request to load the page
$userID = isset($_GET['member-id']) ? intval($_GET['member-id']) : 0;
if (empty($userID)) {
    die("Error: Member ID is missing.");
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
    <style>
        :root {
            --primary-color: #00FFAF;
            --secondary-color: #0F4332;
            --dark-color: #1f1f1f;
            --accent-color: #00FFAF;
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
            text-align: center;
        }

        .apply-loan:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 115, 232, 0.3);
        }

        /* Enhanced Footer */
        .footer {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            color: white !important;
            padding: 80px 0 30px;
            margin-top: 3rem;
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
            color: white !important;
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
            color: white !important;
            font-size: 0.9rem;
        }

        /* Base Styles */
        .membership-container {
            max-width: 100%;
            margin: 20px auto;
            padding: 0 20px;
        }

        /* Progress Steps Responsive Modifications */
        .progress-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 1rem 0;
            position: relative;
            width: 100%;
        }

        .step-line {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            height: 2px;
            background: #e9ecef;
            z-index: 0;
            width: 100%;
        }

        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-weight: 600;
            margin: 0 10px;
            z-index: 1;
            position: relative;
        }

        .step.active {
            background: var(--accent-color, #0F4332);
            color: white;
        }

        /* Button Responsiveness */
        .d-flex.justify-content-center.gap-4.mt-5 {
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }

        .btn-previous, 
        .btn-next {
            width: 100%;
            max-width: 250px;
            padding: 10px !important;
        }

        /* Form Input Responsiveness */
        .row.mb-3 {
            margin-bottom: 1rem;
        }

        .form-control, 
        .form-select {
            width: 100%;
            border-radius: 4px;
            padding: 8px 12px;
        }

        /* Tablet Styles */
        @media screen and (min-width: 768px) {
            .membership-container {
                max-width: 90%;
                margin: 50px auto;
            }

            .d-flex.justify-content-center.gap-4.mt-5 {
                flex-direction: row;
                gap: 20px;
            }

            .btn-previous, 
            .btn-next {
                width: 160px;
            }

            .step {
                width: 40px;
                height: 40px;
                margin: 0 20px;
            }
        }

        /* Desktop Styles */
        @media screen and (min-width: 1024px) {
            .membership-container {
                max-width: 500px;
                margin: 50px auto;
            }

            .step {
                width: 40px;
                height: 40px;
                margin: 0 2rem;
            }
        }

        /* Ensure Proper Box Sizing */
        * {
            box-sizing: border-box;
        }

        /* Accessibility and Touch Targets */
        @media (max-width: 767px) {
            .step {
                min-width: 40px;
                min-height: 40px;
            }
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

    <div class="row justify-content-center">
        <div class="membership-container col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Date of Appointment</h4><br>
                    <form action="member-doa.php?member-id=<?php echo htmlspecialchars($userID); ?>" method="post">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="appointment-date" class="form-label">Date of Appointment:</label>
                            <input type="date" class="form-control" id="appointment-date" name="appointment-date" placeholder="mm/dd/yyyy"  min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>" required>
                        </div>
                        <div class="col-6">
                            <label for="membership-type" class="form-label">Membership Type:</label>
                            <select class="form-select" id="membership-type" name="membership-type">
                                <option value="Associate">Associate Membership</option>
                                <option value="Regular">Regular Membership</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3" id="regular-membership-details" style="display: none;">
                    <h4 class="card-title">Membership Information</h4><br>
                        <div class="row">
                            <div class="col-6">
                                <label for="share-capital" class="form-label">Share Capital:</label>
                                <input type="text" class="form-control" id="share-capital" value="5,000" readonly>
                            </div>
                            <div class="col-6">
                                <label for="savings" class="form-label">Savings:</label>
                                <input type="text" class="form-control" id="savings" value="1,000" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label for="insurance-18-64" class="form-label">Insurance (18-64 years old):</label>
                                <input type="text" class="form-control" id="insurance-18-64" value="450.00 PHP" readonly>
                            </div>
                            <div class="col-6">
                                <label for="insurance-65-75" class="form-label">Insurance (65-75 years old):</label>
                                <input type="text" class="form-control" id="insurance-65-75" value="550.00 PHP" readonly>
                            </div>
                            <div class="col-6">
                                <label for="membership-fee" class="form-label">Membership Fee:</label>
                                <input type="text" class="form-control" id="membership-fee" value="300" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="total-display">
                                <?php if ($userAge >= 65 && $userAge <= 75): ?>
                                    <label for="total-65-75" class="form-label">Total Amount Due:</label>
                                    <input type="text" class="form-control" id="total-65-75" value="6,850 Pesos" readonly>
                                <?php else: ?>
                                    <label for="total-18-64" class="form-label">Total Amount Due:</label>
                                    <input type="text" class="form-control" id="total-18-64" value="6,750 Pesos" readonly>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3" id="associate-membership-details" style="display: none;">
                    <h4 class="card-title">Membership Information</h4><br>
                        <div class="row">
                            <div class="col-6">
                                <label for="share-capital" class="form-label">Share Capital:</label>
                                <input type="text" class="form-control" id="share-capital" value="1,000" readonly>
                            </div>
                            <div class="col-6">
                                <label for="savings" class="form-label">Savings:</label>
                                <input type="text" class="form-control" id="savings" value="1,000" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label for="insurance-18-64" class="form-label">Insurance (18-64 years old):</label>
                                <input type="text" class="form-control" id="insurance-18-64" value="450.00 PHP" readonly>
                            </div>
                            <div class="col-6">
                                <label for="insurance-65-75" class="form-label">Insurance (65-75 years old):</label>
                                <input type="text" class="form-control" id="insurance-65-75" value="550.00 PHP" readonly>
                            </div>
                            <div class="col-6">
                                <label for="membership-fee" class="form-label">Membership Fee:</label>
                                <input type="text" class="form-control" id="membership-fee" value="300" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="total-display">
                                <?php if ($userAge >= 65 && $userAge <= 75): ?>
                                    <label for="total-65-75" class="form-label">Total Amount Due:</label>
                                    <input type="text" class="form-control" id="total-65-75" value="2,850 Pesos" readonly>
                                <?php else: ?>
                                    <label for="total-18-64" class="form-label">Total Amount Due:</label>
                                    <input type="text" class="form-control" id="total-18-64" value="2,750 Pesos" readonly>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="progress-steps">
                        <div class="step-line"></div>
                        <div class="step">1</div>
                        <div class="step">2</div>
                        <div class="step active">3</div>
                        <div class="step">4</div>
                    </div>
                    <div class="d-flex justify-content-center gap-4 mt-5">
                        <!-- Hidden field to store member ID -->
                        <input type="hidden" name="member-id" value="<?php echo htmlspecialchars($userID); ?>"> 
                        <button type="button" name="previousBtn" class="btn btn-previous px-5 py-2" style="background-color: #E9ECEF; border: none; border-radius: 5px; color: #666; width: 160px;"><a href="videoseminar.php?member-id=2" style="text-decoration:none; color:grey;">Previous</a></button>
                        <button type="submit" name="nextBtn" class="btn btn-next px-5 py-2" style="background-color: #0F4332; border: none; border-radius: 5px; color: white; width: 160px;">Next</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <br> <br> <br> <br> <br> <br> <br>
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
            // Remove any pre-existing active classes
            link.classList.remove('active');

            // Check if link's href matches current page
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

    // Function to handle checklist redirection
    function continueToNextStep() {
        window.location.href = "member-application.php";
    }

    // Optional: Add event listener for mobile menu
    document.addEventListener('DOMContentLoaded', function() {
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarCollapse = document.querySelector('.navbar-collapse');

        if (navbarToggler && navbarCollapse) {
            navbarToggler.addEventListener('click', function() {
                navbarCollapse.classList.toggle('show');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!navbarCollapse.contains(event.target) && !navbarToggler.contains(event.target)) {
                    navbarCollapse.classList.remove('show');
                }
            });

            // Close mobile menu when clicking on a link
            const mobileNavLinks = navbarCollapse.querySelectorAll('.nav-link');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navbarCollapse.classList.remove('show');
                });
            });
        }
    });

    // Optional: Add scroll to top functionality
    window.addEventListener('scroll', function() {
        const scrollToTop = document.querySelector('.scroll-to-top');
        if (scrollToTop) {
            if (window.pageYOffset > 100) {
                scrollToTop.style.display = 'block';
            } else {
                scrollToTop.style.display = 'none';
            }
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
    const associateMembershipDetails = document.getElementById('associate-membership-details');
    const regularMembershipDetails = document.getElementById('regular-membership-details');
    const membershipTypeSelect = document.getElementById('membership-type');
    
    // Get user's age from PHP
    const userAge = <?php echo $userAge ?? 0; ?>;
    
    function updateMembershipDetails(selectedType) {
        // Hide both sections initially
        associateMembershipDetails.style.display = 'none';
        regularMembershipDetails.style.display = 'none';
        
        const isOlder = userAge >= 65 && userAge <= 75;
        const details = selectedType === 'associate' ? associateMembershipDetails : regularMembershipDetails;
        
        if (details) {
            details.style.display = 'block';
            
            // Update the visible total based on age
            const total1864 = details.querySelector('#total-18-64');
            const total6575 = details.querySelector('#total-65-75');
            
            if (isOlder) {
                total1864.parentElement.style.display = 'none';
                total6575.parentElement.style.display = 'block';
            } else {
                total1864.parentElement.style.display = 'block';
                total6575.parentElement.style.display = 'none';
            }
        }
    }
    
    membershipTypeSelect.addEventListener('change', (e) => {
        updateMembershipDetails(e.target.value.toLowerCase());
    });
    
    // Initial update
    updateMembershipDetails(membershipTypeSelect.value.toLowerCase());
});

    document.getElementById('previous-btn').addEventListener('click', () => {
      // Add your previous button logic here
    });

    document.getElementById('next-btn').addEventListener('click', () => {
      // Add your next button logic here
    });
   
</script>
</body>
</html>