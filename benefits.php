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
        }

        .apply-loan:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 115, 232, 0.3);
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
            color: white !important;
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
            color: white;
            font-size: 0.9rem;
        }

         /* Enhanced Benefits Section */
         #benefits {
            background: linear-gradient(135deg, #f9f9f9, #f0f0f0);
            padding: 6rem 0;
        }

        .benefits-header {
            margin-bottom: 4rem;
            text-align: center;
        }

        .benefits-header h2 {
            color: var(black);
            font-weight: 700;
            position: relative;
            display: inline-block;
        }

        .benefits-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50%;
            height: 4px;
            background: var(black);
            margin-left: 25%;
        }

        .benefit-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .benefit-card .icon {
            position: absolute;
            top: 20px;
            right: 20px;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .benefit-card:hover .icon {
            opacity: 1;
        }

        .benefit-card .card-body {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .benefit-card .card-title {
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(black);
        }

        .additional-perks {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-top: 3rem;
        }

        .additional-perks h3 {
            color: var(black);
            margin-bottom: 1.5rem;
        }

        .perk-list {
            list-style-type: none;
            padding: 0;
        }

        .perk-list li {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .perk-list li i {
            margin-right: 10px;
            color: var(black);
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
                        <a class="nav-link apply-loan" href="login.php">Apply for Loan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    
     <!-- Benefits Section -->
     <section id="benefits">
        <div class="container">
            <!-- Section Header -->
            <div class="benefits-header" data-aos="fade-up">
                <h2 class="display-5">Why Join Us?</h2>
                <p class="lead text-muted mt-3">Explore the exclusive benefits we offer to our members.</p>
            </div>

            <!-- Benefits Grid -->
            <div class="row g-4">
                <!-- Benefit Card 1: Life Insurance -->
                <div class="col-lg-4 col-md-6" data-aos="fade-right">
                    <div class="card benefit-card h-100">
                        <div class="card-body">
                            <div class="icon text-primary">
                                <i class="fas fa-user-shield fa-2x"></i>
                            </div>
                            <h5 class="card-title">Life Insurance Protection</h5>
                            <p class="card-text">
                                Comprehensive life insurance coverage:
                                <ul class="list-unstyled">
                                    <li>• Age 18-64: Up to P50,000 (P350 premium)</li>
                                    <li>• Age 65-74: Up to P50,000 (P450 premium)</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit Card 2: Medical Discounts -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="card benefit-card h-100">
                        <div class="card-body">
                            <div class="icon text-success">
                                <i class="fas fa-medkit fa-2x"></i>
                            </div>
                            <h5 class="card-title">Medical Discounts</h5>
                            <p class="card-text">
                                Exclusive member benefits:
                                <ul class="list-unstyled">
                                    <li>• 20% discount on medical services</li>
                                    <li>• Access to partner healthcare providers</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit Card 3: Dividends -->
                <div class="col-lg-4 col-md-6" data-aos="fade-left">
                    <div class="card benefit-card h-100">
                        <div class="card-body">
                            <div class="icon text-info">
                                <i class="fas fa-piggy-bank fa-2x"></i>
                            </div>
                            <h5 class="card-title">Dividends and Patronage</h5>
                            <p class="card-text">
                                Financial growth opportunities:
                                <ul class="list-unstyled">
                                    <li>• Dividends for shareholders</li>
                                    <li>• Patronage refunds for active members</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit Card 4: Flexible Loans -->
                <div class="col-lg-4 col-md-6" data-aos="fade-right">
                    <div class="card benefit-card h-100">
                        <div class="card-body">
                            <div class="icon text-warning">
                                <i class="fas fa-hand-holding-usd fa-2x"></i>
                            </div>
                            <h5 class="card-title">Flexible Loan Terms</h5>
                            <p class="card-text">
                                Affordable financing options:
                                <ul class="list-unstyled">
                                    <li>• Low 12% annual interest rate</li>
                                    <li>• Terms: 1 month to 5 years</li>
                                    <li>• Various collateral options</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit Card 5: Free Seminars -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="card benefit-card h-100">
                        <div class="card-body">
                            <div class="icon text-info">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </div>
                            <h5 class="card-title">Free Seminars</h5>
                            <p class="card-text">
                                Professional development:
                                <ul class="list-unstyled">
                                    <li>• Financial coaching</li>
                                    <li>• Livelihood programs</li>
                                    <li>• Skills enhancement workshops</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit Card 6: Scholarships -->
                <div class="col-lg-4 col-md-6" data-aos="fade-left">
                    <div class="card benefit-card h-100">
                        <div class="card-body">
                            <div class="icon text-success">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <h5 class="card-title">Scholarships & Benefits</h5>
                            <p class="card-text">
                                Support for members:
                                <ul class="list-unstyled">
                                    <li>• Educational scholarships</li>
                                    <li>• Senior member benefits</li>
                                    <li>• Mutual aid programs</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Perks Card -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="additional-perks" data-aos="fade-up">
                        <h3 class="text-center">Membership Details</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="perk-list">
                                    <li><i class="fas fa-check-circle"></i>Membership Fee: P100.00</li>
                                    <li><i class="fas fa-check-circle"></i>Initial Savings Deposit: P1,000.00</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="perk-list">
                                    <li><i class="fas fa-check-circle"></i>Share Capital: P5,000.00</li>
                                    <li><i class="fas fa-check-circle"></i>Total Initial Investment: P6,100.00</li>
                                </ul>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <p class="text-muted">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Share Capital can be paid in installments if full payment is not possible.
                                </p>
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
                        <li><a href="login.php" class="text-white text-decoration-none">Apply for Loan</a></li>
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
</script>
</body>
</html>