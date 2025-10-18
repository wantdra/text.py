<?php
require_once __DIR__ . '/yardimci.php';
$kullanici = kullanici();
$baslik = $sayfa_baslik ?? 'Pflegefachmann/frau Quiz';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($baslik) ?></title>
    <link rel="stylesheet" href="stil.css">
</head>
<body class="tema-acik">
<header class="ust">
    <div class="ust-ic">
        <a href="anasayfa.php" class="logo">Pflege Quiz</a>
        <nav class="menu">
            <a href="anasayfa.php">Anasayfa</a>
            <a href="dersler.php">Dersler</a>
            <a href="quiz.php">Quiz</a>
            <a href="flashkart.php">Flashkart</a>
            <a href="arama.php">Arama</a>
            <?php if ($kullanici): ?>
                <a href="profil.php">Profil</a>
                <?php if (kullanici_admin_mi()): ?>
                    <a href="admin.php">Admin</a>
                <?php endif; ?>
                <a href="cikis.php" class="cikis">Çıkış</a>
            <?php else: ?>
                <a href="giris.php">Giriş</a>
                <a href="kayit.php" class="vurgulu">Kayıt</a>
            <?php endif; ?>
        </nav>
        <button type="button" class="tema-dugme" id="tema-dugme" aria-label="Tema Değiştir">🌓</button>
    </div>
</header>
<main class="icerik">
