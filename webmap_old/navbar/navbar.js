const hamburger = document.querySelector('.hamburger');
const navBar = document.querySelector('.nav-bar');
const navLinks = document.querySelectorAll('.nav-bar ul li a');
const header = document.querySelector('header');
const body = document.body;

// Create and append overlay if it doesn't exist
if (!document.querySelector('.overlay')) {
    const overlay = document.createElement('div');
    overlay.className = 'overlay';
    document.body.appendChild(overlay);
}

const overlay = document.querySelector('.overlay');

hamburger.onclick = function() {
    hamburger.classList.toggle('active');
    navBar.classList.toggle('active');
    body.classList.toggle('menu-open');

    if (navBar.classList.contains('active')) {
        header.classList.add('change-background');
    } else {
        header.classList.remove('change-background');
    }
};

// Close menu when clicking overlay
overlay.onclick = function() {
    closeMenu();
};

// Close menu when clicking a navigation link
navLinks.forEach(link => {
    link.onclick = function() {
        // Set active state for the clicked link
        navLinks.forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');
        
        // Close the mobile menu
        closeMenu();
    };
});

// Function to close the menu
function closeMenu() {
    hamburger.classList.remove('active');
    navBar.classList.remove('active');
    body.classList.remove('menu-open');
    header.classList.remove('change-background');
}

window.onscroll = function() {
    const navbar = document.querySelector('header');
    if (window.scrollY > 0) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
};

// Close menu when window is resized to desktop view
window.addEventListener('resize', function() {
    if (window.innerWidth > 1024) {
        closeMenu();
    }
});