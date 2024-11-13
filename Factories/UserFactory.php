<?php
namespace Factories;

use Models\User;

class UserFactory
{
    /**
     * Creates a new user instance based on the provided type.
     *
     * @param string $type User type (e.g., 'regular', 'admin')
     * @param array $data Associative array containing user data
     * @return User|null Returns an instance of User or null if type is invalid
     */
    public static function createUser(string $type, array $data): ?User
    {
        return match ($type) {
            'regular' => new User($data['username'], $data['email'], $data['password']),
            default => null,
        };
    }
}
