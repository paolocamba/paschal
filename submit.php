<?php
include 'connection/config.php';

// At the top of your PHP file, define the reCAPTCHA keys
define('RECAPTCHA_SITE_KEY', '6LdFs5UqAAAAAMZcRWyjNtzVBvl7z2p32ipVxU8M');  // Your site key
define('RECAPTCHA_SECRET_KEY', '6LdFs5UqAAAAAF5LEvASDsjdm-v-BGf07lqPKs8w');  // Replace with your secret key

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the reCAPTCHA response
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
    
    // Verify reCAPTCHA
    $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $verify_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $recaptcha_response
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $result = json_decode($response);
    curl_close($ch);
    
    // Debug information
    error_log('reCAPTCHA Response: ' . print_r($response, true));
    
    if ($result && $result->success) {
        // reCAPTCHA verification successful
        $userID = isset($_POST['member-id']) ? intval($_POST['member-id']) : 0;
        if ($userID) {
            header("Location: success.php?member-id=" . $userID);
            exit();
        } else {
            die("Error: Member ID is missing.");
        }
    } else {
        // Debug: Log verification failure details
        error_log('reCAPTCHA Verification Failed. Error codes: ' . 
                 (isset($result->{'error-codes'}) ? json_encode($result->{'error-codes'}) : 'none'));
        die("Error: reCAPTCHA verification failed. Please try again.");
    }
}

$userID = isset($_GET['member-id']) ? intval($_GET['member-id']) : 0;
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

        .submit-container {
      max-width: 700px;
      margin: auto;

      flex-grow: 1;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .card-header {
      background-color: #4CAF50;
      color: white;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    .btn-previous,
    .btn-next {
      width: 100%;
      max-width: 250px;
      padding: 10px !important;
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

    .g-recaptcha{
        display: flex;
        justify-content: center;
    }
    p{
        text-align:center;
    }
  

    @media (max-width: 767.98px) {
      .submit-container {
        margin: 20px;
      }

      .btn-previous,
      .btn-next {
        max-width: 100%;
      }

      .progress-steps {
        flex-wrap: wrap;
      }

      .step {
        margin: 10px;
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
    <br> <br> <br> <br> <br> <br> <br><br>
    <div class="submit-container">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-center">SUBMIT MEMBERSHIP APPLICATION</h4>
            </div>
            <div class="card-body">
                <form id="membershipForm" method="post" action="">
                    <input type="hidden" name="member-id" value="<?php echo htmlspecialchars($userID); ?>">
                    
                    <p>Please verify you are human *</p>
                    <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                    
                    <div class="progress-steps mb-4">
                        <div class="step-line"></div>
                        <div class="step">1</div>
                        <div class="step">2</div>
                        <div class="step">3</div>
                        <div class="step active">4</div>
                    </div>

                    <div class="d-flex justify-content-center gap-4 mt-5">
                        <a href="member-doa.php?member-id=<?php echo htmlspecialchars($userID); ?>" 
                           class="btn btn-previous px-5 py-2" 
                           style="background-color: #E9ECEF; border: none; border-radius: 5px; color: #666; width: 160px; text-decoration:none;">Previous</a>
                        <button type="submit" 
                                class="btn btn-next px-5 py-2" 
                                style="background-color: #0F4332; border: none; border-radius: 5px; color: white; width: 160px;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
    <br> <br> <br> <br> <br> <br> <br><br> <br> <br> <br> <br> <br> <br><br> <br> <br> <br> <br> 
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
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

    document.getElementById('membershipForm').addEventListener('submit', function(event) {
        var response = grecaptcha.getResponse();
        
        if (response.length === 0) {
            event.preventDefault();
            alert('Please complete the reCAPTCHA verification');
            return false;
        }
    });
</script>
<!--

    const express = require('express');
const axios = require('axios');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.json());

app.post('/verify-recaptcha', async (req, res) => {
    const { recaptchaToken } = req.body;
    
    try {
        const googleVerifyUrl = `https://www.google.com/recaptcha/api/siteverify?secret=6LdipZUqAAAAABr5pHsrKwOZ3evPL1Uj96K9F50x&response=${recaptchaToken}`;
        
        const response = await axios.post(googleVerifyUrl);
        const { success } = response.data;
        
        res.json({ success });
    } catch (error) {
        console.error('reCAPTCHA verification error:', error);
        res.status(500).json({ success: false });
    }
});

app.listen(3000, () => {
    console.log('Server running on http://localhost:3000');
});

document.getElementById('membershipForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        var response = grecaptcha.getResponse();
        
        if (response.length === 0) {
            alert('Please complete the reCAPTCHA');
            return;
        }

        // For localhost testing, you'll want to verify the token server-side
        fetch('/verify-recaptcha', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                recaptchaToken: response
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Form submitted successfully!');
                // Proceed with form submission
            } else {
                alert('reCAPTCHA verification failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });-->
</body>
</html>