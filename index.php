<?php
require_once 'autoload.php';
require_once 'vendor/autoload.php';


use Controllers\LoginController;

$loginController = new LoginController();

// Test login attempt with email and password
$loginData = [
    'email' => 'test@example.com', // Replace with a registered user's email
    'password' => 'securepassword' // Replace with the correct password for that user
];

$response = $loginController->login($loginData);
echo json_encode($response) . PHP_EOL;
