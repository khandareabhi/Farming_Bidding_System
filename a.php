<?php
session_start(); // Start session at the top
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        /* Crop Theme Colors */
        :root {
            --primary-green: #2e7d32;
            --secondary-green: #388e3c;
            --accent-orange: #f57f17;
            --light-beige: #f1f8e9;
            --dark-green: #1b5e20;
        }

        .awareness-section {
            padding: 4rem 2rem;
            background: var(--light-beige);
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: var(--dark-green);
            margin-bottom: 3rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 100px;
            height: 4px;
            background: var(--accent-orange);
            margin: 1rem auto;
        }

        .video-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .video-card {
            flex: 1 1 100%;
            display: flex;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            min-width: 300px;
        }

        .video-card:nth-child(even) .video-content {
            order: -1;
        }

        .video-player {
            flex: 1;
            min-width: 300px;
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
        }

        .video-player iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 15px 0 0 15px;
        }

        .video-info {
            flex: 1;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: white;
        }

        .video-info h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--light-beige);
        }

        .video-info p {
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .video-features {
            list-style: none;
            padding: 0;
        }

        .video-features li {
            margin-bottom: 0.8rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .video-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--accent-orange);
        }

        .cta-button {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: var(--accent-orange);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .video-card {
                flex-direction: column;
            }

            .video-card:nth-child(even) .video-content {
                order: 0;
            }

            .video-player {
                padding-bottom: 56.25%;
                border-radius: 15px 15px 0 0;
            }

            .video-info {
                border-radius: 0 0 15px 15px;
            }

            .section-title {
                font-size: 2rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .video-card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biding Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="b.css">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <h1>AS Crops Bidding</h1>
        </div>
        <ul class="navbar-links">


            <li><a href="a.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="biding_page.php" onclick="showLatestBids()"><i class="fas fa-store"></i> Market</a></li>
            <li>
                <a href="#Registration"><i class="fas fa-user"></i> Registration <i class="fas fa-caret-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="#farmer" onclick="showFarmerForm()">Farmer</a></li>
                    <li><a href="#Bidder" onclick="showBidderForm()">Bidder</a></li>
                </ul>
            </li>
            <li><a href="#Blogs"><i class="fas fa-rocket"></i> Blogs</a></li>
            <li><a href="#feedback"><i class="fas fa-comment"></i> Feedback</a></li>
            <li><a href="#about" class="nav-link">About Us</a></li>
            <li>


            <li id="userProfile">
                <?php
                if (isset($_SESSION['a']) && isset($_SESSION['role'])) {
                    //echo  $_SESSION['a']['photo'];
                    $userPhoto = ($_SESSION['a']['photo']) ? $_SESSION['a']['photo'] : 'default.jpg';
                    $dashboard = "#"; // Default

                    // Assign dashboard based on user role
                    if ($_SESSION['role'] == 'Farmer') {
                        $dashboard = "farmer_dashboard.php";
                    } elseif ($_SESSION['role'] == 'Bidder') {
                        $dashboard = "bidder_dashboard.php";
                    } elseif ($_SESSION['role'] == 'Admin') {
                        $dashboard = "admin_dashboard.php";
                    }

                    echo '<a href="' . $dashboard . '">
                <img src="' . $userPhoto . '" class="profile-photo" alt="Profile">
                
              </a>';
                } else {
                    echo '<a href="#Login" class="auth-link register">
                <i class="fas fa-user"></i> Login <i class="fas fa-caret-down"></i>
              </a>';
                    echo '<ul class="dropdown-menu">
                <li><a href="#farmer" onclick="showFarmerlogin()">Farmer</a></li>
                <li><a href="#Bidder" onclick="showbidderlogin()">Bidder</a></li>
                <li><a href="#Admin" onclick="showAdminlogin()">Admin</a></li>
              </ul>';
                }
                ?>
            </li>

            </li>
        </ul>

        <?php include __DIR__ . '/includes/lang_selector.php'; ?>

        <div class="navbar-toggle" onclick="toggleNavbar()">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <!-- Sliding Advertisement Section -->
    <section class="advertisement">
        <div class="slider">
            <div class="slides">
                <div class="slide"><img src="slide1.jpg" alt="Ad 1"></div>
                <div class="slide"><img src="slide2.jpg" alt="Ad 2"></div>
                <div class="slide"><img src="slide3.jpg" alt="Ad 3"></div>
            </div>
            <button class="prev" onclick="prevSlide()">&#10094;</button>
            <button class="next" onclick="nextSlide()">&#10095;</button>
        </div>
    </section>

    <!-- video -->
    <section class="awareness-section">
        <h2 class="section-title">🌱 Bidding Awareness Program</h2>

        <div class="video-container">
            <!-- Video 1 -->
            <div class="video-card">
                <div class="video-player">
                    <iframe src="v1.mp4" allowfullscreen></iframe>
                </div>
                <div class="video-info">
                    <h3>Understanding Agricultural Bidding</h3>
                    <p>Learn the fundamentals of modern agricultural bidding practices and how they benefit farmers.</p>
                    <ul class="video-features">
                        <li>Step-by-step bidding process</li>
                        <li>Market price optimization</li>
                        <li>Digital platform benefits</li>
                    </ul>
                    <a href="https://www.youtube.com/results?search_query=Understanding+Agricultural+Bidding" class="cta-button">Watch More</a>
                </div>
            </div>

            <!-- Video 2 -->
            <div class="video-card">
                <div class="video-player">
                    <iframe src="v2.mp4" allowfullscreen></iframe>
                </div>
                <div class="video-info">
                    <h3>Maximizing Crop Value</h3>
                    <p>Discover strategies to get the best value for your crops through competitive bidding.</p>
                    <ul class="video-features">
                        <li>Crop quality assessment</li>
                        <li>Bidding timeline management</li>
                        <li>Market trend analysis</li>
                    </ul>
                    <a href="https://www.youtube.com/results?search_query=Maximizing+Crop+Value" class="cta-button">Watch More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Login and Registration Forms -->
    <div id="farmer-signin" class="form-container hidden">
        <div class="form-box">
            <h2>🚜 Farmer Registration</h2>
            <br><br>
            <form action="farmer_data_adding.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" id="name" name="name" pattern="[A-Za-z\s]+" title="Name can only contain alphabetic characters and spaces" required>
                    <span class="error" id="nameError"></span>
                </div>
                <div class="form-group">
                    <label>City:</label>
                    <input type="text" id="city" name="city" required>
                    <span class="error" id="cityError"></span>
                </div>
                <div class="form-group">
                    <label>Pincode:</label>
                    <input type="number" id="pincode" name="pincode" pattern="^\d{6}$" title="Pincode must be 6 digits" required>
                    <span class="error" id="pincodeError"></span>
                </div>
                <div class="form-group">
                    <label>State:</label>
                    <input type="text" id="state" name="state" maxlength="18" required>
                    <span class="error" id="stateError"></span>
                </div>
                <div class="form-group">
                    <label>Country:</label>
                    <input type="text" id="country" name="country" maxlength="20" required>
                    <span class="error" id="countryError"></span>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" id="email" name="email" maxlength="30" required>
                    <span class="error" id="emailError"></span>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error" id="passwordError"></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password:</label>
                    <input type="password" id="confirm-password" name="c_password" required>
                    <span class="error" id="confirmPasswordError"></span>
                </div>
                <!-- ✅ New Photo Upload Field -->
                <div class="form-group">
                    <label>Upload Profile Photo:</label>
                    <input type="file" name="photo" accept="image/*" required>
                </div>
                <div class="button-group">
                    <button type="submit">Sign In</button>
                    <button type="reset">Reset</button>
                </div>
                <div class="button-group centered">
                    <button type="button" class="close-btn" onclick="closeFarmerForm()">❌ Close</button>
                </div>
            </form>

        </div>
    </div>


    <!-- bidder Registration -->
    <div id="bidder-signin" class="form-container hidden">
        <div class="form-box">
            <h2>🚜 Bidder Registration</h2>
            <br><br>
            <form action="bidder_data_addded.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" id="name" name="name" required>
                    <span class="error" id="nameError"></span>
                </div>
                <div class="form-group">
                    <label>City:</label>
                    <input type="text" id="city" name="city" required>
                    <span class="error" id="cityError"></span>
                </div>
                <div class="form-group">
                    <label>Pincode:</label>
                    <input type="number" id="pincode" name="pincode" required>
                    <span class="error" id="pincodeError"></span>
                </div>
                <div class="form-group">
                    <label>State:</label>
                    <input type="text" id="state" name="state" required>
                    <span class="error" id="stateError"></span>
                </div>
                <div class="form-group">
                    <label>Country:</label>
                    <input type="text" id="country" name="country" required>
                    <span class="error" id="countryError"></span>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error" id="emailError"></span>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error" id="passwordError"></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password:</label>
                    <input type="password" id="confirm-password" name="c_password" required>
                    <span class="error" id="confirmPasswordError"></span>
                </div>
                <div class="form-group">
                    <label>Upload Profile Photo:</label>
                    <input type="file" name="photo" accept="image/*" required>
                </div>
                <div class="button-group">
                    <button type="submit">Sign In</button>
                    <button type="reset">Reset</button>
                </div>
                <div class="button-group centered">
                    <button type="button" class="close-btn" onclick="closeBidderForm()">❌ Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Farmer Login -->

    <div id="farmer-login" class="form-container hidden">
        <div class="form-box">
            <h2>🚜 Farmer Log in</h2>
            <br><br>
            <form id="farmerlogin" action="farmer_login.php" method="post">
                <div class="form-group full-width">
                    <label>Email:</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error" id="emailError"></span>
                </div>
                <div class="form-group full-width">
                    <label>Password:</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error" id="passwordError"></span>
                </div>

                <div class="button-group">
                    <button type="submit">Sign In</button>
                    <button type="reset">Reset</button>
                </div>

                <!-- Centered Close Button -->
                <div class="button-group centered">
                    <button type="button" class="close-btn" onclick="closeFarmerlogin()">❌ Close</button>
                </div>
            </form>
        </div>
    </div>


    <!-- bidder Login -->
    <div id="line-299">
        <div id="bidder-login" class="form-container hidden">
            <div class="form-box">
                <h2>🚜 Bidder Log in</h2>
                <br><br>
                <form id="bidderlogin" action="bidder_login.php" method="post">
                    <div class="form-group full-width">
                        <label>Email:</label>
                        <input type="email" id="email" name="email" required>
                        <span class="error" id="emailError"></span>
                    </div>
                    <div class="form-group full-width">
                        <label>Password:</label>
                        <input type="password" id="password" name="password" required>
                        <span class="error" id="passwordError"></span>
                    </div>

                    <div class="button-group">
                        <button type="submit">Sign In</button>
                        <button type="reset">Reset</button>
                    </div>

                    <!-- Centered Close Button -->
                    <div class="button-group centered">
                        <button type="button" class="close-btn" onclick="closebidderlogin()">❌ Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>



    <!-- admin login -->
    <div id="Admin-login" class="form-container hidden">
        <div class="form-box">
            <h2>🚜 Admin Log in</h2>
            <br><br>
            <form id="Adminlogin" action="admin_login.php" method="post">
                <div class="form-group full-width">
                    <label>Email:</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error" id="emailError"></span>
                </div>
                <div class="form-group full-width">
                    <label>Password:</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error" id="passwordError"></span>
                </div>

                <div class="button-group">
                    <button type="submit">Sign In</button>
                    <button type="reset">Reset</button>
                </div>

                <!-- Centered Close Button -->
                <div class="button-group centered">
                    <button type="button" class="close-btn" onclick="closeAdminlogin()">❌ Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- blog section -->
    <!-- 📚 Blogs Section 📚 -->
    <section class="blogs-section" id="Blogs">
        <h2>Latest Farming Insights & News</h2>
        <div class="blog-container">
            <!-- Blog Card 1 -->
            <article class="blog-card">
                <div class="blog-image">
                    <img src="organic1.jpg" alt="Organic Farming Techniques">
                </div>
                <div class="blog-content">
                    <div class="blog-meta">
                        <span class="blog-category">Farming Techniques</span>
                        <span class="blog-date">March 15, 2024</span>
                    </div>
                    <h3>Modern Organic Farming Methods</h3>
                    <p>Discover the latest innovations in organic crop cultivation and sustainable farming practices that are revolutionizing agriculture...</p>
                    <a href="https://asqi.in/future-trends-in-organic-farming-technology/" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>

            <!-- Blog Card 2 -->
            <article class="blog-card">
                <div class="blog-image">
                    <img src="organic2.jpg" alt="Crop Market Trends">
                </div>
                <div class="blog-content">
                    <div class="blog-meta">
                        <span class="blog-category">Market Trends</span>
                        <span class="blog-date">March 12, 2024</span>
                    </div>
                    <h3>2024 Crop Pricing Predictions</h3>
                    <p>Expert analysis of upcoming trends in agricultural commodity markets and what it means for farmers and bidders...</p>
                    <a href="https://www.gminsights.com/industry-analysis/machine-learning-for-crop-yield-prediction-market" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>

            <!-- Blog Card 3 -->
            <article class="blog-card">
                <div class="blog-image">
                    <img src="organic3.jpg" alt="Agricultural Technology">
                </div>
                <div class="blog-content">
                    <div class="blog-meta">
                        <span class="blog-category">Agri-Tech</span>
                        <span class="blog-date">March 10, 2024</span>
                    </div>
                    <h3>Smart Farming Technologies</h3>
                    <p>Explore how IoT and AI are transforming traditional farming practices and improving crop yields...</p>
                    <a href="https://eos.com/blog/smart-farming/" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </article>
        </div>
    </section>

    <!-- feedback form section -->
    <section id="feedback" class="feedback-section">
        <h2>💬 We Value Your Feedback</h2>
        <p>Help us improve by sharing your experience.</p>

        <form class="feedback-form">
            <label for="name">Your Name</label>
            <input type="text" id="name" placeholder="Enter your name" required>

            <label for="email">Your Email</label>
            <input type="email" id="email" placeholder="Enter your email" required>

            <label for="message">Your Feedback</label>
            <textarea id="message" rows="4" placeholder="Write your feedback..." required></textarea>

            <button type="submit">Submit Feedback</button>
        </form>
    </section>

    <?php
    include 'db_connection.php'; // Ensure this file connects to your database

    $query = "SELECT COUNT(*) AS total_users FROM registration";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $totalUsers = $row['total_users']; // Store user count
    ?>

    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="about-container">
            <div class="about-content">
                <h2>About AS Crops Bidding</h2>
                <p class="tagline">Connecting Farmers & Buyers Through Transparent Bidding</p>

                <div class="about-grid">
                    <!-- Dynamic Stats -->
                    <div class="stats-card">
                        <i class="fas fa-users"></i>
                        <div class="stats-info">
                            <h3 data-count="2500"><?php echo $totalUsers ?></h3>
                            <p>Registered Users</p>
                        </div>
                    </div>

                    <div class="about-text">
                        <h3>Why Choose Us?</h3>
                        <ul class="features-list">
                            <li><i class="fas fa-shield-alt"></i> Secure Transactions</li>
                            <li><i class="fas fa-chart-line"></i> Real-time Market Data</li>
                            <li><i class="fas fa-handshake"></i> Fair Pricing</li>
                            <li><i class="fas fa-mobile-alt"></i> Mobile-friendly Platform</li>
                        </ul>
                    </div>

                    <div class="about-text">
                        <h3>Our Mission</h3>
                        <p>Empowering farmers with fair market prices and providing buyers direct access to quality agricultural produce through our innovative bidding platform.</p>
                        <a href="#Registration" class="cta-btn">Join Now</a>
                    </div>


                </div>
            </div>

            <!-- Team Section -->
            <div class="team-section">
                <h3>Our Team</h3>
                <div class="team-grid">
                    <div class="team-card">
                        <img src="omkar.jpg" alt="Team Member">
                        <h4>Omkar Satpute</h4>
                        <p>Founder & CEO</p>
                        <div class="social-links">
                            <a href="https://www.linkedin.com/login?fromSignIn=true&trk=guest_homepage-basic_nav-header-signin"><i class="fab fa-linkedin"></i></a>
                            <a href="https://x.com/i/flow/login"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <!-- Add more team cards -->
                    <div class="team-card">
                        <img src="om2.jpg" alt="Team Member">
                        <h4>Abhishek Khandare</h4>
                        <p>Full Stack Developer</p>
                        <div class="social-links">
                            <a href="https://www.linkedin.com/login?fromSignIn=true&trk=guest_homepage-basic_nav-header-signin"><i class="fab fa-linkedin"></i></a>
                            <a href="https://x.com/i/flow/login"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>

                    <div class="team-card">
                        <img src="om3.jpg" alt="Team Member">
                        <h4>Shrinivas Kumri</h4>
                        <p>Full Stack Developer</p>
                        <div class="social-links">
                            <a href="https://www.linkedin.com/login?fromSignIn=true&trk=guest_homepage-basic_nav-header-signin"><i class="fab fa-linkedin"></i></a>
                            <a href="https://x.com/i/flow/login"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- footer section -->
    <footer class="site-footer">
        <div class="footer-container">
            <!-- Footer Columns -->
            <div class="footer-columns">
                <!-- Company Info -->
                <div class="footer-col">
                    <h3 class="footer-logo">AS Crops Bidding</h3>
                    <p class="footer-text">Empowering farmers through transparent digital agriculture solutions.</p>
                    <div class="social-links">
                        <a href="https://en-gb.facebook.com/login/web/" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/i/flow/login?input_flow_data=%7B%22requested_variant%22%3A%22eyIiOiIiLCJteCI6IjIifQ%3D%3D%22%7D" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/accounts/login/?hl=en" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/login" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul class="footer-menu">
                        <li><a href="a.php">Home</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#market">Market</a></li>
                        <li><a href="#Blogs"><i class="fas fa-rocket"></i> Blogs</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>

                    <!-- <li><a href="#Blogs"><i class="fas fa-rocket"></i> Blogs</a></li>
            <li><a href="#feedback"><i class="fas fa-comment" class="nav-link"></i> Feedback</a></li>
            <li><a href="#about" class="nav-link">About Us</a></li> -->
                </div>

                <!-- Contact Info -->
                <div class="footer-col">
                    <h4>Contact Us</h4>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i>123 Farm Street, Agri City, IN 560001</li>
                        <li><i class="fas fa-phone"></i><a href="tel:+919876543210">+91 98765 43210</a></li>
                        <li><i class="fas fa-envelope"></i><a href="mailto:info@ascrops.com">info@ascrops.com</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="footer-col">
                    <h4>Newsletter</h4>
                    <form class="newsletter-form" id="newsletterForm">
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit">Subscribe</button>
                    </form>
                    <p class="newsletter-text">Get latest market updates and farming tips</p>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="copyright">
                    &copy; <span id="currentYear"></span> AS Crops Bidding. All rights reserved.
                </div>
                <div class="legal-links">
                    <a href="#privacy">Privacy Policy</a>
                    <a href="#terms">Terms of Service</a>
                    <a href="#faq">FAQs</a>
                </div>
            </div>
        </div>

        <!-- Scroll to Top -->
        <button class="scroll-top" onclick="scrollToTop()">
            <i class="fas fa-chevron-up"></i>
        </button>
    </footer>

    <script src="c.js"></script>
</body>

</html>