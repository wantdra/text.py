<?php
// Admin paneli için header bölümü.
?><!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | <?php echo e(config('app_name')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0b1221; color: #e2e8f0; }
        .sidebar { width: 220px; background: #111827; min-height: 100vh; position: fixed; }
        .content { margin-left: 230px; padding: 20px; }
        a { color: #93c5fd; }
    </style>
</head>
<body>
<div class="sidebar p-3">
    <h5 class="text-light">Admin</h5>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="<?php echo base_url('admin'); ?>">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo base_url('admin/users'); ?>">Benutzer</a></li>
    </ul>
    <a class="btn btn-sm btn-outline-light mt-3" href="<?php echo base_url('dashboard'); ?>">Zurück</a>
</div>
<div class="content">
