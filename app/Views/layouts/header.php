<?php
// Genel kullanıcı arayüzü header bölümü.
?><!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('app_name')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Daha yumuşak ve ferah bir görünüm için pastel renk paleti */
        body { background-color: #f6f8fb; color: #0f172a; }
        .navbar { background-color: #0ea5e9; box-shadow: 0 2px 12px rgba(14,165,233,0.25); }
        .navbar a.nav-link, .navbar .navbar-brand { color: #0b1221 !important; font-weight: 600; }
        .card { background-color: #ffffff; color: #0b1221; border: 1px solid #e2e8f0; box-shadow: 0 6px 18px rgba(15,23,42,0.08); }
        .btn-primary { background-color: #10b981; border-color: #10b981; box-shadow: 0 4px 12px rgba(16,185,129,0.25); }
        .btn-primary:hover { background-color: #0ea375; border-color: #0ea375; }
        a { color: #0ea5e9; }
        .badge.bg-secondary { background-color: #e0f2fe !important; color: #0b1221; }
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
<div class="container mb-5">
