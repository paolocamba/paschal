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
            text-align:center;
        }

        .apply-loan:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 115, 232, 0.3);
        }
    
       /* Enhanced Footer */
       .footer {
            background:linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            color: white !important;
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


        .membership-section {
            padding: 40px 0;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }

        .membership-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .membership-card:hover {
            transform: translateY(-3px);
        }

        .membership-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 20px;
            text-align: center;
            color: white;
        }

        .membership-header img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .membership-header h2 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .membership-header h3 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .membership-body {
            padding: 25px;
        }

        .membership-steps {
            margin: 20px 0;
        }

        .step-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 12px;
            font-size: 0.9rem;
        }

        .step-content h5 {
            font-size: 1rem;
            margin-bottom: 2px;
        }

        .step-content p {
            font-size: 0.9rem;
            margin-bottom: 0;
            color: #666;
        }

        .get-started-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
            margin-top: 15px;
        }

        .get-started-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
            color: black;
        }
        @media (max-width: 768px) {
           
            
           .apply-loan {
               margin-top: 10px;
               background-color: white !important;
               color: black !important;  /* Using the primary color variable for text */
               text-align:center;
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

    <section class="membership-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="membership-card">
                        <div class="membership-header">
                            <img src="dist/assets/images/pmpc-logo.png" alt="PASCHAL Logo" class="img-fluid">
                            <h2>PASCHAL</h2>
                            <h3>Multi-Purpose Cooperative</h3>
                            <p class="mb-0">MEMBERSHIP APPLICATION</p>
                        </div>
                        <div class="membership-body">
                            <h4 class="text-center mb-3">Join Our Growing Community</h4>
                            
                            <div class="membership-steps">
                                <div class="step-item">
                                    <div class="step-number">1</div>
                                    <div class="step-content">
                                        <h5>Complete Application Form</h5>
                                        <p>Fill out your personal information and membership details</p>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <div class="step-number">2</div>
                                    <div class="step-content">
                                        <h5>Submit Requirements</h5>
                                        <p>Provide necessary documents and initial share capital</p>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <div class="step-number">3</div>
                                    <div class="step-content">
                                        <h5>Attend Orientation</h5>
                                        <p>Learn about member benefits and cooperative principles</p>
                                    </div>
                                </div>
                            </div>

                            <button class="get-started-btn" onclick="window.location.href='sign-up-checklist.php'">
                                Get Started
                            </button>
                            
                            <div class="login-link">
                                Already have an account? <a href="login.php">Log in instead</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
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
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Active nav link handler
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('section');

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
    document.addEventListener('DOMContentLoaded', function() {
    // Get current page URL
    const currentLocation = location.pathname.split('/').pop();

    // Select all nav links
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        // Remove any pre-existing active classes
        link.classList.remove('active');

        // Check if link's href matches current page
        if (link.getAttribute('href') === currentLocation || (currentLocation === '' && link.getAttribute('href') === 'index.php')) {
            link.classList.add('active');
        }
    });
});
// Function to handle checklist redirection
function redirectToChecklist() {
    window.location.href = "sign-up-checklist.php";
}
</script>
</body>
</html>