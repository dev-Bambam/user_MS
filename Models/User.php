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
            $stmt = $connection->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $this->username, 'email' => $this->email]);
            
            if ($stmt->fetchColumn() > 0) {
                return false; // Duplicate found, abort insert
            }

            // Insert new user
            $stmt = $connection->prepare("INSERT INTO users (username, email, password, role, first_name, last_name) VALUES (:username, :email, :password, :role, :first_name, :last_name)");
            $stmt->execute([
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'role' => $this->role,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name
            ]);

            return true;
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
     * @param int $userId The ID of the user to retrieve.
     * @return array|null The user data as an associative array, or null if not found.
     */
    public function getUserById(int $userId): ?array
    {
        try {
            $connection = Database::getInstance()->getConnection();
            $query = "SELECT * FROM users WHERE id = :id";
            $statement = $connection->prepare($query);
            $statement->bindParam(':id', $userId);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $exception) {
            error_log("PDO Error: " . $exception->getMessage());
            return null;
        }
    }
    
    /**
     * Update a user's profile data in the database.
     *
     * @param int $id The ID of the user to update.
     * @param array $data Associative array containing the user data to update (e.g., first_name, email).
     * @return bool True if the update was successful, False otherwise.
     */
    public function updateUserProfile($id, $data)
    {
        try {
            $connection = Database::getInstance()->getConnection();
            $query = "UPDATE users SET first_name = :first_name, email = :email WHERE id = :id";
            $statement = $connection->prepare($query);
            $statement->bindParam(':first_name', $data['first_name']);
            $statement->bindParam(':email', $data['email']);
            $statement->bindParam(':id', $id);
            return $statement->execute();
        } catch (\PDOException $exception) {
            error_log("PDO Error: " . $exception->getMessage());
            return false;
        }
    }
}
