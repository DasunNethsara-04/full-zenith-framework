<?php

namespace ZenithPHP\Core\Includes;

use Firebase\JWT\JWT;
use Random\RandomException;

/**
 * Class Security
 *
 * This Security class provides various security functions including password hashing,
 * JWT generation, CSRF token generation, input sanitization, data encryption/decryption,
 * and other security utilities to help protect user data and secure web applications.
 *
 * @package ZenithPHP\Core\Includes
 */
class Security
{
    /**
     * Hashes a password with BCRYPT.
     *
     * @param string $password The password to hash.
     * @param int $salt Cost factor for BCRYPT (default is 10).
     * @return string The hashed password.
     */
    public static function hash_password(string $password, int $salt = 10): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $salt]);
    }

    /**
     * Verifies a password against a hashed value.
     *
     * @param string $password The plain-text password.
     * @param string $hash The hashed password.
     * @return bool True if the password matches the hash; otherwise, false.
     */
    public static function verify_password(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Generates a JWT token.
     *
     * @param string|int $userId User ID to encode in the token.
     * @param string $issuer The token issuer.
     * @param string $secretKey The secret key for signing.
     * @param int $expiry Expiry time in seconds (default is 3600).
     * @return string The generated JWT token.
     */
    public static function generateJWTToken(string|int $userId, string $issuer, string $secretKey, int $expiry = 3600): string
    {
        $payload = [
            'iss' => $issuer,
            'sub' => $userId,
            'iat' => time(),
            'exp' => time() + $expiry,
        ];

        return JWT::encode($payload, $secretKey, 'HS256');
    }

    /**
     * Generates a CSRF token and stores it in the session.
     *
     * @throws RandomException
     * @return string The generated CSRF token.
     */
    public static function generate_csrf(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    /**
     * Validates a CSRF token from the session.
     *
     * @param string $token The token to validate.
     * @return bool True if valid; otherwise, false.
     */
    public static function validateCsrfToken($token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Sanitizes input data to prevent XSS.
     *
     * @param string $data The input to sanitize.
     * @return string The sanitized input.
     */
    public static function sanitizeInput($data): string
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Encrypts data using AES-256-CBC.
     *
     * @throws RandomException
     * @param string $data The data to encrypt.
     * @param string $key The encryption key.
     * @return string The encrypted data with the IV appended.
     */
    public static function encryptData($data, $key): string
    {
        $iv = random_bytes(16);
        $cipher = 'aes-256-cbc';
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    /**
     * Decrypts AES-256-CBC encrypted data.
     *
     * @param string $encryptedData The encrypted data.
     * @param string $key The decryption key.
     * @return false|string The decrypted data or false on failure.
     */
    public static function decryptData($encryptedData, $key): false|string
    {
        list($encrypted, $iv) = explode('::', base64_decode($encryptedData), 2);
        $cipher = 'aes-256-cbc';
        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }

    /**
     * Generates a random token.
     *
     * @throws RandomException
     * @param int $length The length of the token (default is 32).
     * @return string The generated token.
     */
    public static function generateToken($length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Regenerates the session ID to prevent session fixation attacks.
     */
    public static function regenerateSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);
    }

    /**
     * Escapes output to prevent XSS attacks.
     *
     * @param string $data The data to escape.
     * @return string The escaped output.
     */
    public static function escapeOutput($data): string
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validates an email address.
     *
     * @param string $email The email to validate.
     * @return bool True if valid; otherwise, false.
     */
    public static function validateEmail($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
