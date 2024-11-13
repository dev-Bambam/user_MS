<?php
namespace Models;
use PDO;
// models/Database.php

/**
 * Database class handles the connection to the MySQL database
 * using the Singleton pattern. This ensures a single shared 
 * database connection instance throughout the application.
 */
class Database
{
    /**
     * The single instance of the Database class
     *
     * @var Database|null
     */
    private static $instance = null;

    /**
     * The PDO connection object to interact with the database
     *
     * @var PDO|null
     */
    private $connection = null;

    /**
     * Private constructor to prevent direct object creation.
     * Initializes the PDO connection with database credentials.
     */
    private function __construct()
    {
        // Database configuration
        $host = 'localhost';         // Database server (e.g., localhost)
        $dbname = 'student_cash';     // Database name
        $username = 'root';       // Database username
        $password = '';   // Database password
        $charset = 'utf8mb4';        // Character set to support UTF-8

        // Data Source Name (DSN) specifies the database type and connection parameters
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        // Options for the PDO connection to handle errors and other settings
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Enable exceptions for errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Fetch results as associative arrays
            PDO::ATTR_PERSISTENT => true                      // Persistent connection for efficiency
        ];

        try {
            // Create a new PDO instance for database connection
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            // Handle any errors that occur during the connection
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Returns the single instance of the Database class.
     * Creates the instance if it doesn't already exist.
     *
     * @return Database The singleton instance of the Database class
     */
    public static function getInstance(): Database
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Provides access to the PDO connection object.
     * This can be used for running queries and interacting with the database.
     *
     * @return PDO The PDO connection object
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Closes the database connection by setting it to null.
     * PDO automatically handles this when the script ends, but it’s here for control.
     */
    public function closeConnection()
    {
        $this->connection = null;
    }
}
