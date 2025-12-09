<?php
// Giriş yapmış kullanıcıya özel dashboard ve profil yönetimi.

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        $stats = [
            'total_questions' => 120,
            'completed_today' => 6,
            'accuracy' => 78,
            'weak_topics' => ['Dekubitusprophylaxe', 'Wundmanagement', 'Pflegeprozess'],
        ];

        $this->view('dashboard/index', [
            'user' => $user,
            'stats' => $stats,
            'flash' => Session::getFlash('success'),
        ]);
    }

    public function profile(): void
    {
        Auth::requireLogin();
        $user = Auth::user();
        $this->view('dashboard/profile', [
            'user' => $user,
            'errors' => Session::getFlash('errors'),
            'flash' => Session::getFlash('success'),
        ]);
    }

    public function updateProfile(): void
    {
        Auth::requireLogin();
        if (!Session::verifyCsrf($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Ungültiger CSRF-Token.');
            redirect('dashboard/profile');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $errors = [];
        if (strlen($name) < 3) {
            $errors['name'] = 'Name zu kurz.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-Mail ungültig.';
        }

        if ($errors) {
            Session::flash('errors', $errors);
            redirect('dashboard/profile');
            return;
        }

        User::updateProfile((int)Auth::user()['id'], $name, $email);
        $user = User::find((int)Auth::user()['id']);
        Session::set('user', $user);
        Session::flash('success', 'Profil aktualisiert.');
        redirect('dashboard/profile');
    }
}
