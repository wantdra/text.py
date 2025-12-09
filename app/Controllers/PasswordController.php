<?php
// Şifre sıfırlama süreçlerini yönetir.

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\PasswordReset;
use App\Models\User;
use DateInterval;
use DateTimeImmutable;

class PasswordController extends Controller
{
    public function showForgot(): void
    {
        $this->view('password/forgot', [
            'flash' => Session::getFlash('success'),
            'errors' => Session::getFlash('errors'),
        ]);
    }

    public function sendReset(): void
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Ungültiger CSRF-Token.');
            redirect('password/forgot');
        }

        $email = trim($_POST['email'] ?? '');
        $user = User::findByEmail($email);
        if (!$user) {
            Session::flash('errors', ['email' => 'E-Mail nicht gefunden.']);
            redirect('password/forgot');
            return;
        }

        $token = bin2hex(random_bytes(20));
        $expires = (new DateTimeImmutable())->add(new DateInterval('PT1H'))->format('Y-m-d H:i:s');
        PasswordReset::deleteByUser((int)$user['id']);
        PasswordReset::create((int)$user['id'], $token, $expires);

        $link = base_url('password/reset?token=' . $token);
        Session::flash('success', 'Reset-Link (Demo): ' . $link);
        redirect('password/forgot');
    }

    public function showReset(): void
    {
        $token = $_GET['token'] ?? '';
        $this->view('password/reset', [
            'token' => $token,
            'errors' => Session::getFlash('errors'),
            'flash' => Session::getFlash('success'),
        ]);
    }

    public function reset(): void
    {
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Ungültiger CSRF-Token.');
            redirect('password/reset?token=' . ($_POST['token'] ?? ''));
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        $errors = [];
        if (strlen($password) < 8) {
            $errors['password'] = 'Passwort zu kurz.';
        }
        if ($password !== $confirm) {
            $errors['password_confirm'] = 'Passwörter stimmen nicht überein.';
        }

        $reset = PasswordReset::findValid($token);
        if (!$reset) {
            $errors['token'] = 'Token ungültig oder abgelaufen.';
        }

        if ($errors) {
            Session::flash('errors', $errors);
            redirect('password/reset?token=' . $token);
            return;
        }

        User::updatePassword((int)$reset['user_id'], $password);
        PasswordReset::deleteByUser((int)$reset['user_id']);
        Session::flash('success', 'Passwort erfolgreich geändert. Bitte anmelden.');
        redirect('login');
    }
}
