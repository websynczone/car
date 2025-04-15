// Booking reference generator
class BookingReferenceGenerator {
    constructor() {
        this.prefix = 'CAB';
        this.lastTimestamp = 0;
        this.sequence = 0;
    }

    generate() {
        const timestamp = Date.now();
        
        // Reset sequence if it's a new second
        if (timestamp !== this.lastTimestamp) {
            this.sequence = 0;
            this.lastTimestamp = timestamp;
        }
        
        // Increment sequence
        this.sequence++;
        
        // Create reference number: CAB + YY + MM + DD + 4-digit sequence
        const date = new Date(timestamp);
        const year = date.getFullYear().toString().slice(-2);
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        const sequence = this.sequence.toString().padStart(4, '0');
        
        return `${this.prefix}${year}${month}${day}${sequence}`;
    }
}

// Booking handler
class BookingHandler {
    constructor() {
        this.referenceGenerator = new BookingReferenceGenerator();
        this.whatsappNumber = '919304514974';
        this.searchData = null;
        this.initializeForms();
        this.setupDateValidation();
    }

    initializeForms() {
        const searchForm = document.getElementById('searchForm');
        const whatsappButton = document.getElementById('whatsappButton');

        if (searchForm) {
            searchForm.addEventListener('submit', (e) => this.handleSearch(e));
        }

        if (whatsappButton) {
            whatsappButton.addEventListener('click', () => this.handleWhatsAppBooking());
        }
    }

    setupDateValidation() {
        const dateInput = document.querySelector('input[type="date"]');
        if (dateInput) {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;

            // Set maximum date to 3 months from today
            const maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 3);
            dateInput.max = maxDate.toISOString().split('T')[0];
        }
    }

    handleSearch(e) {
        e.preventDefault();
        
        // Get form values
        const vehicle = document.getElementById('vehicle-select').value;
        const pickup = document.getElementById('pickup-location').value;
        const date = document.querySelector('input[type="date"]').value;
        const dropoff = document.getElementById('dropoff-location').value;

        // Store the search data for later use
        this.searchData = {
            vehicle,
            pickup,
            date,
            dropoff
        };

        // Update booking summary with animation
        this.updateBookingSummary();

        // Show booking summary with animation
        this.showBookingSummary();
    }

    updateBookingSummary() {
        const elements = {
            vehicle: document.getElementById('summaryVehicle'),
            pickup: document.getElementById('summaryPickup'),
            date: document.getElementById('summaryDate'),
            dropoff: document.getElementById('summaryDropoff')
        };

        // Update each element with animation
        Object.entries(elements).forEach(([key, element]) => {
            if (element) {
                element.style.opacity = '0';
                element.textContent = this.searchData[key];
                setTimeout(() => {
                    element.style.opacity = '1';
                }, 100);
            }
        });
    }

    showBookingSummary() {
        const searchForm = document.getElementById('searchForm');
        const bookingSummary = document.getElementById('bookingSummary');

        if (searchForm && bookingSummary) {
            searchForm.style.opacity = '0';
            setTimeout(() => {
                bookingSummary.style.display = 'block';
                setTimeout(() => {
                    bookingSummary.style.opacity = '1';
                }, 50);
            }, 300);
        }
    }

    handleWhatsAppBooking() {
        if (!this.searchData) return;

        // Generate booking reference
        const bookingData = {
            ...this.searchData,
            reference: this.referenceGenerator.generate()
        };

        // Send to WhatsApp
        this.sendToWhatsApp(bookingData);
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-IN', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    sendToWhatsApp(bookingData) {
        // Format the phone number correctly
        const formattedPhone = this.whatsappNumber.replace(/\D/g, '');
        
        // Create WhatsApp message with proper formatting
        const message = 
            `🚗 *NEW BOOKING REQUEST* 🚗\n\n` +
            `🚘 *Journey Details*\n` +
            `━━━━━━━━━━━━━━━\n` +
            `*Vehicle:* ${bookingData.vehicle}\n` +
            `*Pickup Location:* ${bookingData.pickup}\n` +
            `*Journey Date:* ${this.formatDate(bookingData.date)}\n` +
            `*Drop-off Location:* ${bookingData.dropoff}\n\n` +
            `📝 *Booking Reference:* ${bookingData.reference}\n` +
            `━━━━━━━━━━━━━━━\n\n` +
            `Thank you for choosing our service! 🙏\n` +
            `Our team will contact you shortly to confirm your booking.`;

        // Create and trigger WhatsApp link
        const whatsappLink = document.createElement('a');
        whatsappLink.href = `https://api.whatsapp.com/send?phone=${formattedPhone}&text=${encodeURIComponent(message)}`;
        whatsappLink.target = '_blank';
        whatsappLink.rel = 'noopener noreferrer';
        
        document.body.appendChild(whatsappLink);
        whatsappLink.click();
        setTimeout(() => {
            document.body.removeChild(whatsappLink);
        }, 100);
    }
}

// Initialize booking handler when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the booking system
    const bookingSystem = new BookingSystem();
    bookingSystem.init();

    // Initialize date range picker
    $('#journey-date').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'MM/DD/YYYY'
        }
    });

    // Handle date range picker events
    $('#journey-date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('#journey-date').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // Form submission handler
    const bookingForm = document.getElementById('bookingSearchForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const vehicle = document.getElementById('vehicle-select').value;
            const pickup = document.getElementById('pickup-location').value;
            const dropoff = document.getElementById('dropoff-location').value;
            const date = document.getElementById('journey-date').value;

            // Validate form
            if (!vehicle || !pickup || !dropoff || !date) {
                showNotification('Please fill in all fields', 'error');
                return;
            }

            // Show loading state
            const submitButton = this.querySelector('.btn-search');
            submitButton.classList.add('loading');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';

            // Simulate API call
            setTimeout(() => {
                // Reset button state
                submitButton.classList.remove('loading');
                submitButton.innerHTML = '<i class="fas fa-search"></i> Search';

                // Show success message
                showNotification('Search completed successfully', 'success');

                // Here you would typically handle the search results
                // Search data is ready for processing
                // vehicle, pickup, dropoff, date variables contain the form data
            }, 1500);
        });
    }

    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    function getNotificationIcon(type) {
        switch (type) {
            case 'success':
                return 'fa-check-circle';
            case 'error':
                return 'fa-exclamation-circle';
            case 'warning':
                return 'fa-exclamation-triangle';
            default:
                return 'fa-info-circle';
        }
    }

    // Add form field animations
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        control.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});

class BookingSystem {
    constructor() {
        // Form elements
        this.form = document.getElementById('sylhetBookingForm');
        this.vehicleSelect = document.getElementById('sylhet-vehicle-select');
        this.passengersSelect = document.getElementById('sylhet-passengers');
        this.pickupLocation = document.getElementById('sylhet-pickup-location');
        this.dropoffLocation = document.getElementById('sylhet-dropoff-location');
        this.pickupDate = document.getElementById('sylhet-pickup-date');
        this.returnDate = document.getElementById('sylhet-return-date');
        this.returnDateGroup = document.querySelector('.return-date-group');
        
        // Search elements
        this.pickupSearch = document.getElementById('pickup-search');
        this.dropoffSearch = document.getElementById('dropoff-search');
        this.pickupResults = document.getElementById('pickup-results');
        this.dropoffResults = document.getElementById('dropoff-results');
        
        // Results elements
        this.resultsContainer = document.getElementById('bookingResults');
        this.availableCars = document.getElementById('available-cars');
        this.modifySearchBtn = document.getElementById('modifySearch');
        
        // Tab elements
        this.tabs = document.querySelectorAll('.booking-tab');
        
        // Vehicle data
        this.vehicles = [
            {
                id: 'toyota-premio',
                name: 'Toyota Premio',
                image: 'images/vehicles/toyota-premio.jpg',
                price: '₹2,500',
                period: 'per day',
                features: ['4 Seats', 'AC', 'Music System', 'Bluetooth'],
                badge: 'Popular'
            },
            {
                id: 'toyota-allion',
                name: 'Toyota Allion',
                image: 'images/vehicles/toyota-allion.jpg',
                price: '₹2,800',
                period: 'per day',
                features: ['4 Seats', 'AC', 'Music System', 'Bluetooth', 'GPS'],
                badge: 'Premium'
            },
            {
                id: 'honda-city',
                name: 'Honda City',
                image: 'images/vehicles/honda-city.jpg',
                price: '₹2,200',
                period: 'per day',
                features: ['4 Seats', 'AC', 'Music System'],
                badge: 'Economy'
            },
            {
                id: 'toyota-noah',
                name: 'Toyota Noah',
                image: 'images/vehicles/toyota-noah.jpg',
                price: '₹3,500',
                period: 'per day',
                features: ['7 Seats', 'AC', 'Music System', 'Bluetooth', 'GPS'],
                badge: 'Family'
            },
            {
                id: 'toyota-hiace',
                name: 'Toyota Hiace',
                image: 'images/vehicles/toyota-hiace.jpg',
                price: '₹4,000',
                period: 'per day',
                features: ['12 Seats', 'AC', 'Music System', 'Bluetooth', 'GPS'],
                badge: 'Group'
            },
            {
                id: 'micro-bus',
                name: 'Micro Bus',
                image: 'images/vehicles/micro-bus.jpg',
                price: '₹5,000',
                period: 'per day',
                features: ['20 Seats', 'AC', 'Music System', 'Bluetooth', 'GPS'],
                badge: 'Large Group'
            }
        ];
        
        // Location data
        this.locations = [
            { id: 'sylhet-airport', name: 'Osmani International Airport', icon: 'fa-plane' },
            { id: 'sylhet-railway', name: 'Sylhet Railway Station', icon: 'fa-train' },
            { id: 'sylhet-bus-stand', name: 'Kadamtali Bus Stand', icon: 'fa-bus' },
            { id: 'sylhet-city', name: 'Sylhet City', icon: 'fa-city' },
            { id: 'ratargul', name: 'Ratargul Swamp Forest', icon: 'fa-tree' },
            { id: 'bisnakandi', name: 'Bisnakandi', icon: 'fa-mountain' },
            { id: 'lalakhal', name: 'Lalakhal Canal', icon: 'fa-water' },
            { id: 'custom', name: 'Custom Location', icon: 'fa-map-marker-alt' }
        ];
    }
    
    init() {
        this.setMinDates();
        this.setupEventListeners();
    }
    
    setMinDates() {
        // Set minimum date to today
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        this.pickupDate.min = formattedDate;
        this.returnDate.min = formattedDate;
    }
    
    setupEventListeners() {
        // Form submission
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (this.validateForm()) {
                this.showResults();
            }
        });
        
        // Location search
        this.pickupSearch.addEventListener('input', () => {
            this.searchLocations(this.pickupSearch.value, this.pickupResults);
        });
        
        this.dropoffSearch.addEventListener('input', () => {
            this.searchLocations(this.dropoffSearch.value, this.dropoffResults);
        });
        
        // Modify search button
        this.modifySearchBtn.addEventListener('click', () => {
            this.hideResults();
        });
        
        // Tab switching
        this.tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                this.switchTab(tab);
            });
        });
        
        // Pickup date change
        this.pickupDate.addEventListener('change', () => {
            this.returnDate.min = this.pickupDate.value;
            if (this.returnDate.value && this.returnDate.value < this.pickupDate.value) {
                this.returnDate.value = this.pickupDate.value;
            }
        });
    }
    
    searchLocations(query, resultsContainer) {
        if (!query) {
            resultsContainer.style.display = 'none';
            return;
        }
        
        const filteredLocations = this.locations.filter(location => 
            location.name.toLowerCase().includes(query.toLowerCase())
        );
        
        if (filteredLocations.length > 0) {
            resultsContainer.innerHTML = filteredLocations.map(location => `
                <div class="location-result" data-id="${location.id}">
                    <i class="fas ${location.icon}"></i>
                    <span>${location.name}</span>
                </div>
            `).join('');
            
            resultsContainer.style.display = 'block';
            
            // Add click event to results
            const resultItems = resultsContainer.querySelectorAll('.location-result');
            resultItems.forEach(item => {
                item.addEventListener('click', () => {
                    const locationId = item.getAttribute('data-id');
                    const locationName = item.querySelector('span').textContent;
                    
                    if (resultsContainer === this.pickupResults) {
                        this.pickupLocation.value = locationId;
                        this.pickupSearch.value = locationName;
                    } else {
                        this.dropoffLocation.value = locationId;
                        this.dropoffSearch.value = locationName;
                    }
                    
                    resultsContainer.style.display = 'none';
                });
            });
        } else {
            resultsContainer.innerHTML = '<div class="location-result">No locations found</div>';
            resultsContainer.style.display = 'block';
        }
    }
    
    switchTab(tab) {
        // Remove active class from all tabs
        this.tabs.forEach(t => t.classList.remove('active'));
        
        // Add active class to clicked tab
        tab.classList.add('active');
        
        // Show/hide return date based on tab
        const tabType = tab.getAttribute('data-tab');
        if (tabType === 'round-trip') {
            this.returnDateGroup.style.display = 'block';
            this.returnDate.required = true;
        } else {
            this.returnDateGroup.style.display = 'none';
            this.returnDate.required = false;
        }
    }
    
    validateForm() {
        // Basic validation
        if (!this.vehicleSelect.value) {
            this.showNotification('Please select a vehicle', 'error');
            return false;
        }
        
        if (!this.pickupLocation.value) {
            this.showNotification('Please select a pickup location', 'error');
            return false;
        }
        
        if (!this.dropoffLocation.value) {
            this.showNotification('Please select a drop-off location', 'error');
            return false;
        }
        
        if (!this.pickupDate.value) {
            this.showNotification('Please select a pickup date', 'error');
            return false;
        }
        
        const activeTab = document.querySelector('.booking-tab.active');
        if (activeTab.getAttribute('data-tab') === 'round-trip' && !this.returnDate.value) {
            this.showNotification('Please select a return date', 'error');
            return false;
        }
        
        return true;
    }
    
    showResults() {
        // Hide form and show results
        this.form.style.display = 'none';
        this.resultsContainer.style.display = 'block';
        
        // Populate available cars
        this.populateAvailableCars();
    }
    
    hideResults() {
        // Hide results and show form
        this.resultsContainer.style.display = 'none';
        this.form.style.display = 'block';
    }
    
    populateAvailableCars() {
        // Clear previous results
        this.availableCars.innerHTML = '';
        
        // Get selected vehicle type
        const selectedVehicleType = this.vehicleSelect.value;
        
        // Filter vehicles based on selection
        const filteredVehicles = selectedVehicleType 
            ? this.vehicles.filter(vehicle => vehicle.id === selectedVehicleType)
            : this.vehicles;
        
        // Create car cards
        filteredVehicles.forEach(vehicle => {
            const carCard = document.createElement('div');
            carCard.className = 'car-card';
            carCard.innerHTML = `
                <div class="car-image">
                    <img src="${vehicle.image}" alt="${vehicle.name}">
                    <div class="car-badge">${vehicle.badge}</div>
                </div>
                <div class="car-details">
                    <h4 class="car-name">${vehicle.name}</h4>
                    <div class="car-features">
                        ${vehicle.features.map(feature => `
                            <span class="car-feature">
                                <i class="fas fa-check"></i> ${feature}
                            </span>
                        `).join('')}
                    </div>
                    <div class="car-price">
                        <span class="price-amount">${vehicle.price}</span>
                        <span class="price-period">${vehicle.period}</span>
                    </div>
                    <div class="car-actions">
                        <button class="btn btn-primary book-now" data-id="${vehicle.id}">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </button>
                        <button class="btn btn-outline-primary view-details" data-id="${vehicle.id}">
                            <i class="fas fa-info-circle"></i> View Details
                        </button>
                    </div>
                </div>
            `;
            
            this.availableCars.appendChild(carCard);
        });
        
        // Add event listeners to buttons
        const bookButtons = this.availableCars.querySelectorAll('.book-now');
        bookButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.bookVehicle(button.getAttribute('data-id'));
            });
        });
        
        const detailButtons = this.availableCars.querySelectorAll('.view-details');
        detailButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.viewVehicleDetails(button.getAttribute('data-id'));
            });
        });
    }
    
    bookVehicle(vehicleId) {
        const vehicle = this.vehicles.find(v => v.id === vehicleId);
        if (vehicle) {
            this.showNotification(`Booking initiated for ${vehicle.name}`, 'success');
            // Here you would typically redirect to a booking confirmation page
            // or open a modal with booking details
        }
    }
    
    viewVehicleDetails(vehicleId) {
        const vehicle = this.vehicles.find(v => v.id === vehicleId);
        if (vehicle) {
            this.showNotification(`Viewing details for ${vehicle.name}`, 'info');
            // Here you would typically open a modal with vehicle details
        }
    }
    
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add to document
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Remove notification after delay
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    getNotificationIcon(type) {
        switch (type) {
            case 'success':
                return 'fa-check-circle';
            case 'error':
                return 'fa-exclamation-circle';
            case 'warning':
                return 'fa-exclamation-triangle';
            default:
                return 'fa-info-circle';
        }
    }
} 