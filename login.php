<?php
include 'connection/config.php';
session_start();

// Initialize failed attempts counter if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Check if user is temporarily blocked
if (isset($_SESSION['login_blocked_until'])) {
    if (time() < $_SESSION['login_blocked_until']) {
        $remaining_time = $_SESSION['login_blocked_until'] - time();
        $error[] = "Too many failed attempts. Please try again in " . gmdate("i:s", $remaining_time);
    } else {
        // Timeout expired, reset attempts
        unset($_SESSION['login_blocked_until']);
        $_SESSION['login_attempts'] = 0;
    }
}

function logActivity($user_id, $user_type, $activity) {
    global $conn;
    $sql = "INSERT INTO activity_logs (user_id, user_type, action, details) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $user_type, $activity, $activity);
    $stmt->execute();
}

if (isset($_POST['submit'])) {
    // Check if user is blocked
    if (isset($_SESSION['login_blocked_until']) && time() < $_SESSION['login_blocked_until']) {
        $remaining_time = $_SESSION['login_blocked_until'] - time();
        $error[] = "Account temporarily locked. Try again in " . gmdate("i:s", $remaining_time);
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = $_POST['password'];
        $remember = isset($_POST['remember']) ? true : false;

        $select = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($select);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($pass, $row['password'])) {
                // Successful login - reset attempts
                $_SESSION['login_attempts'] = 0;
                
                // Check membership status for Member user type
                if ($row['user_type'] == 'Member' && $row['membership_status'] == 'Pending') {
                    $error[] = 'Your membership is still pending approval. Please wait for activation.';
                } else {
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['name'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['uploadID'] = $row['uploadID'];
                    $_SESSION['full_name'] = $row['first_name'] . ' ' . $row['last_name'];
                    $_SESSION['is_logged_in'] = 1;
                    $_SESSION['user_type'] = $row['user_type'];
                    $_SESSION['membership_type'] = $row['membership_type'];
                    $_SESSION['membership_status'] = $row['membership_status'];
                
                    logActivity($_SESSION['user_id'], $_SESSION['user_type'], 'Logged in');
                
                    // Update is_logged_in status and time
                    $updateStatus = "UPDATE users SET is_logged_in = 1, is_logged_in_time = NOW() WHERE user_id = ?";
                    $stmt = $conn->prepare($updateStatus);
                    $stmt->bind_param("s", $row['user_id']);
                    $stmt->execute();

                    // Handle "Remember Me" functionality
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        $updateToken = "UPDATE users SET remember_token = ? WHERE user_id = ?";
                        $stmt = $conn->prepare($updateToken);
                        $stmt->bind_param("ss", $token, $row['user_id']);
                        $stmt->execute();

                        // Set cookies
                        setcookie('remember_user', $row['email'], time() + (30 * 24 * 60 * 60), '/');
                        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');
                    }

                    switch ($row['user_type']) {
                        case 'Admin':
                            header('Location: admin/index.php');
                            break;
                        case 'Member':
                            header('Location: member/index.php');
                            break;
                        case 'Loan Officer':
                            header('Location: loan-officer/index.php');
                            break;
                        case 'Membership Officer':
                            header('Location: membership-officer/index.php');
                            break;
                        case 'Liaison Officer':
                            header('Location: liaison-officer/index.php');
                            break;
                        case 'Cashier':
                            header('Location: cashier/index.php');
                            break;
                        default:
                            $error[] = 'Invalid user type!';
                            break;
                    }
                    exit();
                }
            } else {
                // Failed attempt
                $_SESSION['login_attempts']++;
                
                if ($_SESSION['login_attempts'] >= 3) {
                    // Block for 5 minutes (300 seconds)
                    $_SESSION['login_blocked_until'] = time() + 300;
                    $error[] = "Too many failed attempts. Please try again in 5 minutes.";
                } else {
                    $remaining_attempts = 3 - $_SESSION['login_attempts'];
                    $error[] = "Incorrect email or password! ($remaining_attempts attempts remaining)";
                }
            }
        } else {
            // Failed attempt (email not found)
            $_SESSION['login_attempts']++;
            
            if ($_SESSION['login_attempts'] >= 3) {
                $_SESSION['login_blocked_until'] = time() + 300;
                $error[] = "Too many failed attempts. Please try again in 5 minutes.";
            } else {
                $remaining_attempts = 3 - $_SESSION['login_attempts'];
                $error[] = "Incorrect email or password! ($remaining_attempts attempts remaining)";
            }
        }
    }
}

// Check for "Remember Me" cookie
if (!isset($_POST['submit']) && isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_token'])) {
    $email = mysqli_real_escape_string($conn, $_COOKIE['remember_user']);
    $token = mysqli_real_escape_string($conn, $_COOKIE['remember_token']);

    $select = "SELECT * FROM users WHERE email = ? AND remember_token = ?";
    $stmt = $conn->prepare($select);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Check membership status for Member user type in Remember Me
        if ($row['user_type'] == 'Member' && $row['membership_status'] == 'Pending') {
            // Clear remember me cookies if membership is pending
            setcookie('remember_user', '', time() - 3600, '/');
            setcookie('remember_token', '', time() - 3600, '/');
            $error[] = 'Your membership is still pending approval. Please wait for activation.';
        } else {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['uploadID'] = $row['uploadID'];
            $_SESSION['full_name'] = $row['first_name'] . ' ' . $row['last_name'];
            $_SESSION['is_logged_in'] = 1;
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['membership_type'] = $row['membership_type'];
            $_SESSION['membership_status'] = $row['membership_status'];

            logActivity($_SESSION['user_id'], $_SESSION['user_type'], 'Logged in via Remember Me');
        }
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
    <style>
        :root {
            --primary-color: #00FFAF;
            --secondary-color: #0F4332;
            --dark-color: #1f1f1f;
            --accent-color: #00FFAF;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: url('dist/assets/images/paschal.png');
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
        

        .login-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
            margin: 2rem auto;
        }

        .logo {
            width: 120px;
            height: auto;
            display: block;
            margin: 0 auto 1.5rem;
        }

        .title {
            text-align: center;
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #e4e6e8;
            padding: 0.8rem;
            margin-bottom: 1rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(0,148,94,0.2);
            border-color: var(--accent-color);
        }

        .btn-primary {
            background-color: var(--secondary-color) !important;
            border: none;
            padding: 0.8rem;
            width: 100%;
            font-weight: 500;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            background-color: var(--accent-color) !important;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            font-size: 0.9rem;
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            color: #666;
        }

        .create-account {
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .create-account a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .request-credentials {
            text-align: center;
            font-size: 0.9rem;
        }

        .request-credentials a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        @media (max-width: 576px) {
            .login-container {
                padding: 1.5rem;
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
    
    <div class="login-container">
        <img src="dist/assets/images/pmpc-logo.png" alt="Cooperative Logo" class="logo">
        <h1 class="title">PASCHAL COOPERATIVE</h1>
        <p class="subtitle">Welcome Back!</p>
        <?php
        if(isset($error)){
            foreach($error as $error){
                echo '<div class="alert alert-danger">'.$error;
                if (isset($_SESSION['login_blocked_until'])) {
                    echo '<div id="login-timer" data-end-time="'.$_SESSION['login_blocked_until'].'"></div>';
                }
                echo '</div>';
            }
        }
        ?>
       <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                    <span class="input-group-text toggle-password" style="cursor: pointer;  pointer; height:51px;">
                        <i class="fas fa-eye" style="color:black;"></i>
                    </span>
                </div>
            </div>
            
            <div class="remember-forgot">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <a href="forgot-pass.php" class="text-muted">Forgot Password?</a>
            </div>
            
            <button type="submit" name="submit" class="btn btn-primary">Sign-In</button>
        </form>
        
        <div class="create-account mt-4">
            <span>Not a Member? </span>
            <a href="signup.php">Create Account</a>
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

        // Password visibility toggle
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const passwordInput = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Countdown timer for locked accounts
        function updateTimer() {
            const timerElement = document.getElementById('login-timer');
            if (timerElement) {
                const endTime = parseInt(timerElement.dataset.endTime);
                const now = Math.floor(Date.now() / 1000);
                const remaining = endTime - now;

                if (remaining > 0) {
                    const minutes = Math.floor(remaining / 60);
                    const seconds = remaining % 60;
                    timerElement.textContent = `Time remaining: ${minutes}:${seconds.toString().padStart(2, '0')}`;
                    setTimeout(updateTimer, 1000);
                } else {
                    timerElement.textContent = "You may now try again";
                    setTimeout(() => window.location.reload(), 1500);
                }
            }
        }
        updateTimer();
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
    </script>
</body>
</html>