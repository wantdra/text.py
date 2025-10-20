<?php require_once __DIR__.'/../config.php'; ?>
<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= SITE_ADI ?></title>
  <meta name="description" content="Ebeveynler için anlaşılır çocuk gelişimi rehberi.">
  <link rel="stylesheet" href="/assets/css/stil.css">
</head>
<body>
<header class="kap">
  <a class="logo" href="/anasayfa.php"><?= SITE_ADI ?></a>
  <button class="menu-buton" aria-label="Menüyü aç/kapat" onclick="menuToggle()">☰</button>
  <?php include __DIR__.'/gezinme.php'; ?>
</header>
<main class="icerik">
