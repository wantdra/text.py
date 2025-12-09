<?php
// Şifre sıfırlama token yönetimi modeli.

namespace App\Models;

use App\Core\Database;
use DateTimeImmutable;

class PasswordReset
{
    public static function create(int $userId, string $token, string $expiresAt): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (:user_id, :token, :expires_at, NOW())');
        $stmt->execute([
            ':user_id' => $userId,
            ':token' => $token,
            ':expires_at' => $expiresAt,
        ]);
    }

    public static function findValid(string $token): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM password_resets WHERE token = :token LIMIT 1');
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        $now = new DateTimeImmutable();
        if ($now > new DateTimeImmutable($row['expires_at'])) {
            return null;
        }

        return $row;
    }

    public static function deleteByUser(int $userId): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM password_resets WHERE user_id = :id');
        $stmt->execute([':id' => $userId]);
    }
}
