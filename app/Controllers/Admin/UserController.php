<?php
// Admin tarafında kullanıcı yönetimi işlemlerini sağlar.

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class UserController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('admin');
        $this->view('admin/users/index', [
            'users' => User::all(),
            'flash' => Session::getFlash('success'),
        ]);
    }

    public function create(): void
    {
        Auth::requireRole('admin');
        $this->view('admin/users/create', [
            'errors' => Session::getFlash('errors'),
            'old' => Session::getFlash('old'),
        ]);
    }

    public function store(): void
    {
        Auth::requireRole('admin');
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Ungültiger CSRF-Token.');
            redirect('admin/users/create');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        $errors = [];
        if (strlen($name) < 3) {
            $errors['name'] = 'Name zu kurz.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-Mail ungültig.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Passwort min. 8 Zeichen.';
        }
        if (User::findByEmail($email)) {
            $errors['email'] = 'E-Mail bereits vergeben.';
        }

        if ($errors) {
            Session::flash('errors', $errors);
            Session::flash('old', ['name' => $name, 'email' => $email, 'role' => $role]);
            redirect('admin/users/create');
            return;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        Session::flash('success', 'Benutzer erstellt.');
        redirect('admin/users');
    }

    public function edit(): void
    {
        Auth::requireRole('admin');
        $id = (int)($_GET['id'] ?? 0);
        $user = User::find($id);
        if (!$user) {
            redirect('admin/users');
        }

        $this->view('admin/users/edit', [
            'user' => $user,
            'errors' => Session::getFlash('errors'),
        ]);
    }

    public function update(): void
    {
        Auth::requireRole('admin');
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Ungültiger CSRF-Token.');
            redirect('admin/users');
        }

        $id = (int)($_POST['id'] ?? 0);
        $role = $_POST['role'] ?? 'user';
        $active = isset($_POST['is_active']);
        User::setRole($id, $role);
        User::setActive($id, $active);

        Session::flash('success', 'Benutzer aktualisiert.');
        redirect('admin/users');
    }
}
