<?php
namespace Models;
use PDO;
use Dotenv\Dotenv;

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
        // Load environment variables from the .env file
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..'); // Adjust the path if necessary
        $dotenv->load();

        // Get the configuration from the config/database.php file
        $config = require __DIR__ . '/../config/database.php';

        // Data Source Name (DSN) specifies the database type and connection parameters
        $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

        // Options for the PDO connection to handle errors and other settings
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Enable exceptions for errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Fetch results as associative arrays
            PDO::ATTR_PERSISTENT => true                      // Persistent connection for efficiency
        ];

        try {
            // Create a new PDO instance for database connection
            $this->connection = new PDO($dsn, $config['username'], $config['password'], $options);
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
