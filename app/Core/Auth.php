<?php
// Kimlik doğrulama ve rol kontrolü yardımcıları.

namespace App\Core;

use App\Models\LoginAttempt;
use App\Models\User;
use DateInterval;
use DateTimeImmutable;

class Auth
{
    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            Session::flash('error', 'Bitte melde dich zuerst an.');
            redirect('login');
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireLogin();
        $user = self::user();
        if (!$user || $user['role'] !== $role) {
            Session::flash('error', 'Keine Berechtigung.');
            redirect('dashboard');
        }
    }

    public static function attemptLogin(string $email, string $password): bool
    {
        $limit = config('security')['login_attempt_limit'];
        $lockMinutes = config('security')['login_lock_minutes'];
        $attempts = LoginAttempt::countRecentFailures($email, $limit, $lockMinutes);
        if ($attempts >= $limit) {
            Session::flash('error', 'Zu viele Fehlversuche. Bitte später erneut versuchen.');
            return false;
        }

        $user = User::findByEmail($email);
        $success = $user && password_verify($password, $user['password_hash']);
        LoginAttempt::log($email, $_SERVER['REMOTE_ADDR'] ?? 'unknown', $success);

        if ($success) {
            if (!(bool)$user['is_active']) {
                Session::flash('error', 'Konto ist deaktiviert.');
                return false;
            }
            Session::regenerate();
            unset($user['password_hash']);
            Session::set('user', $user);
            User::touchLogin((int)$user['id']);
            return true;
        }

        Session::flash('error', 'E-Mail oder Passwort falsch.');
        return false;
    }

    public static function logout(): void
    {
        Session::destroy();
    }
}
