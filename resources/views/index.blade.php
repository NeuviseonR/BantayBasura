<!DOCTYPE html>
<html>
<head>
    <title>My Website</title>
    @vite(['resources/css/app.css', 'resources/css/custom.css'])
</head>
<body>
    <div class="header">
        <div class="nav">
            <span class="logo">
                <h2>BANTAY &#9851;</h2>
                <h1>BASURA</h1>
            </span>

            <ul class="nav-items">
                <li><a href="{{ route('index') }}">Home</a> <hr></li>
                <li>About <hr></li>
                <li>Services <hr></li>
                <li>Contact <hr></li>
                <li class="login"><a href="{{ route('login') }}">Login</a> <hr></li>
            </ul>
        </div>
    </div>

    <div class="body">
        <div class="hero">
            <div class="hero-text">
                <h1>Barangay Environmental Sanitation Portal</h1>
                <p>Proper waste disposal is now easier than ever.
                   Access waste segregation guidelines,
                   request special pickups, and report
                   sanitation concerns online—helping build a
                   safer and more sustainable neighborhood for
                   every family.</p>
                <div class="hero-btn"><h4>&#8618; Get Started</h4> </div>
            </div>
        </div>


        <div class="features">
            <div class="feat-box">
                <div class="feat-text">
                    <h3>&#9851; Issue Reporting</h3>
                    <p>Submitting waste concerns and illegal dumping
                        reports with photos and locations.</p>
                </div>
            </div>
            <div class="feat-box">
                <div class="feat-text">
                    <h3>&#9851; Volunteering</h3>
                    <p>Browsing, signing up for,
                        and tracking community cleanup drives.</p>
                </div>
            </div>
            <div class="feat-box">
                <div class="feat-text">
                    <h3>&#9851; Waste Management</h3>
                    <p>Tracking collection schedules
                        and identifying waste hotspots.</p>
                </div>
            </div>
        </div>


        <div class="about-us">
            <div class="about-img"></div>
            <div class="about-text">
                <h3>&#9851; About Us</h3>
                <h1>Your Trusted Partner in Smart <br>Waste Solutions</h1>
                <br>
                <p>At Bantay Basura, we bridge the gap between residents and local
                    authorities to solve local sanitation challenges. Designed specifically for
                     barangays, our platform streamlines how waste-related concerns are reported,
                      verified, and resolved while mobilizing community-driven cleanup initiatives.
                       We empower citizens to take an active role in environmental upkeep, equipping
                       local administrators with the structured tools needed to monitor hotspots, manage
                        volunteer turnouts, and maintain a cleaner, more sustainable neighborhood.</p>
                <br>
                <div class="about-text-perks">
                    <span>
                    <div class="perk"><h5>&#8618; Direct Issue Reporting</h5></div>
                    <div class="perk"><h5>&#8618; Admin Verification & Oversight</h5></div>
                    </span>

                    <span>
                    <div class="perk"><h5>&#8618; Community Volunteer Coordination</h5></div>
                    <div class="perk"><h5>&#8618; Waste Hotspot Tracking</h5></div>
                    </span>
                </div>
            </div>

        </div>



        <div class="mivi">
            <div class="mission"><h3>&#10070;<br>Mission</h3>
                <p>To empower barangay communities by providing
                an accessible, centralized platform for
                reporting waste concerns, managing municipal
                collection schedules, and mobilizing active
                resident volunteers to maintain a clean and
                sustainable neighborhood.</p>
            <br></div>
            <div class="vision"><h3>&#128065;<br>Vision</h3>
                <p>To foster a cleaner, safer, and highly engaged
                 community through transparent local governance,
                  digital civic participation, and efficient,
                  technology-driven waste management.</p>
            </div>
        </div>

        <section class="services">

            <!-- Section Header -->
            <div class="services-header">
                <div>
                    <p class="services-subtitle">
                        <span>&#x267B;</span> Our Services
                    </p>
                    <h2 class="services-title">
                        Community Action &<br>Sustainable Waste Management
                    </h2>
                </div>
                <div>
                    <a href="#all-services" class="services-btn">&#8618; Explore All Services</a>
                </div>
            </div>

            <!-- Services Grid -->
            <div class="services-grid">

                <!-- Card 1: Issue Reporting -->
                <div class="service-card">
                    <div class="service-icon-box">
                        &#x25C9;
                    </div>
                    <div class="service-content">
                        <div>
                            <h3>Issue Reporting Module</h3>
                            <p>Allows residents to report community problems, especially those related to waste, such as garbage piles and illegal dumping, with photo and location.</p>
                        </div>
                        <a href="#report" class="service-link">View Details &#x21AA;</a>
                    </div>
                </div>

                <!-- Card 2: Volunteering -->
                <div class="service-card">
                    <div class="service-icon-box">
                        &#x2756;
                    </div>
                    <div class="service-content">
                        <div>
                            <h3>Volunteering Module</h3>
                            <p>Provides a platform where residents can view and participate in clean-up drives and other community programs .</p>
                        </div>
                        <a href="#volunteer" class="service-link">View Details &#x21AA;</a>
                    </div>
                </div>

                <!-- Card 3: Waste Management -->
                <div class="service-card">
                    <div class="service-icon-box">
                        &#x267B;
                    </div>
                    <div class="service-content">
                        <div>
                            <h3>Waste Management Module</h3>
                            <p>Helps monitor garbage collection schedules, identify waste hotspots, and promote proper waste segregation .</p>
                        </div>
                        <a href="#management" class="service-link">View Details &#x21AA;</a>
                    </div>
                </div>

                <!-- Card 4: Admin Dashboard -->
                <div class="service-card">
                    <div class="service-icon-box">
                        &#x1F441;
                    </div>
                    <div class="service-content">
                        <div>
                            <h3>Admin Dashboard</h3>
                            <p>Enables barangay officials to receive reports, assign tasks, and monitor the status of community activities.</p>
                        </div>
                        <a href="#admin" class="service-link">View Details &#x21AA;</a>
                    </div>
                </div>

            </div>
        </section>

        <section class="contact-us">

            <!-- Section Header -->
            <div class="contact-header">
                <p class="contact-subtitle">
                    <span>&#x2756;</span> Get In Touch
                </p>
                <h2 class="contact-title">
                    Connect With Your Barangay Officials
                </h2>
                <p class="contact-desc">
                    Have questions about waste collection schedules, community clean-up drives, or need to report an unresolved environmental concern? Reach out to our team below.
                </p>
            </div>

            <!-- Contact Content Layout -->
            <div class="contact-container">

                <!-- Left Side: Contact Information Cards -->
                <div class="contact-info">
                    <div class="info-card">
                        <div class="info-icon">&#x25C9;</div>
                        <div>
                            <h3>Office Location</h3>
                            <p>Barangay Hall, Minglanilla, Cebu, Philippines</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">&#x2756;</div>
                        <div>
                            <h3>Direct Hotline</h3>
                            <p>+63 (32) 123-4567 / +63 912 345 6789</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">&#x267B;</div>
                        <div>
                            <h3>Office Hours</h3>
                            <p>Monday – Friday: 8:00 AM – 5:00 PM</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Contact Form -->
                <form class="contact-form" action="#" method="POST">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Your Message / Inquiry</label>
                        <textarea id="message" name="message" rows="5" placeholder="Type your message or report details here..." required></textarea>
                    </div>

                    <button type="submit" class="hero-btn">Send Message &#x21AA;</button>
                </form>

            </div>
        </section>

        <footer class="site-footer">
            <div class="footer-container">

                <!-- Top Section: Newsletter / Updates Bar -->
                <div class="footer-top">
                    <div class="newsletter-headline">
                        <h2>Get Community Updates & Announcements</h2>
                    </div>
                    <div class="newsletter-description">
                        <p>Join our notification list for updates on garbage collection schedules, upcoming community clean-up drives, and local environmental initiatives.</p>
                    </div>
                    <form class="newsletter-form" action="#" method="POST">
                        <input type="email" placeholder="Email Address" required>
                        <button type="submit" class="btn-subscribe">Subscribe Now</button>
                    </form>
                </div>

                <hr class="footer-divider">

                <!-- Main Section: 4 Columns Layout -->
                <div class="footer-main">

                    <!-- Column 1: Branding & Description -->
                    <div class="footer-col col-brand">
                        <div class="footer-logo">
                            <span>&#x267B;</span> Community Clean
                        </div>
                        <p class="brand-desc">
                            Your reliable partner for smart waste reporting, community cleanup coordination, and local environmental management.
                        </p>
                        <div class="social-links">
                            <a href="#" aria-label="Facebook">&#x21AA;</a>
                            <a href="#" aria-label="Twitter / X">&#x2756;</a>
                            <a href="#" aria-label="YouTube">&#x25C9;</a>
                            <a href="#" aria-label="Instagram">&#x1F441;</a>
                        </div>
                    </div>

                    <!-- Column 2: Quick Links -->
                    <div class="footer-col">
                        <h3>Quick Links</h3>
                        <ul class="footer-links">
                            <li><a href="#home">Home</a></li>
                            <li><a href="#about">About Us</a></li>
                            <li><a href="#services">Our Services</a></li>
                            <li><a href="#report">Submit a Report</a></li>
                            <li><a href="#contact">Contact Us <span class="badge">New</span></a></li>
                        </ul>
                    </div>

                    <!-- Column 3: Our Services -->
                    <div class="footer-col">
                        <h3>Our Services</h3>
                        <ul class="footer-links">
                            <li><a href="#reporting">Issue Reporting Module</a></li>
                            <li><a href="#volunteering">Volunteering Module</a></li>
                            <li><a href="#waste-management">Waste Management</a></li>
                            <li><a href="#hotspots">Waste Hotspot Map</a></li>
                            <li><a href="#admin">Admin Verification <span class="badge">New</span></a></li>
                        </ul>
                    </div>

                    <!-- Column 4: Contact Info -->
                    <div class="footer-col">
                        <h3>Contact Info</h3>
                        <ul class="contact-list">
                            <li>
                                <span class="icon">&#x25C9;</span>
                                <span>Barangay Hall, Minglanilla, Cebu, Philippines</span>
                            </li>
                            <li>
                                <span class="icon">&#x2756;</span>
                                <span>+63 (32) 123-4567</span>
                            </li>
                            <li>
                                <span class="icon">&#x21AA;</span>
                                <span>support@barangayclean.gov.ph</span>
                            </li>
                            <li>
                                <span class="icon">&#x267B;</span>
                                <span>Mon &ndash; Fri: 8:00 AM &ndash; 5:00 PM</span>
                            </li>
                        </ul>
                    </div>

                </div>

                <hr class="footer-divider">

                <!-- Bottom Section: Copyright & Legal -->
                <div class="footer-bottom">
                    <p>&copy; 2026 CoreTechs. St. Cecilia's College &ndash; Cebu, Inc.</p>
                    <div class="legal-links">
                        <a href="#">Privacy Policy</a>
                        <span>|</span>
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>

            </div>
        </footer>
    </div>
</body>
</html>
