<?php
session_start();

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
            --primary-color:  #00FFAF;
            --secondary-color: #0F4332;
            --dark-color: #1f1f1f;
        
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
            transition: box-shadow 0.3s ease;
        }
        .navbar.scrolled {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
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

        /* Welcome Section */
        .welcome-section {
            background: url('dist/assets/images/paschal.png') center/cover;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
            margin-bottom: 80px;
        }

        /* Dark overlay */
        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6); /* Dark overlay with 60% opacity */
            backdrop-filter: blur(3px); /* Slight blur effect */
            z-index: 1;
        }

        /* Ensure content appears above the overlay */
        .welcome-section .container {
            position: relative;
            z-index: 2;
        }

        .welcome-text {
            padding-right: 50px;
        }

        .welcome-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .welcome-text h1 span {
            color: var(--primary-color);
            position: relative;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .welcome-text h2 {
            font-size: 2rem;
            color: #e0f7ff;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .welcome-text p {
            font-size: 1.25rem;
            color: #ffffff;
            margin-bottom: 2rem;
            font-style: italic;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            opacity: 0.9;
        }

        /* Services button styling */
        .services-btn {
            position: relative;
            z-index: 2;
            background: linear-gradient(135deg, #00d4ff, #0056b3);
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .services-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: #17B169 !important;
        }


        .services-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(26,115,232,0.2);
        }

        .services-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26,115,232,0.3);
        }

        .coop-image img {
            width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .coop-image img:hover {
            transform: scale(1.02);
        }

        /* Enhanced Events Section */
        .events-section {
            padding: 80px 0;
            background: linear-gradient(to bottom, #f8f9fa, #ffffff);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary-color);
        }

        .event-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }

        .event-card img {
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .event-card:hover img {
            transform: scale(1.1);
        }

        .success-section {
            background: linear-gradient(135deg, #00FFAF, #0F4332);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
            }
            .success-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
        }
        .success-section .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #ffffff;
        }
        .success-section .lead {
            color: #e0e0e0;
            font-size: 1.1rem;
            margin-bottom: 4rem;
        }
        .success-section .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }
        .success-section .stat-label {
            font-size: 1.1rem;
            color: #e0e0e0;
            font-weight: 500;
        }

        /* Enhanced Map Section */
        .map-section {
            height: 500px;
            position: relative;
        }

        .map-section iframe {
            filter: grayscale(1);
            transition: filter 0.3s;
        }

        .map-section iframe:hover {
            filter: grayscale(0);
        }

        /* Enhanced Footer */
        .footer {
            background:linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
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

        @media (max-width: 768px) {
            .nav-link {
                margin: 5px 0;
                color: white !important;
            }
            
            .apply-loan {
                margin-top: 10px;
                background-color: white !important;
                color: var(--primary-color) !important;  /* Using the primary color variable for text */
                text-align:center;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .welcome-section {
                padding: 80px 0;
            }
            
            .welcome-text {
                padding-right: 0;
                text-align: center;
            }

            .welcome-text h1 {
                font-size: 2.5rem;
            }

            .welcome-text h2 {
                font-size: 1.5rem;
            }
        }
        .forgot-password-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .forgot-password-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            margin: 2rem auto;
        }

        .logo {
            display: block;
            margin: 0 auto 1rem;
            max-width: 150px;
            height: auto;
        }

        .title {
            color: var(--secondary-color);
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.8rem 1.2rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
        }

        .btn-reset {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 10px;
            width: 100%;
            font-weight: 500;
            margin-top: 1rem;
            transition: opacity 0.3s;
        }

        .btn-reset:hover {
            opacity: 0.9;
            color: white;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 1rem;
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

    <div class="forgot-password-container">
        <div class="container">
            <div class="forgot-password-card">
                <img src="dist/assets/images/pmpc-logo.png" alt="Cooperative Logo" class="logo">
                <h1 class="title">PASCHAL COOPERATIVE</h1>
                <h2 class="text-center mb-4">Forgot Password</h2>
                
                <?php if(isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show">
                        <?php 
                            echo $_SESSION['message']; 
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="process_forgot_password.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required 
                               placeholder="Enter your registered email">
                    </div>
                    <button type="submit" class="btn btn-reset">Reset Password</button>
                </form>
                <div class="text-center mt-3">
                    <a href="login.php" class="text-decoration-none">Back to Login</a>
                </div>
            </div>
        </div>
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
    <script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false,
        offset: 100
    });

    // Smooth scrolling for anchor links
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
    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= sectionTop - sectionHeight / 3) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').includes(current)) {
                link.classList.add('active');
            }
        });
    });

    // Navbar background change on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            navbar.style.boxShadow = '0 2px 15px rgba(0,0,0,0.1)';
        } else {
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            navbar.style.boxShadow = 'none';
        }
    });

    // Add loading="lazy" to images for better performance
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('img:not([loading])');
        images.forEach(img => {
            img.setAttribute('loading', 'lazy');
        });
    });
  
</script>
</body>
</html>