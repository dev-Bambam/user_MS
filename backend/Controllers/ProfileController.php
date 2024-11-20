<?php

namespace Controllers;

use Models\User;
use Utils\JWTUtility;

class ProfileController
{
    // Method to fetch the current user's profile
    public function getProfile()
    {
        // Get JWT token from the Authorization header
        $jwt = JWTUtility::getJWTFromHeader();

        if (!$jwt) {
            return $this->sendResponse(["error" => "Unauthorized"], 401);
        }

        try {
            // Decode the JWT token to get user ID
            $decodedToken = JWTUtility::decodeToken($jwt);
            $userId = $decodedToken->userId;

            if (!$userId) {
                return $this->sendResponse(["error" => "Invalid token"], 401);
            }

            // Fetch user data from the database
            $user = new User('', '', '','', '');
            $userData = $user->getUserById($userId);

            if (!$userData) {
                return $this->sendResponse(["error" => "User not found"], 404);
            }

            // Return the profile data
            return $this->sendResponse($userData, 200);
        } catch (\Exception $e) {
            // Log error message if desired (e.g., using error logging library)
            return $this->sendResponse(["error" => "Internal Server Error"], 500);
        }
    }

    // Method to update the user's profile
    public function updateProfile($data)
    {
        // Validate incoming data (e.g., name, email)
        $errors = $this->validateProfileData($data);

        if (!empty($errors)) {
            return $this->sendResponse(["errors" => $errors], 400);
        }

        // Get JWT token from the Authorization header
        $jwt = JWTUtility::getJWTFromHeader();

        if (!$jwt) {
            return $this->sendResponse(["error" => "Unauthorized"], 401);
        }

        // Decode the JWT token to get user ID
        $userId = JWTUtility::decodeToken($jwt);

        if (!$userId) {
            return $this->sendResponse(["error" => "Invalid token"], 401);
        }

        // Update the user's profile data
        $user = new User(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['firstName'],
            $data['lastName']
        );
        $decodedToken = JWTUtility::decodeToken($jwt);
        $userId = $decodedToken->userId;
        $updateResult = $user->updateUserProfile($userId, $data);

        if ($updateResult) {
            return $this->sendResponse(["message" => "Profile updated successfully"], 200);
        } else {
            return $this->sendResponse(["error" => "Failed to update profile"], 500);
        }
    }

    // Method to validate profile data
    private function validateProfileData($data)
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = "Name is required.";
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }

        if (isset($data['password']) && strlen($data['password']) < 6) {
            $errors['password'] = "Password must be at least 6 characters long.";
        }

        return $errors;
    }

    // Method to send JSON responses
    private function sendResponse($data, $statusCode)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}