<?php

namespace App\Includes;

use Random\RandomException;

class Security
{
    public static function hash_password(string $password, int $salt = 10): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => $salt]);
    }

    public static function verify_password(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * @throws RandomException
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

    public static function validateCsrfToken($token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // 5. Input Sanitization (Basic XSS protection)
    public static function sanitizeInput($data): string
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @throws RandomException
     */
    public static function encryptData($data, $key): string
    {
        $iv = random_bytes(16); // Initialization vector for encryption
        $cipher = 'aes-256-cbc';
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    public static function decryptData($encryptedData, $key): false|string
    {
        list($encrypted, $iv) = explode('::', base64_decode($encryptedData), 2);
        $cipher = 'aes-256-cbc';
        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }

    /**
     * @throws RandomException
     */
    public static function generateToken($length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    public static function regenerateSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);
    }

    public static function escapeOutput($data): string
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    public static function validateEmail($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}