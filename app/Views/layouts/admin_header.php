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
        /* Admin paneli için daha aydınlık ve anlaşılır renkler */
        body { background-color: #f8fafc; color: #0b1221; }
        .sidebar { width: 240px; background: linear-gradient(180deg, #0ea5e9 0%, #0284c7 100%); min-height: 100vh; position: fixed; box-shadow: 0 8px 24px rgba(2,132,199,0.25); }
        .sidebar a { color: #0b1221; font-weight: 600; }
        .sidebar h5 { color: #0b1221; font-weight: 700; }
        .content { margin-left: 260px; padding: 24px; }
        .card { background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 6px 18px rgba(15,23,42,0.08); }
        .btn-primary { background-color: #10b981; border-color: #10b981; }
        .btn-outline-light { border-color: #0b1221; color: #0b1221; }
        .btn-outline-light:hover { background-color: #0b1221; color: #f8fafc; }
        a { color: #0b1221; }
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
