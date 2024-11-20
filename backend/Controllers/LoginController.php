<?php
namespace Controllers;

use Models\User;
use Models\Database;
use Utils\JWTUtility;

class LoginController
{
    /**
     * Authenticates a user based on provided email and password.
     *
     * @param array $requestData User credentials (email, password)
     * @return array Response array with success, error messages, and optional token
     */
    public function login(array $requestData): array
    {
        // Validate email and password input
        if (empty($requestData['email']) || empty($requestData['password'])) {
            return ['status' => 'error', 'message' => 'Email and password are required.'];
        }

        // Attempt to fetch user by email
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $requestData['email']);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Check if user exists and password matches
        if ($user && password_verify($requestData['password'], $user['password'])) {
            // Generate JWT token
            $token = JWTUtility::generateToken(['userId' => $user['id']]);

            return [
                'status' => 'success',
                'message' => 'Login successful.',
                'token' => $token
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Invalid email or password.'
            ];
        }
    }
}
