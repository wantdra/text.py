<?php
// Basit PDO bağlantı yardımcı dosyası (XAMPP/MySQL için)
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dbHost = 'localhost';
        $dbName = 'almanca_app';
        $dbUser = 'root'; // XAMPP varsayılanı
        $dbPass = '';     // XAMPP varsayılanı (parola boş)

        $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    return $pdo;
}
