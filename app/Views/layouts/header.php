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
        body { background-color: #0f172a; color: #e2e8f0; }
        .navbar { background-color: #111827; }
        .card { background-color: #1f2937; color: #e2e8f0; }
        .btn-primary { background-color: #2563eb; border-color: #2563eb; }
        a { color: #93c5fd; }
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
