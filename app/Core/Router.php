<?php
// Basit MVC yönlendiricisi: HTTP metoduna göre controller metodunu çağırır.

namespace App\Core;

class Router
{
    private array $routes = [];
    private string $basePath;

    public function __construct()
    {
        $base = parse_url(config('base_url'), PHP_URL_PATH) ?: '';
        $this->basePath = rtrim($base, '/');
    }

    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$this->normalize($path)] = $handler;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        if ($this->basePath && str_starts_with($path, $this->basePath)) {
            $path = substr($path, strlen($this->basePath));
        }
        $path = $this->normalize($path);
        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler) {
            http_response_code(404);
            echo 'Sayfa bulunamadı';
            return;
        }

        call_user_func($handler);
    }

    private function normalize(string $path): string
    {
        $normalized = '/' . trim($path, '/');
        return $normalized === '/' ? '/' : $normalized;
    }
}
