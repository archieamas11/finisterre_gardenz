<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo WEBROOT; ?>assets/images/icon.png" type="image/x-icon">
    <title><?php echo $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/general.css?=v6">
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>assets/css/home-index.css">
    
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <div class="homepage-wrapper">
        <?php include($homepage_content); ?>
    </div>
</body>

<!-- Scripts -->
<script src="<?php echo WEBROOT; ?>webmap/navbar/navbar.js"></script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/68249c3d8984c1190f3abff7/1ir7g4kiv';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>

<script>
    lucide.createIcons();
    // JavaScript to toggle the FAQ dropdown
    document.querySelectorAll('.faq-item h3').forEach(item => {
        item.addEventListener('click', () => {
            const faqItem = item.parentElement;

            // Check if the clicked FAQ item is already active
            if (faqItem.classList.contains('active')) {
                // If it is active, remove the active class (collapse it)
                faqItem.classList.remove('active');
            } else {
                // If it is not active, remove 'active' from all items
                document.querySelectorAll('.faq-item').forEach(faq => faq.classList.remove('active'));

                // Add 'active' class to the clicked item (expand it)
                faqItem.classList.add('active');
            }
        });
    });


    const slides = document.querySelectorAll('.right .slide');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    // Start the slideshow
    showSlide(currentSlide);
    setInterval(nextSlide, 3000); // Change slide every 3 seconds
</script>

</html>