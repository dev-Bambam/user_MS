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

    public function __construct($username, $email, $password,$role = 'regular')
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
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);

            return $stmt->execute();
        } catch (\PDOException $e) {
            // Log error message if desired (e.g., using error logging library)
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
