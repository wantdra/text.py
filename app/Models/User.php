<?php
// Kullanıcı model sınıfı; temel CRUD ve auth işlemleri.

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    public static function create(array $data): int
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, is_active, created_at, updated_at) VALUES (:name, :email, :password_hash, :role, 1, NOW(), NOW())');
        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => $data['role'] ?? 'user',
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT id, name, email, role, is_active, created_at, updated_at, last_login_at FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function all(): array
    {
        $stmt = Database::connection()->query('SELECT id, name, email, role, is_active, created_at, last_login_at FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function updateProfile(int $id, string $name, string $email): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET name = :name, email = :email, updated_at = NOW() WHERE id = :id');
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':id' => $id,
        ]);
    }

    public static function updatePassword(int $id, string $password): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET password_hash = :password, updated_at = NOW() WHERE id = :id');
        $stmt->execute([
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':id' => $id,
        ]);
    }

    public static function setRole(int $id, string $role): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET role = :role WHERE id = :id');
        $stmt->execute([':role' => $role, ':id' => $id]);
    }

    public static function setActive(int $id, bool $active): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET is_active = :active WHERE id = :id');
        $stmt->execute([':active' => $active ? 1 : 0, ':id' => $id]);
    }

    public static function touchLogin(int $id): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
