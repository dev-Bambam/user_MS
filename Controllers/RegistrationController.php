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
     * Validates user registration data.
     *
     * @param array $data Associative array containing user registration data
     * @return array Associative array containing validation errors, if any
     */
    private function validateData(array $data): array
    {
        $errors = [];

        $errors = array_filter([
            'username' => empty($data['username']) || strlen($data['username']) < 3
                ? 'Username must be at least 3 characters long.'
                : null,
            'first_name' => empty($data['first_name'])
                ? 'First name is required.'
                : null,
            'last_name' => empty($data['last_name'])
                ? 'Last name is required.'
                : null,
            'email' => empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
                ? 'Email is required and must be a valid email address.'
                : null,
            'password' => empty($data['password']) || strlen($data['password']) < 6
                ? 'Password must be at least 6 characters long.'
                : null,
        ]);

        return $errors;
    }

}
