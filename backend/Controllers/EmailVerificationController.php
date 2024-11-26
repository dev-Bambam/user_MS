<?php
namespace Controllers;

use Models\Database;

class EmailVerificationController
{

    /**
     * Verifies a user's email address using a token sent to their email address.
     * 
     * The function:
     * 1. Retrieves the user ID and expiration time associated with the token.
     * 2. Checks if the token has expired.
     * 3. Marks the user's email as verified in the `users` table.
     * 4. Deletes the token from the `email_verifications` table.
     * 
     * Returns a JSON response containing a success or error message.
     * 
     * @param string $token The verification token.
     * @return string The JSON response.
     */
    public function verifyEmail(string $token): string
    {
        $db = Database::getInstance()->getConnection();

        // Step 1: Retrieve the user ID and expiration time associated with the token
        $stmt = $db->prepare("SELECT user_id, expires_at FROM email_verifications WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $verificationData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$verificationData) {
            http_response_code(400);
            return json_encode(['status' => 'error', 'message' => 'Invalid token.']);
        }

        // Step 2: Check if the token has expired
        $expiresAt = new \DateTime($verificationData['expires_at']);
        $now = new \DateTime();
        if ($now > $expiresAt) {
            http_response_code(400);
            return json_encode(['status' => 'error', 'message' => 'Token has expired.']);
        }

        $userId = $verificationData['user_id'];

        // Step 3: Mark the user's email as verified in the `users` table
        $updateStmt = $db->prepare("UPDATE users SET email_verified = 1 WHERE id = :user_id");
        $updateStmt->bindParam(':user_id', $userId);

        if ($updateStmt->execute()) {
            // Step 4: Delete the token from the `email_verifications` table
            $deleteStmt = $db->prepare("DELETE FROM email_verifications WHERE token = :token");
            $deleteStmt->bindParam(':token', $token);
            $deleteStmt->execute();

            return json_encode(['status' => 'success', 'message' => 'Email verified successfully.']);
        }

        http_response_code(500);
        return json_encode(['status' => 'error', 'message' => 'Failed to verify email.']);
    }

    /**
     * Create and send the verification token.
     *
     * @param int $userId The user ID
     * @param string $token The verification token
     * @return void
     */
    public function createVerificationToken(int $userId, string $token): void
    {
        $db = Database::getInstance()->getConnection();

        // Set the token expiration time (24 hours from now)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours')); // 24 hours expiry
        // Insert the token into the database with the expiration time
        $stmt = $db->prepare("INSERT INTO email_verifications (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires_at', $expiresAt);
        $stmt->execute();
    }
}
