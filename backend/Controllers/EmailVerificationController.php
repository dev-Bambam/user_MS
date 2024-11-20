<?php

namespace Controllers;

use Models\Database;

class EmailVerificationController
{
    /**
     * Verify the email using the token.
     *
     * @param string $token The verification token
     * @return string JSON response indicating success or failure
     */
    public function verifyEmail(string $token): string
    {
        $db = Database::getInstance()->getConnection();

        // Step 1: Retrieve the user ID associated with the token
        $stmt = $db->prepare("SELECT user_id FROM email_verifications WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $verificationData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$verificationData) {
            http_response_code(400);
            return json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
        }

        $userId = $verificationData['user_id'];

        // Step 2: Mark the user's email as verified in the `users` table
        $updateStmt = $db->prepare("UPDATE users SET email_verified = 1 WHERE id = :user_id");
        $updateStmt->bindParam(':user_id', $userId);

        if ($updateStmt->execute()) {
            // Step 3: Delete the token from the `email_verifications` table
            $deleteStmt = $db->prepare("DELETE FROM email_verifications WHERE token = :token");
            $deleteStmt->bindParam(':token', $token);
            $deleteStmt->execute();

            return json_encode(['status' => 'success', 'message' => 'Email verified successfully.']);
        }

        http_response_code(500);
        return json_encode(['status' => 'error', 'message' => 'Failed to verify email.']);
    }
}
