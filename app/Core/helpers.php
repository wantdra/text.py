<?php
// Basit yardımcı fonksiyonları barındırır.

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        static $config;
        if (!$config) {
            $config = require __DIR__ . '/../../config/config.php';
        }
        return $config[$key] ?? $default;
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $base = rtrim(config('base_url'), '/');
        $path = ltrim($path, '/');
        return $base . ($path ? '/' . $path : '');
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path)
    {
        header('Location: ' . base_url($path));
        exit;
    }
}

if (!function_exists('method')) {
    function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
}

if (!function_exists('is_post')) {
    function is_post(): bool
    {
        return method() === 'POST';
    }
}
