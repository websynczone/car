// Vehicle Data
const vehicles = [
    {
        id: 1,
        name: 'Maruti Suzuki Dzire',
        category: 'sedan',
        image: 'images/vehicles/dzire.svg',
        price: '₹12',
        period: '/km',
        features: [
            { icon: 'fas fa-users', text: '4 Seats' },
            { icon: 'fas fa-snowflake', text: 'AC' },
            { icon: 'fas fa-music', text: 'Music System' },
            { icon: 'fas fa-gas-pump', text: 'Petrol' }
        ],
        badge: 'Popular'
    },
    {
        id: 2,
        name: 'Maruti Suzuki Ertiga',
        category: 'suv',
        image: 'images/vehicles/ertiga.svg',
        price: '₹15',
        period: '/km',
        features: [
            { icon: 'fas fa-users', text: '7 Seats' },
            { icon: 'fas fa-snowflake', text: 'AC' },
            { icon: 'fas fa-music', text: 'Music System' },
            { icon: 'fas fa-gas-pump', text: 'Petrol' }
        ],
        badge: 'Family'
    },
    {
        id: 3,
        name: 'Toyota Innova Crysta',
        category: 'luxury',
        image: 'images/vehicles/innova.svg',
        price: '₹18',
        period: '/km',
        features: [
            { icon: 'fas fa-users', text: '7 Seats' },
            { icon: 'fas fa-snowflake', text: 'AC' },
            { icon: 'fas fa-music', text: 'Premium Audio' },
            { icon: 'fas fa-gas-pump', text: 'Diesel' }
        ],
        badge: 'Premium'
    },
    {
        id: 4,
        name: 'Mahindra Scorpio',
        category: 'suv',
        image: 'images/vehicles/scorpio.svg',
        price: '₹16',
        period: '/km',
        features: [
            { icon: 'fas fa-users', text: '7 Seats' },
            { icon: 'fas fa-snowflake', text: 'AC' },
            { icon: 'fas fa-music', text: 'Music System' },
            { icon: 'fas fa-gas-pump', text: 'Diesel' }
        ],
        badge: 'Adventure'
    },
    {
        id: 5,
        name: 'Honda City',
        category: 'sedan',
        image: 'images/vehicles/city.svg',
        price: '₹14',
        period: '/km',
        features: [
            { icon: 'fas fa-users', text: '5 Seats' },
            { icon: 'fas fa-snowflake', text: 'AC' },
            { icon: 'fas fa-music', text: 'Music System' },
            { icon: 'fas fa-gas-pump', text: 'Petrol' }
        ],
        badge: 'Comfort'
    },
    {
        id: 6,
        name: 'Toyota Fortuner',
        category: 'luxury',
        image: 'images/vehicles/fortuner.svg',
        price: '₹22',
        period: '/km',
        features: [
            { icon: 'fas fa-users', text: '7 Seats' },
            { icon: 'fas fa-snowflake', text: 'AC' },
            { icon: 'fas fa-music', text: 'Premium Audio' },
            { icon: 'fas fa-gas-pump', text: 'Diesel' }
        ],
        badge: 'Luxury'
    }
];

// Testimonial Data
const testimonials = [
    {
        id: 1,
        name: 'Deep Biswas',
        role: 'Web Developer, TnyToday (pvt Ltd)',
        image: 'images/testimonials/deep.jpg',
        rating: 5,
        text: 'The professionalism of the CabinPatna staff stood out to me. They helped me find the perfect vehicle for my needs at an unbeatable price.'
    },
    {
        id: 2,
        name: 'Shivpujan kumar',
        role: 'CEO, Harsh Tour and Travels',
        image: 'images/testimonials/shivpujan.jpg',
        rating: 5,
        text: 'I rely on CabinPatna for all my travel needs. Their convenient pick-up and drop-off services make my photography assignments a breeze'
    },
    {
        id: 3,
        name: 'Harsh kumar',
        role: 'Freelance, TnyToday (pvt Ltd)',
        image: 'images/testimonials/harsh.jpg',
        rating: 5,
        text: 'I\'ve tried many rental services, but none compare to CabinPatna. Their excellent prices and easy booking process keep me coming back every time!'
    },
    {
        id: 4,
        name: 'Nitin Thakur',
        role: 'Traveller, Harsh Tour and Travels',
        image: 'images/testimonials/nitin.jpg',
        rating: 5,
        text: 'Committed to Providing Prompt and Reliable Support for a Seamless and Enjoyable Rental Experience'
    }
];

// Available Locations
const locations = [
    'Bodh gaya',
    'Sitamadhi',
    'Chappra',
    'Ara',
    'Motihari',
    'Buxar',
    'Ballia',
    'Deoghar',
    'Aurangabad',
    'Samastipur',
    'Madhubani',
    'Purnia',
    'Begusarai',
    'Gaya',
    'Varanasi',
    'Darbangha',
    'Patna'
];

// DOM Elements
const vehicleList = document.getElementById('vehicle-list');
const testimonialList = document.getElementById('testimonial-list');
const navbar = document.querySelector('.navbar');

// Populate Vehicle Cards
function populateVehicleCards(filter = 'all') {
    const vehicleList = document.getElementById('vehicle-list');
    if (!vehicleList) return;
    
    vehicleList.innerHTML = '';
    
    const filteredVehicles = filter === 'all' 
        ? vehicles 
        : vehicles.filter(vehicle => vehicle.category === filter);
    
    filteredVehicles.forEach(vehicle => {
        const vehicleCard = document.createElement('div');
        vehicleCard.className = 'col-lg-4 col-md-6';
        vehicleCard.innerHTML = `
            <div class="vehicle-card">
                <div class="vehicle-image">
                    <img src="${vehicle.image}" alt="${vehicle.name}">
                    ${vehicle.badge ? `<div class="vehicle-badge">${vehicle.badge}</div>` : ''}
                    <div class="vehicle-overlay">
                        <div class="vehicle-features">
                            ${vehicle.features.map(feature => `
                                <div class="vehicle-feature">
                                    <i class="${feature.icon}"></i>
                                    <span>${feature.text}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
                <div class="vehicle-details">
                    <h3 class="vehicle-name">${vehicle.name}</h3>
                    <div class="vehicle-price">
                        <div class="price-tag">
                            <span class="price-amount">${vehicle.price}</span>
                            <span class="price-period">${vehicle.period}</span>
                        </div>
                    </div>
                    <a href="https://wa.me/919304514974?text=I'm interested in booking the ${encodeURIComponent(vehicle.name)} at ${vehicle.price}${vehicle.period}" class="btn btn-primary btn-book" target="_blank">
                        <span>Book Now</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        `;
        vehicleList.appendChild(vehicleCard);
    });
}

// Populate Testimonial Cards
function populateTestimonials() {
    if (!testimonialList) return;
    
    testimonialList.innerHTML = testimonials.map(testimonial => `
        <div class="col-lg-4 col-md-6">
            <div class="testimonial-card animate-fadeInUp">
                <div class="testimonial-header">
                    <div class="testimonial-image">
                        <img src="${testimonial.image}" alt="${testimonial.name}">
                    </div>
                    <div class="testimonial-info">
                        <h5>${testimonial.name}</h5>
                        <p>${testimonial.role}</p>
                    </div>
                </div>
                <div class="testimonial-rating">
                    ${Array(testimonial.rating).fill('★').join('')}
                    ${Array(5 - testimonial.rating).fill('☆').join('')}
                </div>
                <p class="testimonial-text">${testimonial.text}</p>
            </div>
        </div>
    `).join('');
}

// Navbar Scroll Effect
function handleNavbarScroll() {
    if (window.scrollY > 50) {
        navbar.classList.add('navbar-scrolled');
    } else {
        navbar.classList.remove('navbar-scrolled');
    }
}

// Initialize Vehicle Slider
function initVehicleSlider() {
    const slider = document.querySelector('.vehicle-slider');
    if (!slider) return;

    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2;
        slider.scrollLeft = scrollLeft - walk;
    });
}

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        // Add your form validation and submission logic here
        // Form data is ready for submission
        
        // Show success message
        showNotification('Form submitted successfully!', 'success');
    });
}

// Notification System
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Initialize Animations
function initAnimations() {
    const animatedElements = document.querySelectorAll('.animate-fadeInUp');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
            }
        });
    }, { threshold: 0.1 });
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

// Initialize Vehicle Filtering
function initVehicleFiltering() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    if (!filterButtons.length) return;
    
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            button.classList.add('active');
            
            // Get filter value and populate cards
            const filter = button.getAttribute('data-filter');
            populateVehicleCards(filter);
        });
    });
}

// Event Listeners
window.addEventListener('DOMContentLoaded', () => {
    populateVehicleCards();
    populateTestimonials();
    initVehicleSlider();
    initAnimations();
    validateForm('bookingForm');
    validateForm('newsletterForm');
    initVehicleFiltering();
});

window.addEventListener('scroll', handleNavbarScroll);

// Add smooth scrolling to all links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Theme Handler
class ThemeHandler {
    constructor() {
        this.themeToggle = document.querySelector('.theme-toggle');
        this.themeIcon = document.querySelector('.theme-toggle i');
        this.heroSection = document.querySelector('.hero');
    }

    init() {
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            this.heroSection.setAttribute('data-theme', 'dark');
            this.updateThemeIcon('dark');
        } else {
            this.heroSection.setAttribute('data-theme', 'light');
            this.updateThemeIcon('light');
        }
    }

    toggleTheme() {
        const currentTheme = this.heroSection.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        this.heroSection.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        this.updateThemeIcon(newTheme);
    }

    updateThemeIcon(theme) {
        if (theme === 'dark') {
            this.themeIcon.classList.remove('fa-sun');
            this.themeIcon.classList.add('fa-moon');
        } else {
            this.themeIcon.classList.remove('fa-moon');
            this.themeIcon.classList.add('fa-sun');
        }
    }
}

// Initialize theme handler
document.addEventListener('DOMContentLoaded', () => {
    const themeHandler = new ThemeHandler();
    themeHandler.init();
    
    // Add click event listener to theme toggle
    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => themeHandler.toggleTheme());
    }
});

// Contact Form Handler
class ContactFormHandler {
    constructor() {
        this.form = document.getElementById('searchForm');
        this.whatsappButton = document.getElementById('whatsappButton');
        this.bookingSummary = document.getElementById('bookingSummary');
        this.phoneNumber = '919304514974';
        
        this.init();
    }
    
    init() {
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
        
        if (this.whatsappButton) {
            this.whatsappButton.addEventListener('click', () => this.redirectToWhatsApp());
        }
    }
    
    handleFormSubmit(e) {
        e.preventDefault();
        
        const vehicle = document.getElementById('vehicle-select').value;
        const pickup = document.getElementById('pickup-location').value;
        const date = this.form.querySelector('input[type="date"]').value;
        const dropoff = document.getElementById('dropoff-location').value;
        
        if (!this.validateFormData(vehicle, pickup, date, dropoff)) {
            return;
        }
        
        this.updateBookingSummary(vehicle, pickup, date, dropoff);
        this.bookingSummary.style.display = 'block';
        
        // Scroll to booking summary
        this.bookingSummary.scrollIntoView({ behavior: 'smooth' });
    }
    
    validateFormData(vehicle, pickup, date, dropoff) {
        if (!vehicle) {
            alert('Please select a vehicle');
            return false;
        }
        
        if (!pickup) {
            alert('Please select a pickup location');
            return false;
        }
        
        if (!date) {
            alert('Please select a date');
            return false;
        }
        
        if (!dropoff) {
            alert('Please select a drop-off location');
            return false;
        }
        
        return true;
    }
    
    updateBookingSummary(vehicle, pickup, date, dropoff) {
        // Format date
        const formattedDate = new Date(date).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Get vehicle name
        const vehicleSelect = document.getElementById('vehicle-select');
        const vehicleName = vehicleSelect.options[vehicleSelect.selectedIndex].text;
        
        // Get location names
        const pickupSelect = document.getElementById('pickup-location');
        const pickupName = pickupSelect.options[pickupSelect.selectedIndex].text;
        
        const dropoffSelect = document.getElementById('dropoff-location');
        const dropoffName = dropoffSelect.options[dropoffSelect.selectedIndex].text;
        
        // Update summary
        document.getElementById('summaryVehicle').textContent = vehicleName;
        document.getElementById('summaryPickup').textContent = pickupName;
        document.getElementById('summaryDate').textContent = formattedDate;
        document.getElementById('summaryDropoff').textContent = dropoffName;
    }
    
    redirectToWhatsApp() {
        const vehicle = document.getElementById('vehicle-select').value;
        const pickup = document.getElementById('pickup-location').value;
        const date = this.form.querySelector('input[type="date"]').value;
        const dropoff = document.getElementById('dropoff-location').value;
        
        if (!this.validateFormData(vehicle, pickup, date, dropoff)) {
            return;
        }
        
        // Format date
        const formattedDate = new Date(date).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Get vehicle name
        const vehicleSelect = document.getElementById('vehicle-select');
        const vehicleName = vehicleSelect.options[vehicleSelect.selectedIndex].text;
        
        // Get location names
        const pickupSelect = document.getElementById('pickup-location');
        const pickupName = pickupSelect.options[pickupSelect.selectedIndex].text;
        
        const dropoffSelect = document.getElementById('dropoff-location');
        const dropoffName = dropoffSelect.options[dropoffSelect.selectedIndex].text;
        
        // Create message
        const message = `Hello! I would like to book a car rental with CabinPatna with the following details:%0A%0A` +
            `Vehicle: ${vehicleName}%0A` +
            `Pickup: ${pickupName}%0A` +
            `Date: ${formattedDate}%0A` +
            `Drop-off: ${dropoffName}%0A%0A` +
            `Please confirm availability and pricing. Thank you!`;
        
        // Redirect to WhatsApp
        window.open(`https://wa.me/${this.phoneNumber}?text=${message}`, '_blank');
    }
}

// Cookie Consent Handler
class CookieConsentHandler {
    constructor() {
        this.cookieConsent = document.getElementById('cookieConsent');
        this.init();
    }
    
    init() {
        if (this.cookieConsent) {
            // Check if user has already accepted cookies
            if (localStorage.getItem('cookiesAccepted') !== 'true') {
                this.showCookieConsent();
            } else {
                this.hideCookieConsent();
            }
        }
    }
    
    showCookieConsent() {
        if (this.cookieConsent) {
            this.cookieConsent.style.display = 'flex';
        }
    }
    
    hideCookieConsent() {
        if (this.cookieConsent) {
            this.cookieConsent.style.display = 'none';
        }
    }
    
    acceptCookies() {
        localStorage.setItem('cookiesAccepted', 'true');
        this.hideCookieConsent();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize theme handler
    new ThemeHandler();
    
    // Initialize contact form handler
    new ContactFormHandler();
    
    // Initialize cookie consent handler
    new CookieConsentHandler();
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Navbar scroll behavior
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    }
});

// Global function for cookie consent
function acceptCookies() {
    const cookieHandler = new CookieConsentHandler();
    cookieHandler.acceptCookies();
}

// Dark Mode Toggle
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;
    
    // Check for saved theme preference or use system preference
    const savedTheme = localStorage.getItem('cabinpatna-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Set initial theme
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        body.classList.add('dark-mode');
        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    }
    
    // Toggle theme
    themeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        // Update icon and save preference
        if (body.classList.contains('dark-mode')) {
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            localStorage.setItem('cabinpatna-theme', 'dark');
        } else {
            themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            localStorage.setItem('cabinpatna-theme', 'light');
        }
    });
    
    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('cabinpatna-theme')) {
            if (e.matches) {
                body.classList.add('dark-mode');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                body.classList.remove('dark-mode');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
        }
    });
});

// Testimonials Slider
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.testimonials-slider');
    const cards = document.querySelectorAll('.testimonial-card');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.control-btn.prev');
    const nextBtn = document.querySelector('.control-btn.next');
    
    if (!slider || !cards.length) return;
    
    let currentIndex = 0;
    let autoSlideInterval;
    const cardWidth = cards[0].offsetWidth;
    const gap = 32; // 2rem gap between cards
    
    function goToSlide(index) {
        if (index < 0) index = cards.length - 1;
        if (index >= cards.length) index = 0;
        
        currentIndex = index;
        const offset = -(cardWidth + gap) * currentIndex;
        slider.style.transform = `translateX(${offset}px)`;
        
        // Update dots
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentIndex);
        });
    }
    
    function startAutoSlide() {
        if (autoSlideInterval) clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(() => {
            goToSlide(currentIndex + 1);
        }, 5000);
    }
    
    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
            autoSlideInterval = null;
        }
    }
    
    // Event Listeners
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            stopAutoSlide();
            goToSlide(currentIndex - 1);
            startAutoSlide();
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            stopAutoSlide();
            goToSlide(currentIndex + 1);
            startAutoSlide();
        });
    }
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            goToSlide(index);
            startAutoSlide();
        });
    });
    
    // Touch events for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    slider.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
        stopAutoSlide();
    }, { passive: true });
    
    slider.addEventListener('touchmove', (e) => {
        touchEndX = e.touches[0].clientX;
    }, { passive: true });
    
    slider.addEventListener('touchend', () => {
        const difference = touchStartX - touchEndX;
        if (Math.abs(difference) > 50) { // Minimum swipe distance
            if (difference > 0) {
                goToSlide(currentIndex + 1);
            } else {
                goToSlide(currentIndex - 1);
            }
        }
        startAutoSlide();
    });
    
    // Adjust slider for mobile
    function adjustSliderForMobile() {
        const isMobile = window.innerWidth <= 768;
        cards.forEach(card => {
            card.style.flex = isMobile ? '0 0 100%' : '0 0 350px';
        });
        
        // Reset position when switching between mobile and desktop
        goToSlide(currentIndex);
    }
    
    // Initial setup
    adjustSliderForMobile();
    window.addEventListener('resize', adjustSliderForMobile);
    
    // Start automatic sliding
    startAutoSlide();
    
    // Pause auto-sliding when user interacts with the page
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopAutoSlide();
        } else {
            startAutoSlide();
        }
    });
});

// Back to Top Button
const backToTopButton = document.getElementById('backToTop');

window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
        backToTopButton.classList.add('visible');
    } else {
        backToTopButton.classList.remove('visible');
    }
});

backToTopButton.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}); 