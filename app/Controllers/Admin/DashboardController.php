<?php
// Admin paneli için temel istatistik ve özet bilgileri sunar.

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireRole('admin');
        $users = User::all();
        $stats = [
            'total_users' => count($users),
            'active_users' => count(array_filter($users, fn($u) => $u['is_active'])),
            'total_questions' => 0,
            'solved_today' => 0,
        ];
        $recent = array_slice($users, 0, 5);

        $this->view('admin/dashboard/index', [
            'stats' => $stats,
            'recent' => $recent,
        ]);
    }
}
