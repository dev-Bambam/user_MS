<?php
namespace Utils;

use \Firebase\JWT\JWT;

class JWTUtility
{
    private static $secretKey = 'your-secret-key'; // Keep this key secure!

    /**
     * Generates a JWT token.
     *
     * @param array $userData User data to encode in the token (e.g., user ID)
     * @return string Encoded JWT token
     */
    public static function generateToken(array $userData): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // jwt valid for 1 hour from the issued time
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $userData
        ];

        return JWT::encode($payload, self::$secretKey, 'HS256');
    }

    /**
     * Decodes a JWT token and returns the payload.
     *
     * @param string $token JWT token
     * @return object|null Decoded token payload or null if invalid
     */
    public static function decodeToken(string $token)
    {
        try {
            $decoded = JWT::decode($token, new \Firebase\JWT\Key(self::$secretKey, 'HS256'));
            return $decoded; // Return the decoded token
        } catch (\Exception $e) {
            return null; // Invalid token
        }
    }
    /**
     * Extracts a JWT token from the Authorization header of the current HTTP request.
     * The header is expected to have the format "Authorization: Bearer <token>".
     *
     * @return string|null The JWT token if found, null otherwise
     */
    public static function getJWTFromHeader()
    {
        $headers = apache_request_headers();
        $authorizationHeader = $headers['Authorization'] ?? null;

        if ($authorizationHeader && strpos($authorizationHeader, 'Bearer ') === 0) {
            return substr($authorizationHeader, 7);
        }

        return null;
    }
}
