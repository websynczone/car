<?php
/**
 * Database Configuration
 * 
 * This file contains the database connection settings.
 * Make sure to update these values according to your environment.
 */

return [
    'host' => 'localhost',
    'database' => 'car_rental',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
]; 