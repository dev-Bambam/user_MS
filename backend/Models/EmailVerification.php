<?php

namespace Models;

use PDO;
use Models\Database;

class EmailVerification
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new verification token for a user.
     *
     * @param int $userId
     * @param string $token
     * @param string|null $expiresAt
     * @return bool
     */
    public function createVerificationToken(int $userId, string $token, ?string $expiresAt = null): bool
    {
        $sql = "INSERT INTO email_verifications (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Retrieve a verification record by token.
     *
     * @param string $token
     * @return array|null
     */
    public function getVerificationByToken(string $token): ?array
    {
        $sql = "SELECT * FROM email_verifications WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Delete expired tokens.
     *
     * @return bool
     */
    public function deleteExpiredTokens(): bool
    {
        $sql = "DELETE FROM email_verifications WHERE expires_at < NOW()";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Delete a verification record by user ID.
     *
     * @param int $userId
     * @return bool
     */
    public function deleteVerificationByUserId(int $userId): bool
    {
        $sql = "DELETE FROM email_verifications WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['user_id' => $userId]);
    }
    /**
     * Find a verification record by its token.
     *
     * @param string $token
     * @return array|null The record as an associative array, or null if not found.
     */
    public static function findByToken(string $token): ?array
    {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT * FROM email_verifications WHERE token = :token");
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $exception) {
            error_log("PDO Error: " . $exception->getMessage());
            return null;
        }
    }

    /**
     * Check if a verification token has expired.
     *
     * @param array $verification The verification record from the database.
     * @return bool True if expired, false otherwise.
     */
    public static function isTokenExpired(array $verification): bool
    {
        $expiresAt = new \DateTime($verification['expires_at']);
        $now = new \DateTime();
        return $now > $expiresAt;
    }

    /**
     * Delete a verification record by its token.
     *
     * @param string $token
     * @return bool True if the record was deleted, false otherwise.
     */
    public static function deleteByToken(string $token): bool
    {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("DELETE FROM email_verifications WHERE token = :token");
            $stmt->bindParam(':token', $token);
            return $stmt->execute();
        } catch (\PDOException $exception) {
            error_log("PDO Error: " . $exception->getMessage());
            return false;
        }
    }

    /**
     * Create a new verification token.
     *
     * @param int $userId
     * @param string $token
     * @return bool True if the record was created, false otherwise.
     */
    public static function create(int $userId, string $token): bool
    {
        try {
            $db = Database::getInstance()->getConnection();
            $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
            $stmt = $db->prepare("INSERT INTO email_verifications (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires_at', $expiresAt);
            return $stmt->execute();
        } catch (\PDOException $exception) {
            error_log("PDO Error: " . $exception->getMessage());
            return false;
        }
    }
    /**
     * Delete a verification record by its user ID.
     *
     * @param int $userId
     * @return bool True if the record was deleted, false otherwise.
     */
    public static function deleteByUserId(int $userId): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM email_verifications WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

}
