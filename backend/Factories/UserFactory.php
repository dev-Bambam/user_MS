<?php
namespace Factories;

use Models\User;
use Models\AdminUser;

class UserFactory
{
    /**
     * Create a user object of the given type, using the supplied data.
     *
     * @param array $data Associative array containing user data.
     * @param string $type The type of user to create. Valid types are 'user', and 'admin'.
     * @return User|null The created user, or null if the given type is invalid.
     */
    public static function createUser(array $data): User
    {
        // Check if role is provided in data and decide which class to instantiate
        $role = $data['role'] ?? 'user';  // Default role is 'user'
        
        // If role is admin, create AdminUser, otherwise create User
        if ($role === 'admin') {
            return new AdminUser(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['first_name'],
                $data['last_name'],
                $role
            );
        }

        // Default to User
        return new User(
            $data['username'],
            $data['email'],
            $data['password'],
            $data['first_name'],
            $data['last_name']
        ); 
    }
}
