<?php
declare(strict_types=1);

// Copy to config.php and adjust for your local MySQL installation.
// config.php is excluded from Git so local database credentials are not shared.
return [
    'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=contact_manager;charset=utf8mb4',
    'user' => 'root',
    'password' => '', // XAMPP default; change if your MySQL installation uses a password.
];
