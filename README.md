# Premium Car Rental Website

A professional car rental website with an admin panel, booking system, and responsive design.

## Features

- Responsive design using Bootstrap 5
- Admin panel for managing vehicles, bookings, and testimonials
- Secure booking system with email notifications
- Vehicle fleet management
- Customer testimonials
- User authentication and authorization
- Payment integration
- Multi-language support (configurable)

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for PHP dependencies)
- Node.js and npm (for frontend dependencies)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/car-rental-website.git
cd car-rental-website
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install frontend dependencies:
```bash
npm install
```

4. Create a MySQL database and import the schema:
```bash
mysql -u root -p
CREATE DATABASE car_rental;
exit;
mysql -u root -p car_rental < database/schema.sql
```

5. Configure the website:
   - Copy `config.json.example` to `config.json`
   - Update the database credentials and other settings in `config.json`
   - Update the email settings in `config.json` for notifications

6. Set up the web server:
   - Point your web server's document root to the project's public directory
   - Ensure the `images` directory is writable for file uploads
   - Configure URL rewriting (see `.htaccess` for Apache)

7. Create an admin user:
```bash
php scripts/create-admin.php
```

## Directory Structure

```
car-rental-website/
├── index.html              # Homepage
├── css/                    # Stylesheets
├── js/                     # JavaScript files
├── images/                 # Image assets
├── backend/               # PHP backend files
│   ├── config.php         # Configuration
│   ├── db_connect.php     # Database connection
│   ├── booking.php        # Booking handling
│   └── admin/             # Admin panel files
├── fleet.html             # Vehicle fleet page
├── about.html             # About page
├── contact.html           # Contact page
├── faq.html              # FAQ page
├── terms.html            # Terms & conditions
├── privacy.html          # Privacy policy
├── booking.html          # Booking page
└── config.json           # Website configuration
```

## Admin Panel

The admin panel provides the following features:

- Dashboard with statistics
- Vehicle management (add, edit, delete)
- Booking management
- Customer management
- Testimonial management
- Settings configuration

Access the admin panel at: `http://your-domain.com/backend/admin/`

## Security

- All user inputs are sanitized
- Passwords are hashed using bcrypt
- CSRF protection implemented
- SQL injection prevention
- XSS protection
- Secure session handling

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please email support@premiumcarrental.com or create an issue in the repository.

## Credits

- Bootstrap 5 for the frontend framework
- Font Awesome for icons
- jQuery for JavaScript functionality
- PHPMailer for email handling 