<?php
// Genel kullanıcı arayüzü header bölümü.
?><!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('app_name')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Minimal ve ferah bir atmosfer için temel stil ayarları */
        :root {
            --bg-soft: #e5f1fb;
            --bg-card: #ffffff;
            --text-main: #111827;
            --accent-blue: #2563eb;
            --accent-cyan: #06b6d4;
            --accent-green: #22c55e;
            --border-soft: #e2e8f0;
            --shadow-soft: 0 18px 40px rgba(15,23,42,0.12);
        }

        body {
            background-color: var(--bg-soft);
            color: var(--text-main);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(120deg, rgba(37,99,235,0.9), rgba(6,182,212,0.9));
            box-shadow: 0 8px 24px rgba(37,99,235,0.25);
        }

        .navbar a.nav-link, .navbar .navbar-brand {
            color: #f8fafc !important;
            font-weight: 600;
        }

        .navbar .nav-link.active, .navbar .nav-link:hover {
            color: #e0f2fe !important;
        }

        .card {
            background-color: var(--bg-card);
            color: var(--text-main);
            border: 1px solid var(--border-soft);
            box-shadow: var(--shadow-soft);
            border-radius: 18px;
        }

        .btn-primary {
            background: linear-gradient(120deg, var(--accent-green), var(--accent-cyan));
            border: none;
            box-shadow: 0 10px 24px rgba(34,197,94,0.35);
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(120deg, #16a34a, #0891b2);
            transform: translateY(-1px);
        }

        a { color: var(--accent-blue); }
        a:hover { color: #1d4ed8; }

        .badge.bg-secondary { background-color: #e0f2fe !important; color: var(--text-main); }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo base_url('/'); ?>">Pflegefachkraft App</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo base_url('/'); ?>">Start</a></li>
        <?php if (\App\Core\Auth::check()): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('dashboard/profile'); ?>">Profil</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('logout'); ?>">Logout</a></li>
            <?php if (\App\Core\Auth::user()['role'] === 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo base_url('admin'); ?>">Admin</a></li>
            <?php endif; ?>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('login'); ?>">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url('register'); ?>">Registrieren</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid px-3 px-md-4 mb-5">
