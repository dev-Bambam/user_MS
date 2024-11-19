<?php
namespace Models;
use Models\User;
// Models/AdminUser.php
class AdminUser extends User
{
    private $adminPrivileges;

    /**
     * Construct a new AdminUser object.
     *
     * @param string $username Username (unique identifier)
     * @param string $email Email address
     * @param string $password Password
     * @param string $firstName First name
     * @param string $lastName Last name
     * @param string $role Role (should be 'admin', default is 'admin')
     */
    public function __construct(string $username, string $email, string $password, string $firstName, string $lastName, string $role = 'admin')
    {
        parent::__construct($username, $email, $password, $firstName, $lastName, $role);
        $this->adminPrivileges = true; // Indicate that this is an admin user
    }

    // Additional methods specific to admin users
    public function grantPrivileges()
    {
        // Code to grant privileges to other users
    }

    public function revokePrivileges()
    {
        // Code to revoke privileges from other users
    }

    public function manageUsers()
    {
        // Code to manage users (e.g., view, edit, delete)
    }

    // Override any methods from the User class if needed
    public function save(): bool
    {
        // Additional logic for saving admin users
        return parent::save();
    }
}
