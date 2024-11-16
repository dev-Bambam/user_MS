<?php
namespace Factories;

use Models\User;
use Models\AdminUser;

class UserFactory
{
    /**
     * Creates a new user instance based on the provided type.
     *
     * @param string $type User type (e.g., 'user', 'admin')
     * @param array $data Associative array containing user data
     * @return User|null Returns an instance of User or null if type is invalid
     */
    public static function createUser(array $data, $type = 'user'): ?User
    {
        return match ($type) {
            'user' => new User($data['username'], $data['email'], $data['password']),
            'admin' => new AdminUser($data['username'], $data['email'], $data['password'], $data['role']),
            default =>  "Invalid user type",
        };
    }
}
