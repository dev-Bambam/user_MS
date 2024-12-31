<?php
ini_set('log_errors', 1);  // Enable error logging
ini_set('error_log', 'php_error.log');  // Specify log file

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Max-Age: 3600');
    http_response_code(200);
    exit();
}
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use routes\Router;
// Load any necessary files, like autoload and JWT utility classes
require_once 'autoload.php';
require_once 'vendor/autoload.php';
// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


use Controllers\RegistrationController;

$router = new Router();

// Register endpoint - POST 
$router->post('/register', function () {
    $requestData = $_POST;
    $requestData = json_decode(file_get_contents('php://input'), true);
    $controller = new RegistrationController();
    $response = $controller->register($requestData); 
    return $response;
});
// Route: POST /api/resend-verification
use Controllers\ResendVerificationController;

$router->post('/api/resend-verification', function () {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'] ?? null;

    if (!$email) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Email is required.']);
        return;
    }

    $controller = new ResendVerificationController();
    echo $controller->resendVerification($email);
});

$router->get('/verify-email', function () {
    $token = $_GET['token'] ?? null;

    if (!$token) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Token is required.']);
        return;
    }

    $controller = new \Controllers\EmailVerificationController();
    echo $controller->verifyEmail($token);
});

// Run the router
$router->run();