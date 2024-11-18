<?php
namespace Models;

use Models\Database;
use PDO;

class User
{   private $first_name;
    private $last_name;
    private $username;
    private $email;
    private $password;
    protected $role;

    public function __construct($username, $email, $password, $first_name, $last_name, $role = 'user')
    {
        $this->username = $username;
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->role = $role;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }



    /**
     * Save the user to the database.
     * 
     * Checks for duplicate username or email before inserting the user.
     * 
     * @return bool True if the user was inserted successfully, False if a duplicate was found or an error occurred.
     */
    public function save(): bool
    {
        try {
            $connection = Database::getInstance()->getConnection();
            if (!$connection) {
                throw new \Exception("Database connection is null.");
            }

            // Check for duplicate username or email
            $duplicateCheckQuery = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
            $duplicateCheckStmt = $connection->prepare($duplicateCheckQuery);
            $duplicateCheckStmt->bindParam(':username', $this->username);
            $duplicateCheckStmt->bindParam(':email', $this->email);
            $duplicateCheckStmt->execute();
            
            if ($duplicateCheckStmt->fetchColumn() > 0) {
                return false; // Duplicate found, abort insert
            }

            // Insert new user
            $insertQuery = "INSERT INTO users (username, email, password, role, first_name, last_name) VALUES (:username, :email, :password, :role, :first_name, :last_name)"; 
            $insertStmt = $connection->prepare($insertQuery);
            $insertStmt->bindParam(':username', $this->username);
            $insertStmt->bindParam(':email', $this->email);
            $insertStmt->bindParam(':password', $this->password);
            $insertStmt->bindParam(':role', $this->role);
            $insertStmt->bindParam(':first_name', $this->first_name);
            $insertStmt->bindParam(':last_name', $this->last_name);

            return $insertStmt->execute();
        } catch (\PDOException $pdoException) {
            error_log("PDO Error: " . $pdoException->getMessage());
            return false;
        } catch (\Exception $exception) {
            error_log("Error: " . $exception->getMessage());
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
