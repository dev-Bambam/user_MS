<?php
use routes\Router;
// Load any necessary files, like autoload and JWT utility classes
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\RegistrationController;
use Controllers\LoginController;
use Controllers\ProfileController;

// Assuming the router instance is already created
$router = new Router();

// Register endpoint - POST
$router->post('/api/register', function () {
    $controller = new RegistrationController();
    $controller->register($_POST);
});

// Login endpoint - POST
$router->post('/api/login', function () {
    $controller = new LoginController();
    $controller->login($_POST);
});

// Get User Profile endpoint - GET
$router->get('/api/user/profile', function () {
    $controller = new ProfileController();
    $controller->getProfile();
});

// Update User Profile endpoint - PUT
// Update User Profile endpoint - PUT
$router->put('/api/user/profile', function () {
    $putData = json_decode(file_get_contents('php://input'), true);
    $controller = new ProfileController();
    $controller->updateProfile($putData);
});

// Run the router
$router->run();
