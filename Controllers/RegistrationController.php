<?php 
namespace Controllers;
use Models\User;
use Factories\UserFactory;
use Models\Database;

/**
 * RegistrationController handles user registration, including validation and response generation.
 */
class RegistrationController
{
    /**
     * Handles user registration, including validation and response generation.
     *
     * @param array $requestData Associative array containing user registration data
     * @return string JSON response containing registration result (success or error)
     */
    public function register(array $requestData): string
    {
        // Step 1: Validate input data (including first and last name)
        if ($errors = $this->validateData($requestData)) {
            http_response_code(400);
            return json_encode(['status' => 'error', 'errors' => $errors]);
        }

        // Step 2: Create and save user instance using the Factory pattern
        $user = UserFactory::createUser($requestData);
        $response = $user->save()
            ? ['status' => 'success', 'message' => 'User registered successfully.']
            : ['status' => 'error', 'message' => 'User registration failed.'];
        return json_encode($response);
    }

    /**
     * Validates registration data.
     *
     * @param array $data User data to validate
     * @return array List of validation errors, empty if none found
     */
    private function validateData(array $data): array
    {
        $errors = [];

        if (strlen($data['username'] ?? '') < 3) {
            $errors['username'] = 'Username must be at least 3 characters long.';
        }

        if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is required and must be a valid email address.';
        }

        if (strlen($data['password'] ?? '') < 6) {
            $errors['password'] = 'Password must be at least 6 characters long.';
        }

        return $errors;
    }

}
