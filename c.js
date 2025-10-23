function toggleNavbar() {
    const navbarLinks = document.querySelector('.navbar-links');
    navbarLinks.classList.toggle('active');
  }

  let currentSlide = 0;

function showSlide(index) {
    const slides = document.querySelector('.slides');
    const totalSlides = document.querySelectorAll('.slide').length;

    if (index >= totalSlides) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = totalSlides - 1;
    } else {
        currentSlide = index;
    }

    slides.style.transform = `translateX(${-currentSlide * 100}%)`;
}

function nextSlide() {
    showSlide(currentSlide + 1);
}

function prevSlide() {
    showSlide(currentSlide - 1);
}

// Auto-slide functionality
setInterval(nextSlide, 5000); // Change slide every 5 seconds

function toggleNavbar() {
    const navbarLinks = document.querySelector('.navbar-links');
    const navbarToggle = document.querySelector('.navbar-toggle');
    navbarLinks.classList.toggle('active');
    navbarToggle.classList.toggle('active');
    
    if (navbarToggle.classList.contains('active')) {
        navbarToggle.innerHTML = "<i class='fas fa-times'></i>";
    } else {
        navbarToggle.innerHTML = "<span></span><span></span><span></span>";
    }
}


    function showLatestBids() {
        document.getElementById("latest-bids").classList.remove("hidden");
        document.getElementById("latest-bids").scrollIntoView({ behavior: "smooth" });
    }


// registration frarmer 
function showFarmerForm() {
    document.getElementById("farmer-signin").classList.remove("hidden");
}

function closeFarmerForm() {
    document.getElementById("farmer-signin").classList.add("hidden");
}

document.getElementById("farmerForm").addEventListener("submit", function(event) {
    event.preventDefault();
    alert("Sign-In Successful ✅");
    closeFarmerForm();
});


// bidder
function showBidderForm() {
    document.getElementById("bidder-signin").classList.remove("hidden");
}


function closeBidderForm() {
    document.getElementById("bidder-signin").classList.add("hidden");
}

document.getElementById("bidderForm").addEventListener("submit", function(event) {
    event.preventDefault();
    alert("Bidder Sign-In Successful ✅");
    closeBidderForm();
});

// farmer login
function showFarmerlogin() {
    document.getElementById("farmer-login").classList.remove("hidden");
}


function closeFarmerlogin() {
    document.getElementById("farmer-login").classList.add("hidden");
}

document.getElementById("farmerlogin").addEventListener("submit", function(event) {
    event.preventDefault();
    alert("Bidder Sign-In Successful ✅");
    closeFarmerlogin();
});

// bidder login
function showbidderlogin() {
    document.getElementById("bidder-login").classList.remove("hidden");
}


function closebidderlogin() {
    document.getElementById("bidder-login").classList.add("hidden");
}

document.getElementById("bidderlogin").addEventListener("submit", function(event) {
    event.preventDefault();
    alert("Bidder Sign-In Successful ✅");
    closebidderlogin();
});


// Admin login
function showAdminlogin() {
    document.getElementById("Admin-login").classList.remove("hidden");
}


function closeAdminlogin() {
    document.getElementById("Admin-login").classList.add("hidden");
}

document.getElementById("Adminlogin").addEventListener("submit", function(event) {
    event.preventDefault();
    alert("Bidder Sign-In Successful ✅");
    closeAdminlogin();
});


// Add this script for animated stats
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('[data-count]');
    
    const animateCount = (counter) => {
        const target = +counter.getAttribute('data-count');
        const duration = 2000;
        const stepTime = 50;
        
        let current = 0;
        const increment = target / (duration / stepTime);
        
        const timer = setInterval(() => {
            current += increment;
            counter.textContent = Math.ceil(current);
            if(current >= target) {
                clearInterval(timer);
                counter.textContent = target + '+';
            }
        }, stepTime);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                animateCount(entry.target);
                observer.unobserve(entry.target);
            }
        });
    });

    counters.forEach(counter => {
        observer.observe(counter);
    });
});


// Smooth scroll implementation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        
        // Add active class to clicked link
        this.classList.add('active');
        
        // Scroll to target section
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Highlight active section on scroll
window.addEventListener('scroll', () => {
    const aboutSection = document.getElementById('about');
    const navLinks = document.querySelectorAll('.nav-link');
    
    const sectionTop = aboutSection.offsetTop;
    const sectionHeight = aboutSection.offsetHeight;
    const scrollPosition = window.pageYOffset;

    if (scrollPosition >= sectionTop - 50 && 
        scrollPosition < sectionTop + sectionHeight - 50) {
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#about') {
                link.classList.add('active');
            }
        });
    }
});

// Dynamic Year Update
document.getElementById('currentYear').textContent = new Date().getFullYear();

// Scroll to Top Button
window.addEventListener('scroll', function() {
    const scrollTop = document.querySelector('.scroll-top');
    if (window.scrollY > 300) {
        scrollTop.classList.add('show');
    } else {
        scrollTop.classList.remove('show');
    }
});

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Newsletter Form Handling
document.getElementById('newsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input').value;
    
    // Add your newsletter API call here
    fetch('/subscribe', {
        method: 'POST',
        body: JSON.stringify({ email }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            alert('Thanks for subscribing!');
            this.reset();
        }
    })
    .catch(error => {
        console.error('Subscription error:', error);
    });
});