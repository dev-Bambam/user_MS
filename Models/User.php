<?php
namespace Models;

use Models\Database;
use PDO;

class User
{
    private $username;
    private $email;
    private $password;
    protected $role;

    public function __construct($username, $email, $password,$role = 'user')
    {
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Saves the user data to the database.
     *
     * @return bool True on success, False on failure
     */
    public function save(): bool
    {
        error_log("Saving user: username=$this->username, email=$this->email, role=$this->role");
        try {
            // Get database connection
            $db = Database::getInstance()->getConnection();
            if (!$db) {
                throw new \Exception("Database connection is null.");
            }

            // Check for duplicates (username and email)
            error_log("Checking for duplicates");
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                // Duplicate entry found
                error_log("Duplicate entry found, aborting insert");
                return false;  // Prevent insert and return false
            }

            // Proceed with inserting the new user
            error_log("Creating new user");
            $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':role', $this->role);

            error_log("Executing insert query");
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log("PDO Error: " . $e->getMessage());
            echo "Error: " . $e->getMessage();
            return false;
        } catch (\Exception $e) {
            error_log("Error: " . $e->getMessage());
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    /**
     * Retrieves a user from the database by their ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return array|null The user data as an associative array, or null if not found.
     */
    public function getUserById($id)
    {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        } catch (\PDOException $e) {
            // Log error message if desired (e.g., using error logging library)
            return null;
        }
    }
    
    /**
     * Updates a user's profile data in the database.
     *
     * @param int $id The ID of the user to update.
     * @param array $data Associative array containing the user data to update (e.g., name, email).
     * @return bool True if the update was successful, False otherwise.
     */
    public function updateUserProfile($id, $data)
    {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (\PDOException $e) {
            // Log error message if desired (e.g., using error logging library)
            return false;
        }
    }
}
