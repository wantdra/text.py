<?php
// Güvenli session ve CSRF yönetimi sağlar.

namespace App\Core;

class Session
{
    public static function start(): void
    {
        $name = config('security')['session_name'];
        if (session_status() === PHP_SESSION_NONE) {
            session_name($name);
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'cookie_secure' => isset($_SERVER['HTTPS']),
            ]);
        }
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function flash(string $key, $value): void
    {
        $_SESSION['flash'][$key] = $value;
    }

    public static function getFlash(string $key, $default = null)
    {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return $default;
    }

    public static function csrfToken(): string
    {
        $key = config('security')['csrf_key'];
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = bin2hex(random_bytes(32));
        }
        return $_SESSION[$key];
    }

    public static function verifyCsrf(?string $token): bool
    {
        $key = config('security')['csrf_key'];
        return isset($_SESSION[$key]) && hash_equals($_SESSION[$key], $token ?? '');
    }
}
