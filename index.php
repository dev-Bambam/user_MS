<?php
require_once 'autoload.php';

use Controllers\RegistrationController;
$controller = new RegistrationController();

// Example registration attempts
$testCases = [
    ['username' => 'new_user', 'email' => 'invalid_email', 'password' => '123'], // Invalid email
    ['username' => 'new_user', 'email' => 'test@example.com', 'password' => '123'], // Weak password
    ['username' => 'user', 'email' => 'test@example.com', 'password' => 'securepassword'], // Valid
];

foreach ($testCases as $data) {
    $response = $controller->register($data);
    echo json_encode($response) . PHP_EOL;
}
