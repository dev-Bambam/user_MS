<!-- <?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use routes\Router;
// Load any necessary files, like autoload and JWT utility classes
require_once 'autoload.php';
require_once __DIR__ . '/../vendor/autoload.php'; 

use Controllers\RegistrationController;
use Controllers\LoginController;
use Controllers\ProfileController;
use Middleware\AuthMiddleware;
use Middleware\LoggingMiddleware;

// Assuming the router instance is already created
$router = new Router();

/// Register endpoint - POST
$router->post('/api/register', function () {
    $controller = new RegistrationController();
    $controller->register($_POST);
    error_log("POST /api/register called.");
    echo json_encode(['status' => 'success', 'message' => 'Request received.']);
}); // Removed [LoggingMiddleware::class] 

// Login endpoint - POST
$router->post('/api/login', function () {
    $controller = new LoginController();
    $controller->login($_POST);
}); // Removed [LoggingMiddleware::class]

// // Get User Profile endpoint - GET
$router->get('/api/user/profile', function () {
    $controller = new ProfileController();
    $controller->getProfile();
}); // Removed [AuthMiddleware::class, LoggingMiddleware::class]

// // Update User Profile endpoint - PUT
$router->put('/api/user/profile', function () {
    $putData = json_decode(file_get_contents('php://input'), true);
    $controller = new ProfileController();
    $controller->updateProfile($putData);
}); // Removed [AuthMiddleware::class, LoggingMiddleware::class]

// Run the router
$router->post('/api/register', function () {
    echo json_encode(['status' => 'success', 'message' => 'Request received!']);
});
// $router->run(); -->
