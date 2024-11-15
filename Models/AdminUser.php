<?php
namespace Models;
use Models\User;
// Models/AdminUser.php
class AdminUser extends User
{
    private $adminPrivileges;

    public function __construct($username, $email, $password, $role)
    {
        parent::__construct($username, $email, $password);
        $this->role = $role;    
        $this->adminPrivileges = true;
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