<div class="home-page">
    <section id="homepage" class="page-1">
        <header>
            <a href="index.html" class="logo">
                <img width="24" height="24" src="https://img.icons8.com/fluency-systems-filled/24/cross.png"
                    alt="cross" />
                <span>CemeterEase</span>
            </a>
            <div class="hamburger">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
            <nav class="nav-bar">
                <ul>
                    <li><a href="#hero" class="active">Home</a></li>
                    <li><a href="view-map.php">Map</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#faqs">FAQs</a></li>
                    <li><a href="<?php echo WEBROOT; ?>pages/auth/index.php">Sign In</a></li>
                </ul>
            </nav>
            <!-- Closing the nav tag -->
        </header>
    </section>
    <!-- Closing the header tag -->
    <main>
        <section id="hero" class="hero">
            <div class="container-hero">
                <h1>Effortless Cemetery Management and Navigation</h1>
                <p>
                    Navigate, manage, and honor your loved ones with our comprehensive cemetery mapping and
                    management system.
                </p>
                <div class="icons">
                    <a href="https://www.facebook.com" target="_blank"><i data-lucide="facebook"></i></a>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=cemeterease.memorial@gmail.com"
                        target="_blank"><i data-lucide="mail"></i></a>
                    <a href="09491853866"><i data-lucide="phone"></i></a>
                    <a href="#location"><i data-lucide="map-pin"></i></a>
                </div>
                <div class="cta-buttons">
                    <button class="btn primary" onclick="window.location.href='<?= $page ?>'">Get Started</button>
                </div>
            </div>
        </section>
        <!-- Features Section -->
        <section id="features" class="features">
            <div class="container">
                <div class="feature-grid">
                    <div class="feature">
                        <div class="icons">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <h3>Seamless Navigation</h3>
                        <p>
                            Effortlessly locate gravesites with our intuitive and precise mapping system.
                        </p>
                    </div>
                    <div class="feature">
                        <div class="icons">
                            <i data-lucide="spray-can"></i>
                        </div>
                        <h3>Professional Grave Care</h3>
                        <p>
                            Keep your loved ones' resting places immaculate with our expert grave cleaning services.
                        </p>
                    </div>
                    <div class="feature">
                        <div class="icons">
                            <i data-lucide="bell-ring"></i>
                        </div>
                        <h3>Timely Notifications</h3>
                        <p>
                            Stay informed with reminders for significant dates such as death anniversaries and other
                            important events.
                        </p>
                    </div>
                    <div class="feature">
                        <div class="icons">
                            <i data-lucide="search-check"></i>
                        </div>
                        <h3>Advanced Search</h3>
                        <p>
                            Quickly and easily find records of the deceased with our comprehensive search
                            functionality.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- about Section -->
        <section id="about" class="about">
            <div class="about-container">
                <div class="left">
                    <h2>About CemeterEase Website</h2>
                    <p>
                        CemeterEase simplifies cemetery navigation and maintenance. Our platform offers grave
                        location services, professional grave care, timely notifications, and advanced search
                        capabilities. Whether managing a cemetery or visiting a loved one, CemeterEase provides the
                        tools for efficient and respectful management.
                    </p>
                </div>
                <div class="right">
                    <img src="<?php echo WEBROOT; ?>assets/images/index/about-section-image.png" alt="Slide 1" class="slide" />
                    <img src="<?php echo WEBROOT; ?>assets/images/index/about-section-image2.png" alt="Slide 2" class="slide" />
                    <img src="<?php echo WEBROOT; ?>assets/images/index/about-section-image3.png" alt="Slide 3" class="slide" />
                </div>
            </div>
        </section>

        <!-- location Section -->
        <section id="location" class="location">
            <div class="title">
                <h2>Our Location – Visit Us Today
                </h2>
            </div>
            <div class="container">
                <div class="map-text">
                    <div class="icons">
                        <i data-lucide="map-pin"></i>
                        <h3>Address</h3>
                    </div>
                    <p> 7Q2W+5FV, Poblacion, Ward 3, Minglanilla, 6046 Cebu</p>
                    <div class="icons">
                        <i data-lucide="clock"></i>
                        <h3>Open Hours</h3>
                    </div>
                    <p>5:00 AM - 12:00 AM</p>
                    <div class="photos">
                        <img src="assets/images/index/map-images.jpg" alt="image 1" />
                    </div>
                </div>
                <div class="map-img">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1963.0714175884286!2d123.794762!3d10.250062!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a99d5ff2e08385%3A0xead9ea910c7d98b2!2sMinglanilla%20Cemetery%2C%20Minglanilla%2C%20Cebu!5e0!3m2!1sen!2sph!4v1731767656690!5m2!1sen!2sph"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>
        <!-- FAQs Section -->
        <section id="faqs" class="faqs">
            <div class="container">
                <div class="left-image">
                    <img src="<?php echo WEBROOT; ?>assets/images/index/faqs-image.gif" alt="faqs-image" />
                </div>
                <div class="right-text">
                    <div class="faq-item">
                        <h3>How can I locate a specific gravesite?</h3>
                        <p>To locate a gravesite, use our advanced search feature on the map page. Enter the name or
                            other
                            details of the deceased, and our system will guide you to the exact location.</p>
                    </div>
                    <div class="faq-item">
                        <h3>What services do you offer for grave maintenance?</h3>
                        <p>We offer professional grave care services, including cleaning, flower placement, and
                            maintenance.
                            You can schedule these services through your account dashboard.</p>
                    </div>
                    <div class="faq-item">
                        <h3>How do I get notifications for important dates?</h3>
                        <p>Our system sends automatic reminders for significant dates such as death anniversaries and
                            birthdays. Ensure your contact information is up-to-date in your account settings to receive
                            these notifications.</p>
                    </div>
                    <div class="faq-item">
                        <h3>What are your cemetery's opening hours?</h3>
                        <p>Our cemetery is open from 5:00 AM to 12:00 AM daily, allowing you to visit and pay your
                            respects
                            at a time that is convenient for you.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 CemeterEase. All rights reserved.</p>
            <nav>
                <a href="#">Terms of Service</a>
                <a href="#">Privacy Policy</a>
            </nav>
        </div>
    </footer>
</div>