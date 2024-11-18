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
    public static function createUser(array $data, $type = 'user'): ?User
    {
        return match ($type) {
            'user' => new User($data['username'], $data['email'], $data['password'], $data['first_name'], $data['last_name']),
            'admin' => new AdminUser(
                $data['username'],
                $data['email'],
                $data['password'],
                $data['first_name'],
                $data['last_name'],
                $data['role'] ?? 'user' // default role is 'user'
            ),
            default => "Invalid user type",
        };
    }

}
