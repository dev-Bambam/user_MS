<?php

namespace Controllers;

use Models\EmailVerification;
use Models\User;

class EmailVerificationController
{
    /**
     * Verifies a user's email address using a token sent to their email address.
     * 
     * @param string $token The verification token.
     * @return string The JSON response.
     */
    public function verifyEmail(string $token): string
    {
        // Step 1: Retrieve token details using the EmailVerification model
        $verification = EmailVerification::findByToken($token);

        if (!$verification) {
            http_response_code(400);
            return json_encode(['status' => 'error', 'message' => 'Invalid token.']);
        }

        // Step 2: Check if the token has expired
        if (EmailVerification::isTokenExpired($verification)) {
            http_response_code(400);
            return json_encode(['status' => 'error', 'message' => 'Token has expired.']);
        }

        $userId = $verification['user_id'];

        // Step 3: Mark the user's email as verified using the User model
        if (User::markEmailAsVerified($userId)) {
            // Step 4: Delete the token from the database
            EmailVerification::deleteByToken($token);

            return json_encode(['status' => 'success', 'message' => 'Email verified successfully.']);
        }

        http_response_code(500);
        return json_encode(['status' => 'error', 'message' => 'Failed to verify email.']);
    }

    /**
     * Create and store the verification token in the database.
     *
     * @param int $userId The user ID
     * @param string $token The verification token
     * @return void
     */
    public function createVerificationToken(int $userId, string $token): void
    {
        // Use the EmailVerification model to create the token
        EmailVerification::create($userId, $token);
    }
}
