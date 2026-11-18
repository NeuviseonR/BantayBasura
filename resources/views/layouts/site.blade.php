<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home') | Bantay Basura</title>
    @vite(['resources/css/app.css'])
    @stack('styles')
    @vite(['resources/css/custom.css'])
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
                @auth
                    <li class="login"><a href="{{ route('user-dash') }}">Dashboard</a> <hr></li>
                @else
                    <li class="login"><a href="{{ route('login') }}">Login</a> <hr></li>
                @endauth
            </ul>
        </div>
    </div>

    @yield('content')

    <footer class="site-footer">
        <div class="footer-container">

            <!-- Top Section: Newsletter / Updates Bar -->
            <div class="footer-top">
                <div class="newsletter-headline">
                    <h2>Get Community Updates &amp; Announcements</h2>
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

    @stack('scripts')
</body>
</html>
