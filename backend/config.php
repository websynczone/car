<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'car_rental');

// Application configuration
define('SITE_NAME', 'Premium Car Rental');
define('SITE_URL', 'http://localhost/car-rental-website');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
session_start();

// Time zone
date_default_timezone_set('UTC');

// Constants for booking status
define('BOOKING_STATUS_PENDING', 'pending');
define('BOOKING_STATUS_CONFIRMED', 'confirmed');
define('BOOKING_STATUS_CANCELLED', 'cancelled');
define('BOOKING_STATUS_COMPLETED', 'completed');

// Constants for user roles
define('USER_ROLE_ADMIN', 'admin');
define('USER_ROLE_CUSTOMER', 'customer');

// Constants for vehicle status
define('VEHICLE_STATUS_AVAILABLE', 'available');
define('VEHICLE_STATUS_RENTED', 'rented');
define('VEHICLE_STATUS_MAINTENANCE', 'maintenance'); 