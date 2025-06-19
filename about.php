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
            transitiblack
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

        /* Mission and Vision Sections */
        #about-mission-vision .mission-section,
        #about-mission-vision .vision-section {
            min-height: 100vh;
            position: relative;
        }

        #about-mission-vision .mission-image-container,
        #about-mission-vision .vision-image-container {
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        #about-mission-vision .mission-image,
        #about-mission-vision .vision-image {
            width: 50%;
            height: 50%;
            border-radius: 40px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        #about-mission-vision .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            mix-blend-mode: multiply;
        }

        #about-mission-vision .mission-section:hover .mission-image,
        #about-mission-vision .vision-section:hover .vision-image {
            transform: scale(1.05);
        }

        #about-mission-vision .mission-highlights,
        #about-mission-vision .vision-highlights {
            background: rgba(255,255,255,0.8);
            padding: 20px;
            border-radius: 10px;
        }

        #about-mission-vision .mission-highlight,
        #about-mission-vision .vision-highlight {
            transition: transform 0.3s ease;
        }

        #about-mission-vision .mission-highlight:hover,
        #about-mission-vision .vision-highlight:hover {
            transform: translateX(10px);
        }

        /* Foundation Section */
        #foundation .foundation-card {
            border: none;
            transition: all 0.3s ease;
        }

        #foundation .foundation-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }

        #foundation .founders-list ul li {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 10px;
            transition: all 0.3s ease;
        }

        #foundation .founders-list ul li:hover {
            transform: translateX(10px);
            color: var(black);
        }

        @media (max-width: 991px) {
            #about-mission-vision .mission-section,
            #about-mission-vision .vision-section {
                min-height: auto;
            }
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
    
        <!-- About Us Section -->
        <section id="about-mission-vision" class="container-fluid px-0">
        <div class="mission-section row g-0 align-items-center">
            <div class="col-lg-6 order-lg-1 order-2" data-aos="fade-right">
                <div class="mission-content p-5">
                    <h2 class="display-5 text-primary mb-4">Our Mission</h2>
                    <p class="lead text-dark">
                        To empower our members through sustainable financial services, 
                        community development, and mutual support, fostering economic 
                        growth and social progress for all our stakeholders.
                    </p>
                    <div class="mission-highlights mt-4">
                        <div class="mission-highlight d-flex align-items-center mb-3">
                            <i class="fas fa-chart-line text-primary me-3 fa-2x"></i>
                            <span>Sustainable Financial Solutions</span>
                        </div>
                        <div class="mission-highlight d-flex align-items-center mb-3">
                            <i class="fas fa-users text-primary me-3 fa-2x"></i>
                            <span>Community-Driven Development</span>
                        </div>
                        <div class="mission-highlight d-flex align-items-center">
                            <i class="fas fa-hand-holding-heart text-primary me-3 fa-2x"></i>
                            <span>Mutual Support and Growth</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 order-1" data-aos="fade-left">
                <div class="mission-image-container position-relative">
                    <img src="dist/assets/images/vision-mission.png" alt="PASCHAL Cooperative Mission" class="img-fluid mission-image">
                    <div class="image-overlay"></div>
                </div>
            </div>
        </div>

        <div class="vision-section row g-0 align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="vision-image-container position-relative">
                    <img src="dist/assets/images/vision-mission.png" alt="PASCHAL Cooperative Vision" class="img-fluid vision-image">
                    <div class="image-overlay"></div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="vision-content p-5">
                    <h2 class="display-5 text-primary mb-4">Our Vision</h2>
                    <p class="lead text-dark">
                        To be a leading, innovative multi-purpose cooperative that 
                        provides comprehensive financial solutions, promotes economic 
                        inclusivity, and transforms lives through collaborative growth 
                        and shared prosperity.
                    </p>
                    <div class="vision-highlights mt-4">
                        <div class="vision-highlight d-flex align-items-center mb-3">
                            <i class="fas fa-globe text-primary me-3 fa-2x"></i>
                            <span>Comprehensive Financial Solutions</span>
                        </div>
                        <div class="vision-highlight d-flex align-items-center mb-3">
                            <i class="fas fa-people-carry text-primary me-3 fa-2x"></i>
                            <span>Economic Inclusivity</span>
                        </div>
                        <div class="vision-highlight d-flex align-items-center">
                            <i class="fas fa-seedling text-primary me-3 fa-2x"></i>
                            <span>Collaborative Transformation</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Foundation Section-->
    <section id="foundation" class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="foundation-card bg-white shadow-lg rounded-4 overflow-hidden">
                        <div class="foundation-header text-center text-white p-4" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important">
                            <h2 class="display-6 mb-0">Pundasyon ng PASCHAL Coop</h2>
                        </div>
                        <div class="foundation-body p-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <h3 class="text-primary mb-3">Bakit Paschal Coop?</h3>
                                    <p>Hango sa Paschal Mystery dahil itinatag sa araw ng Linggo ng Pagkabuhay.</p>
                                </div>
                                <div class="col-md-4">
                                    <h3 class="text-primary mb-3">Kailan Naitatag?</h3>
                                    <p class="display-6 text-dark">April 7, 2007</p>
                                </div>
                                <div class="col-md-4">
                                    <h3 class="text-primary mb-3">Sino-sino ang Nagtatag?</h3>
                                    <p>Carmen Pascual-Sta. Maria, mga anak at manugang.</p>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="founders-list">
                                <h3 class="text-primary mb-4">Mga Piling Kasapi</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><strong>Rev. Fr. Jovy Sebastian</strong> - Parish Priest</li>
                                            <li class="mb-2"><strong>Henry/Rona Marquez</strong> - Public Servant, Businessman</li>
                                            <li class="mb-2"><strong>Dra. Maria Visaya</strong> - SM Clinic, Bunsuran 3rd</li>
                                            <li class="mb-2"><strong>Kap. Mario Obispo</strong> - Brgy. Capt-Bunsuran 1st</li>
                                            <li class="mb-2"><strong>Puring Mauricio</strong> - Retired Principal</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><strong>Dra. Rachel Oca</strong> - Practitioner</li>
                                            <li class="mb-2"><strong>Eloisa Salazar</strong> - Principal</li>
                                            <li class="mb-2"><strong>Mauro Capistrano</strong> - Businessman</li>
                                            <li class="mb-2"><strong>Janeth Baldeo</strong> - Store Owner</li>
                                            <li class="mb-2"><strong>Boyet Santos</strong> - Fruits & Vegetable Vendor</li>
                                            <li class="mb-2"><strong>Tressie dela Cruz</strong> - Gowns Entourage Rentals</li>
                                            <li class="mb-2"><strong>Resty & Ofel Marquez</strong> - Hog Raisers</li>
                                        </ul>
                                    </div>
                                </div>
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