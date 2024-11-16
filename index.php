<?php
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
    $controller->register($requestData);
    // echo json_encode(['status' => 'success', 'message' => 'Request received.']);
}) ;

// Run the router
$router->run();