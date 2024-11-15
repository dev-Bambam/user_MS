<?php
namespace Factories;

use Models\User;
use Models\AdminUser;

class UserFactory
{
    /**
     * Creates a new user instance based on the provided type.
     *
     * @param string $type User type (e.g., 'regular', 'admin')
     * @param array $data Associative array containing user data
     * @return User|null Returns an instance of User or null if type is invalid
     */
    public static function createUser(array $data, $type = 'regular'): ?User
    {
        return match ($type) {
            'regular' => new User($data['username'], $data['email'], $data['password']),
            'admin' => new AdminUser($data['username'], $data['email'], $data['password'], $data['role']),
            default => null,
        };
    }
}
