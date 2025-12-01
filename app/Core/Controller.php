<?php
// Tüm controller'lar için temel sınıf ve view render yardımı.

namespace App\Core;

class Controller
{
    protected function view(string $path, array $data = []): void
    {
        extract($data);
        $viewPath = __DIR__ . '/../Views/' . $path . '.php';
        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo 'View bulunamadı';
            return;
        }
        include $viewPath;
    }
}
