<?php

namespace Controllers;

use Models\User;
use Models\EmailVerification;
use Utils\Mailer;

class ResendVerificationController
{
    /**
     * Handles resending the verification email.
     *
     * @param string $email The user's email address.
     * @return string The JSON response.
     */
    public function resendVerification(string $email): string
    {
        // Step 1: Find the user by email
        $user = User::findByEmail($email);

        if (!$user) {
            http_response_code(404);
            return json_encode(['status' => 'error', 'message' => 'User not found.']);
        }

        // Step 2: Check if the user is already verified
        if ($user['email_verified']) {
            http_response_code(400);
            return json_encode(['status' => 'error', 'message' => 'Email is already verified.']);
        }

        // Step 3: Generate a new token
        $newToken = bin2hex(random_bytes(16)); // Generate a random token
        EmailVerification::deleteByUserId($user['id']); // Remove any existing tokens
        EmailVerification::create($user['id'], $newToken); // Create a new token

        // Step 4: Send the email
        $emailSent = (new Mailer())->sendVerificationEmail(
            $email,
            $user['username'],
            $newToken
        );

        if ($emailSent) {
            return json_encode(['status' => 'success', 'message' => 'Verification email sent.']);
        }

        http_response_code(500);
        return json_encode(['status' => 'error', 'message' => 'Failed to send verification email.']);
    }
}
