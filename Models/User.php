<?php
namespace Models;

use Models\Database;
use PDO;

class User
{
    private $username;
    private $email;
    private $password;

    public function __construct($username, $email, $password)
    {
        $this->username = $username;
        $this->email = $email;
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
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);

            return $stmt->execute();
        } catch (\PDOException $e) {
            // Log error message if desired (e.g., using error logging library)
            return false;
        }
    }
}
