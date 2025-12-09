<?php
// Başarısız giriş denemelerini izleyerek brute force koruması sağlar.

namespace App\Models;

use App\Core\Database;
use DateInterval;
use DateTimeImmutable;

class LoginAttempt
{
    public static function log(string $email, string $ip, bool $success): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO login_attempts (email, ip_address, attempted_at, success) VALUES (:email, :ip, NOW(), :success)');
        $stmt->execute([
            ':email' => $email,
            ':ip' => $ip,
            ':success' => $success ? 1 : 0,
        ]);
    }

    public static function countRecentFailures(string $email, int $limit, int $minutes): int
    {
        $threshold = (new DateTimeImmutable())->sub(new DateInterval('PT' . $minutes . 'M'))->format('Y-m-d H:i:s');
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM login_attempts WHERE email = :email AND success = 0 AND attempted_at >= :threshold');
        $stmt->execute([':email' => $email, ':threshold' => $threshold]);
        return (int)$stmt->fetchColumn();
    }
}
