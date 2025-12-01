<?php
// PDO tabanlı güvenli veritabanı bağlantı sınıfı.

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $db = config('db');
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $db['host'], $db['port'], $db['name'], $db['charset']);
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$connection = new PDO($dsn, $db['user'], $db['pass'], $options);
            } catch (PDOException $e) {
                throw new PDOException('Veritabanı bağlantısı başarısız: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
