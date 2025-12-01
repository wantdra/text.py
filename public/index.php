<?php
// Front controller; router kurar ve gelen istekleri ilgili controller'a yönlendirir.

require __DIR__ . '/../app/Core/helpers.php';
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    spl_autoload_register(function ($class) {
        $prefix = 'App\\';
        $baseDir = __DIR__ . '/../app/';
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        }
    });
}

use App\Controllers\Admin\DashboardController as AdminDashboard;
use App\Controllers\Admin\UserController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\PasswordController;
use App\Core\Router;
use App\Core\Session;

Session::start();

$router = new Router();

// Genel sayfalar
$router->get('/', fn() => (new HomeController())->index());

// Auth
$router->get('/login', fn() => (new AuthController())->showLogin());
$router->post('/login', fn() => (new AuthController())->login());
$router->get('/register', fn() => (new AuthController())->showRegister());
$router->post('/register', fn() => (new AuthController())->register());
$router->get('/logout', fn() => (new AuthController())->logout());

// Şifre sıfırlama
$router->get('/password/forgot', fn() => (new PasswordController())->showForgot());
$router->post('/password/forgot', fn() => (new PasswordController())->sendReset());
$router->get('/password/reset', fn() => (new PasswordController())->showReset());
$router->post('/password/reset', fn() => (new PasswordController())->reset());

// Kullanıcı dashboard
$router->get('/dashboard', fn() => (new DashboardController())->index());
$router->get('/dashboard/profile', fn() => (new DashboardController())->profile());
$router->post('/dashboard/profile', fn() => (new DashboardController())->updateProfile());

// Admin paneli
$router->get('/admin', fn() => (new AdminDashboard())->index());
$router->get('/admin/users', fn() => (new UserController())->index());
$router->get('/admin/users/create', fn() => (new UserController())->create());
$router->post('/admin/users', fn() => (new UserController())->store());
$router->get('/admin/users/edit', fn() => (new UserController())->edit());
$router->post('/admin/users/update', fn() => (new UserController())->update());

$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
