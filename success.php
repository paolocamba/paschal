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
      height: 50px;
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

    .success-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: calc(100vh - 196px);
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: #00b894;
      color: white;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    .card-body {
      padding: 30px;
    }

    @media (max-width: 767.98px) {
      .navbar-brand img {
        height: 40px;
      }

      .nav-link {
        margin: 0 10px;
      }

      .apply-loan {
        padding: 10px 20px;
      }

      .footer-title {
        font-size: 1.6rem;
      }

      .footer-description {
        font-size: 1rem;
      }

      .social-icons a {
        width: 35px;
        height: 35px;
        font-size: 1rem;
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

  <div class="success-container">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-center align-items-center">
          <img src="dist/assets/images/pmpc-logo.png" alt="PASCHAL Logo" class="me-2" style="height: 50px;">
          <h4 class="mb-0">Membership Application Submitted Successfully!</h4>
        </div>
      </div>
      <div class="card-body">
        <p>Please settle the payment for Share Capital on the Appointment Date.</p>
        <div class="d-grid gap-2">
          <a href="index.php" class="btn btn-primary" style="background: var(--secondary-color);">Home</a>
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
  </script>
</body>
</html>