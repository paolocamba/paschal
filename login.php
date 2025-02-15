<?php
// Check if the "success" parameter is set in the URL
if (isset($_GET['success']) && $_GET['success'] == 1) {
    // Use SweetAlert to show a success message
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "success",
                title: "You Successfully Update Your Password",
                showConfirmButton: false,
                timer: 1500 // Close after 1.5 seconds
            });
        });
    </script>';
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
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
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
            background: rgba(255, 255, 255, 0.1);
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
            border-color: rgba(255, 255, 255, 0.1);
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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

        .welcome-text {
            text-align: center;
            color: var(--secondary-color);
            margin-bottom: 2rem;
        }

        .login-text {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.2rem;
        }

        .login-btn {
            display: block;
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: transform 0.2s;
        }

        .member-btn {
            background: var(--secondary-color);
            color: white;
        }

        .staff-btn {
            background: var(--secondary-color);
            color: white;
            border: 2px solid var(--secondary-color);
        }

        .login-btn:hover {
            transform: translateY(-2px);
        }

        .divider {
            text-align: center;
            margin: 1rem 0;
            color: #666;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #ddd;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        @media (max-width: 576px) {
            .login-container {
                margin: 1rem auto;
                padding: 1.5rem;
            }

            .logo {
                width: 100px;
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

    <div class="container">
        <div class="login-container">
            <img src="dist/assets/images/pmpc-logo.png" alt="PASCHAL Logo" class="logo">
            <h2 class="welcome-text">PASCHAL COOPERATIVE</h2>
            <p class="login-text">Welcome Back!</p>

            <h3 class="login-text">Login as</h3>

            <button class="login-btn member-btn">MEMBER</button>
            <div class="divider">or</div>
            <button class="login-btn staff-btn">STAFF</button>
        </div>
    </div>


    <script>
        document.querySelector('.member-btn').addEventListener('click', () => {
            window.location.href = 'member-login.php';
        });

        document.querySelector('.staff-btn').addEventListener('click', () => {
            window.location.href = 'staff-login.php';
        });
    </script>

    <br> <br> <br> <br> <br> <br> <br>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="footer-title">PASCHAL MULTIPURPOSE COOPERATIVE</h3>
                    <p class="footer-description">Corner Acacia St., Bunsuran 1st, Pandi, Bul.(Main Office)</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/paschalcoop" aria-label="Facebook"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="mailto:paschal_mpc@yahoo.com" aria-label="Email"><i
                                class="fa-solid fa-envelope"></i></a>

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
    <!-- Add SweetAlert script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
        document.addEventListener('DOMContentLoaded', function () {
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
                anchor.addEventListener('click', function (e) {
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
        window.addEventListener('scroll', function () {
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
        document.addEventListener('DOMContentLoaded', function () {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');

            if (navbarToggler && navbarCollapse) {
                navbarToggler.addEventListener('click', function () {
                    navbarCollapse.classList.toggle('show');
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function (event) {
                    if (!navbarCollapse.contains(event.target) && !navbarToggler.contains(event.target)) {
                        navbarCollapse.classList.remove('show');
                    }
                });

                // Close mobile menu when clicking on a link
                const mobileNavLinks = navbarCollapse.querySelectorAll('.nav-link');
                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', function () {
                        navbarCollapse.classList.remove('show');
                    });
                });
            }
        });

        // Optional: Add scroll to top functionality
        window.addEventListener('scroll', function () {
            const scrollToTop = document.querySelector('.scroll-to-top');
            if (scrollToTop) {
                if (window.pageYOffset > 100) {
                    scrollToTop.style.display = 'block';
                } else {
                    scrollToTop.style.display = 'none';
                }
            }
        });

        const associateMembershipDetails = document.getElementById('associate-membership-details');
        const regularMembershipDetails = document.getElementById('regular-membership-details');
        const membershipTypeSelect = document.getElementById('membership-type');

        membershipTypeSelect.addEventListener('change', () => {
            const selectedType = membershipTypeSelect.value;
            if (selectedType === 'associate') {
                associateMembershipDetails.style.display = 'block';
                regularMembershipDetails.style.display = 'none';
            } else {
                associateMembershipDetails.style.display = 'none';
                regularMembershipDetails.style.display = 'block';
            }
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