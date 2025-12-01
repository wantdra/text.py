<?php
// Kayıt ve giriş işlemlerini yönetir.

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/login', [
            'errors' => Session::getFlash('errors'),
            'old' => Session::getFlash('old'),
        ]);
    }

    public function login(): void
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Ungültiger CSRF-Token.');
            redirect('login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        Session::flash('old', ['email' => $email]);

        if (Auth::attemptLogin($email, $password)) {
            redirect('dashboard');
            return;
        }

        Session::flash('errors', ['email' => 'Login fehlgeschlagen.']);
        redirect('login');
    }

    public function showRegister(): void
    {
        $this->view('auth/register', [
            'errors' => Session::getFlash('errors'),
            'old' => Session::getFlash('old'),
        ]);
    }

    public function register(): void
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Ungültiger CSRF-Token.');
            redirect('register');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $errors = [];
        if (strlen($name) < 3) {
            $errors['name'] = 'Name zu kurz.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-Mail ungültig.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Passwort zu kurz (min. 8 Zeichen).';
        }
        if ($password !== $passwordConfirm) {
            $errors['password_confirm'] = 'Passwörter stimmen nicht überein.';
        }
        if (User::findByEmail($email)) {
            $errors['email'] = 'E-Mail bereits vergeben.';
        }

        if ($errors) {
            Session::flash('errors', $errors);
            Session::flash('old', ['name' => $name, 'email' => $email]);
            redirect('register');
            return;
        }

        $userId = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'user',
        ]);

        Session::flash('success', 'Registrierung erfolgreich, bitte anmelden.');
        redirect('login');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/');
    }
}
