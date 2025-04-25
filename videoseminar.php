<?php
include 'connection/config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $userID = $_POST['member-id'];
    $seminarCompleted = isset($_POST['seminar-completed']) ? 1 : 0;

    // Validate memberID
    if (empty($userID)) {
        die("Error: Member ID is missing.");
    }

    // Update WatchedVideoSeminar
    $stmt = $conn->prepare("UPDATE member_applications SET watchvideoseminar = ? WHERE user_id = ?");
    $stmt->bind_param('is', $seminarCompleted, $userID);
    
    if (!$stmt->execute()) {
        die("Error updating seminar status: " . $stmt->error);
    }

    // Check the status of the application
    $statusSql = "SELECT fillupform, watchvideoseminar, membershipfee FROM member_applications WHERE user_id = ?";
    $stmtStatus = $conn->prepare($statusSql);
    $stmtStatus->bind_param('s', $userID);
    $stmtStatus->execute();
    $result = $stmtStatus->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Determine status based on enum values in your schema
        $status = 'In Progress'; // Default status
        
        if ($row['fillupform'] && $row['watchvideoseminar'] && $row['membershipfee'] > 0) {
            $status = 'Approved';
        } elseif (!$row['fillupform'] || !$row['watchvideoseminar']) {
            $status = 'Failed';
        }

        // Update the status
        $updateStatusSql = "UPDATE member_applications SET status = ? WHERE user_id = ?";
        $stmtUpdateStatus = $conn->prepare($updateStatusSql);
        $stmtUpdateStatus->bind_param('ss', $status, $userID);
        
        if (!$stmtUpdateStatus->execute()) {
            die("Error updating status: " . $stmtUpdateStatus->error);
        }
    }

    header("Location: member-doa.php?member-id=" . $userID);
    exit();
}

$userID = isset($_GET['member-id']) ? $_GET['member-id'] : '';
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
            --primary-color:  #00FFAF;
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

        /* Video Seminar Section Styling */
        .video-seminar {
            padding: 4rem 2rem;
            background-color: #f8f9fa;
        }

        .video-seminar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .video-seminar .title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            color: var(--secondary-color);
        }

        .video-seminar video {
            width: 100%;
            max-width: 1000px;
            height: auto;
            display: block;
            margin: 0 auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        /* Progress Steps Styling */
        .video-seminar .progress-steps {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem 0;
            position: relative;
            width: 100%;
        }

        .video-seminar .step-line {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            transform: translateY(-50%);
            z-index: 0;
        }

        .video-seminar .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-weight: 600;
            margin: 0 2rem;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .video-seminar .step.active {
            background: var(--accent-color);
            color: white;
        }

        /* Button Styling */
        .video-seminar .buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

   /* Navigation Buttons */
        .btn-navs {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
        }
        /* Additional styles to ensure label is centered and aligned with checkbox */
        .seminar-completed {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 1rem;
        }

        .seminar-completed input[type="checkbox"] {
        margin-right: 10px;
        }

        .seminar-completed label {
        margin-bottom: 0;
        text-align: center;
        }

        @media (max-width: 768px) {
        .seminar-completed {
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }
}
            


        /* Responsive Adjustments */
        @media (max-width: 1200px) {
            .video-seminar .container {
                padding: 0 30px;
            }
        }

        @media (max-width: 992px) {
            .video-seminar {
                padding: 3rem 1rem;
            }

            .video-seminar .title {
                font-size: 2rem;
            }

            .video-seminar .step {
                width: 35px;
                height: 35px;
                margin: 0 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .video-seminar {
                padding: 2rem 0.5rem;
            }

            .video-seminar .title {
                font-size: 1.8rem;
            }

            .video-seminar .progress-steps {
                margin: 1.5rem 0;
            }

            .video-seminar .step {
                width: 30px;
                height: 30px;
                margin: 0 1rem;
                font-size: 0.9rem;
            }

            .video-seminar .buttons {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }

            .video-seminar .btn-previous,
            .video-seminar .btn-next {
                width: 100%;
                max-width: 250px;
            }
        }

        @media (max-width: 480px) {
            .video-seminar {
                padding: 1.5rem 0.25rem;
            }

            .video-seminar .title {
                font-size: 1.5rem;
            }

            .video-seminar .step {
                width: 25px;
                height: 25px;
                margin: 0 0.5rem;
                font-size: 0.8rem;
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
                        <a class="nav-link apply-loan" href="apply-loan.php">Apply for Loan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <section class="video-seminar">
    <div class="container">
      <h2 class="title">Video Seminar</h2>
      <video id="seminarVideo" width="100%" height="auto" controls controlsList="nodownload">
        <source src="dist/assets/PMES.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
        
      <div class="progress-steps">
        <div class="step-line"></div>
        <div class="step">1</div>
        <div class="step active">2</div>
        <div class="step">3</div>
        <div class="step">4</div>
      </div>

      <!-- Confirmation Form -->
      <form class="signup-form" 
      id="seminarForm" 
      action="videoseminar.php?member-id=<?php echo htmlspecialchars($userID); ?>" 
      method="POST"
      enctype="application/x-www-form-urlencoded">
       
        <div class="form-row seminar-completed text-center">
          <div class="d-flex justify-content-center align-items-center">
            <input type="checkbox" id="seminar-completed" name="seminar-completed" class="mr-2">
            <label for="seminar-completed" class="ml-2 mb-0">I have completed the video seminar</label>
          </div>
        </div>

        <div class="d-flex justify-content-center gap-4 mt-5">
            <input type="hidden" name="member-id" value="<?php echo htmlspecialchars($userID); ?>">
          <button type="button" name="previousBtn" class="btn btn-previous px-5 py-2" style="background-color: #E9ECEF; border: none; border-radius: 5px; color: #666; width: 150px;">Previous</button>
          <button type="submit" name="nextBtn" class="btn btn-next px-5 py-2" style="background-color: #0F4332; border: none; border-radius: 5px; color: white; width: 150px;">Next</button>
        </div>
      </form>
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
    document.addEventListener("DOMContentLoaded", function() {
    const video = document.getElementById("seminarVideo");
    const nextButton = document.querySelector("button[name='nextBtn']");
    const previousButton = document.querySelector("button[name='previousBtn']");
    const checkbox = document.getElementById("seminar-completed");
    const seminarForm = document.getElementById("seminarForm");
    let videoFullyWatched = false;
    let lastTime = 0;

    // Initially disable checkbox and next button
    checkbox.disabled = true;
    nextButton.disabled = true;

    // Prevent skipping ahead in the video
    video.addEventListener("timeupdate", function() {
        if (video.currentTime > lastTime + 0.5) { 
            // If user tries to skip forward, reset to last watched position
            video.currentTime = lastTime;
        } else {
            lastTime = video.currentTime; // Update last watched position
        }
        // Check if video is close to the end (within last 2 seconds)
        if (video.currentTime >= video.duration - 2) {
            videoFullyWatched = true;
            checkbox.disabled = false;
            console.log("Video fully watched");
        }
    });

    // Checkbox change event
    checkbox.addEventListener("change", function() {
        nextButton.disabled = !checkbox.checked;
        console.log("Checkbox checked status:", checkbox.checked);
    });

    // Previous button navigation
    previousButton.addEventListener("click", function(event) {
        event.preventDefault();
        // Extract member ID from the form action
        const userID = document.querySelector("input[name='member-id']").value;
        
        // Redirect to the previous page with member ID
        window.location.href = `member-application.php?member-id=${userID}`;
    });

    // Form submission handler with enhanced logging and validation
    seminarForm.addEventListener("submit", function(event) {
        console.log("Form submission attempted");
        console.log("Checkbox checked:", checkbox.checked);
        console.log("Video fully watched:", videoFullyWatched);

        // Validation checks
        if (!videoFullyWatched) {
            event.preventDefault();
            alert("Please watch the entire video before submitting.");
            return false;
        }

        if (!checkbox.checked) {
            event.preventDefault();
            alert("Please confirm that you have completed the video seminar.");
            return false;
        }

        // If all validations pass, form will submit
        console.log("Form submission allowed");
        return true;
    });
});
   
</script>
</body>
</html>