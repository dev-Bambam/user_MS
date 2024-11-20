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

use Controllers\RegistrationController;

$router = new Router();

// Register endpoint - POST 
$router->post('/index/register', function () {
    $requestData = $_POST;
    $requestData = json_decode(file_get_contents('php://input'), true);
    $controller = new RegistrationController();
    $response = $controller->register($requestData); 
    echo $response;
});
$router->get('/index/verify-email', function () {
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