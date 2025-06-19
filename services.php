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
            line-height: 1.6;
            color: #333;
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

        /* Services Section Styles */
        .services-section {
            background: var(--gradient-bg);
            padding: 4rem 0;
        }

        .services-section h1 {
            text-align: center;
            color: black;
            margin-bottom: 2rem;
        }

        .nav-tabs {
            border-bottom: none; /* Remove the gray border */
            justify-content: center;
            margin-bottom: 2rem;
            background-color: transparent; /* Ensure the background color is transparent */
        }

        .nav-tabs .nav-links {
            color: black;
            font-weight: 600;
            border: none;
            margin: 0 15px;
            padding: 15px;
            transition: all 0.3s ease;
            background-color: transparent; /* Ensure the background is transparent */
        }

        .nav-tabs .nav-links.active {
            background-color: transparent;
            border-bottom: 3px solid var(--secondary-color); /* Active tab has a blue underline */
            color: var(--secondary-color);
        }

        .tab-content {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 2rem;
        }

        /* Loan Section Styles */
        .loan-section {
            background: var(--gradient-bg);
            padding: 4rem 0;
        }

        .loan-options {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            padding: 2rem;
            overflow: hidden;
        }

        .calculator {
            background-color: var(--primary-color);
            border-radius: 10px;
            padding: 2rem;
        }

        .calculator input,
        .calculator select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .calculator .btn-primary {
            background-color:var(--secondary-color);
            border: none;
            transition: all 0.3s ease;
        }

        .calculator .btn-primary:hover {
            background-color: #1557b0;
        }

        /* Medical Services Section */
        .medical-section {
            background: var(--gradient-bg);
            padding: 4rem 0;
        }

        .medical-section h2 {
            text-align: center;
            color: black;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .medical-section > p {
            text-align: center;
            color: #6c757d;
            max-width: 800px;
            margin: 0 auto 2.5rem;
            line-height: 1.6;
        }

        .medical-services {
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .medical-services .service {
            text-align: center;
            background: white;
            border-radius: 15px;
            padding: 2rem;
            width: calc(25% - 2rem);
            max-width: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .medical-services .service:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .medical-services .service img {
            max-width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .medical-services .service:hover img {
            transform: scale(1.1);
        }

        .medical-services .service p {
            color: black;
            font-weight: 600;
            margin-top: 1rem;
        }

        .avail-btn {
            display: block;
            width: 250px;
            margin: 2.5rem auto 0;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(26, 115, 232, 0.2);
        }

        .avail-btn:hover {
            background-color: #1557b0;
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(26, 115, 232, 0.3);
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

        /* Responsive Adjustments */
        @media (max-width: 991px) {
            .nav-links {
                margin: 5px 0;
            }

            .nav-tabs .nav-links {
                margin: 0 5px;
                padding: 10px;
                font-size: 0.9rem;
            }

            .loan-options {
                flex-direction: column;
            }

            .left-loan, .right-loan {
                width: 100%;
                margin-bottom: 1rem;
            }

            .medical-services {
                gap: 1.5rem;
            }

            .medical-services .service {
                width: calc(50% - 1.5rem);
                max-width: 300px;
            }
        }

        @media (max-width: 576px) {
            .services-section, .loan-section, .medical-section {
                padding: 2rem 0;
            }

            .nav-tabs .nav-links {
                margin: 0 2px;
                padding: 8px;
                font-size: 0.8rem;
            }
          
            
            .apply-loan {
                margin-top: 10px;
                background-color: white !important;
                color: black !important;  /* Using the primary color variable for text */
                text-align:center;
            }

            .tab-content, .loan-options, .calculator {
                padding: 1rem;
            }

            .medical-section h2 {
                font-size: 1.8rem;
            }

            .medical-section > p {
                font-size: 0.9rem;
                padding: 0 15px;
            }

            .medical-services {
                gap: 1rem;
            }

            .medical-services .service {
                width: 100%;
                max-width: none;
                margin-bottom: 1rem;
            }

            .medical-services .service img {
                max-width: 80px;
                height: 80px;
            }

            .avail-btn {
                width: 80%;
                font-size: 0.9rem;
                padding: 0.6rem 1.2rem;
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

    <!-- Services Section with Tab Panes -->
    <section class="services-section container">
        <h1 class="text-center mb-4">Our Services</h1>
        
        <ul class="nav nav-tabs" id="servicesTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-links active" id="savings-tab" data-bs-toggle="tab" data-bs-target="#savings" type="button" role="tab">Savings</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-links" id="space-tab" data-bs-toggle="tab" data-bs-target="#space" type="button" role="tab">Space for Rent</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-links" id="insurance-tab" data-bs-toggle="tab" data-bs-target="#insurance" type="button" role="tab">Life Insurance</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-links" id="rice-tab" data-bs-toggle="tab" data-bs-target="#rice" type="button" role="tab">Rice</button>
            </li>
        </ul>

        <div class="tab-content" id="servicesTabContent">
            <div class="tab-pane fade show active" id="savings" role="tabpanel">
                <h3>Savings</h3>
                <p>Paschal Cooperative offers a variety of savings options designed to help members grow their wealth securely. Whether you're saving for a short-term goal or long-term financial stability, our savings plans provide competitive interest rates and flexible terms to meet your needs.</p>
            </div>
            <div class="tab-pane fade" id="space" role="tabpanel">
                <h3>Space for Rent</h3>
                <p>Looking for a place to conduct business or host events? Paschal Cooperative offers versatile Space for Rent options to accommodate your needs.</p>
            </div>
            <div class="tab-pane fade" id="insurance" role="tabpanel">
                <h3>Life Insurance</h3>
                <p>Protect your family's future with our comprehensive Life Insurance plans. We offer various options to fit your needs and budget.</p>
            </div>
            <div class="tab-pane fade" id="rice" role="tabpanel">
                <h3>Rice</h3>
                <p>Our Rice Program ensures that members have access to affordable, high-quality rice.</p>
            </div>
        </div>
    </section>

    <!-- Loan Section -->
    <section class="loan-section container">
        
        <div class="loan-options d-flex flex-wrap">
        
            <div class="left-loan flex-grow-1 p-3">
            <h2 class="text-center mb-4">Types of Loan</h2>
            <h3>Regular Loan</h3>
                <p>This loan option allows members to borrow against their share capital and savings deposits, providing a convenient way to access funds.</p>

                <h3>Collateral REM</h3>
                <p>Our Collateral Real Estate Mortgage (REM) Loan allows members to borrow larger amounts using their land title as collateral.</p>
            </div>

            <div class="right-loan flex-grow-1 p-3">
                <div class="calculator">
                    <h3 class="mb-3">Amortization Calculator</h3>
                    <form oninput="calculateInstallment()">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Loan Amount</label>
                            <input type="number" id="amount" class="form-control" placeholder="Enter amount" required>
                        </div>

                        <div class="mb-3">
                            <label for="loan-type" class="form-label">Type of Loan</label>
                            <select id="loan-type" class="form-select" onchange="setInterestRate()">
                                <option value="Regular">Regular</option>
                                <option value="Collateral REM">Collateral REM</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="rate" class="form-label">Interest Rate per Annum</label>
                            <input type="text" id="rate" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="terms" class="form-label">Terms of Payment</label>
                            <select id="terms" class="form-select" onchange="calculateInstallment()">
                                <option value="3">3 months</option>
                                <option value="6">6 months</option>
                                <option value="9">9 months</option>
                                <option value="12">12 months</option>
                                <option value="15">15 months</option>
                                <option value="18">18 months</option>
                                <option value="21">21 months</option>
                                <option value="24">24 months</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="installment" class="form-label">Installment Amount</label>
                            <input type="text" id="installment" class="form-control" placeholder="Installment Amount" readonly>
                        </div>

                        <button type="button" class="btn btn-primary w-100" onclick="window.location.href='signup.php'">Apply for Loan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Medical Services Section -->
    <section class="medical-section container">
        <h2>Medical Services</h2>
        <p class="text-center mb-4">Paschal Cooperative is committed to the health and well-being of its members. We offer a variety of medical services to ensure you receive the care you need:</p>

        <div class="medical-services">
            <div class="service">
                <img src="dist/assets/images/laboratory.png" alt="Laboratory Services">
                <p>Laboratory Services</p>
            </div>
            <div class="service">
                <img src="dist/assets/images/health-card.png" alt="Health Card">
                <p>Health Card</p>
            </div>
            <div class="service">
                <img src="dist/assets/images/medical-consultation.png" alt="Medical Consultation">
                <p>Medical Consultation</p>
            </div>
            <div class="service">
                <img src="dist/assets/images/x-ray.png" alt="X-RAY Services">
                <p>X-RAY Services</p>
            </div>
            <div class="service">
                <img src="dist/assets/images/hilot.png" alt="Hilot Healom">
                <p>Hilot Healom</p>
            </div>
        </div>

        <button class="avail-btn" onclick="window.location.href='login.php'">Avail Our Services</button>
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
    function calculateInstallment() {
      const loanAmount = parseFloat(document.getElementById('amount').value);
      const loanType = document.getElementById('loan-type').value;
      const terms = parseInt(document.getElementById('terms').value);

      let interestRate = loanType === 'Regular' ? 12 : 14; // Set interest based on loan type
      const monthlyRate = (interestRate / 100) / 12;

      if (loanAmount && terms) {
        const installment = (loanAmount * monthlyRate) / (1 - Math.pow(1 + monthlyRate, -terms));
        document.getElementById('installment').value = installment.toFixed(2);
      }
    }

    function setInterestRate() {
      const loanType = document.getElementById('loan-type').value;
      document.getElementById('rate').value = loanType === 'Regular' ? '12%' : '14%';
      calculateInstallment();
    }
    // Ensure that the DOM is fully loaded before executing the script
document.addEventListener('DOMContentLoaded', function () {
    // Select all the nav links in the tab
    const navLinks = document.querySelectorAll('.nav-links');
    
    // Add event listeners for each tab button
    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            // Remove active class from all tabs
            navLinks.forEach(tab => tab.classList.remove('active'));
            
            // Add active class to the clicked tab
            link.classList.add('active');
        });
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