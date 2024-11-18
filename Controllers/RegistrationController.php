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
     * Registers a new user based on provided data.
     *
     * @param array $requestData Array of user data (e.g., username, email, password)
     * @return string Response JSON with success or error message
     */
    public function register(array $requestData): String
    {
        // Step 1: Validate input data
        if ($errors = $this->validateData($requestData)) { 
            http_response_code(400);
            return json_encode(['status' => 'error', 'errors' => $errors]);
        }  

        // Step 2: Check for existing email or username
        // $db = Database::getInstance()->getConnection();
        // if ($this->isDuplicateUser($requestData['email'], $requestData['username'], $db)) {
        //     return json_encode(['status' => 'error', 'message' => 'Username or email already in use.']);
        // }
        
        // Step 3: Create and save user instance using the Factory pattern
        $user = UserFactory::createUser( $requestData);
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
    /**
     * Checks if a user with the given email or username already exists.
     *
     * @param string $email User's email to check
     * @param string $username User's username to check
     * @param \PDO $db The database connection
     * @return bool True if duplicate exists, false otherwise
     */ 
    private function isDuplicateUser(string $email, string $username, $db): bool
    {
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email OR username = :username");
        $stmt->execute([':email' => $email, ':username' => $username]);
        return $stmt->fetchColumn() > 0;
    }
}
