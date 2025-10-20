<?php
$sayfa_baslik = 'Profilim';
require_once __DIR__ . '/yardimci.php';
oturum_zorunlu();
$kullanici = kullanici();
include __DIR__ . '/ust.php';
?>
<section class="kart" data-scroll>
    <h1>Merhaba, <?= htmlspecialchars($kullanici['ad'] ?? '') ?></h1>
    <div class="profil-bilgi">
        <p><strong>Ad:</strong> <?= htmlspecialchars($kullanici['ad'] ?? '') ?></p>
        <p><strong>Soyad:</strong> <?= htmlspecialchars($kullanici['soyad'] ?? '') ?></p>
        <p><strong>E-posta:</strong> <?= htmlspecialchars($kullanici['eposta'] ?? '') ?></p>
        <p><strong>Rol:</strong> <?= htmlspecialchars($kullanici['rol'] ?? 'ogrenci') ?></p>
    </div>
</section>
<section class="kart" data-scroll>
    <h2>Öğrenme Özeti</h2>
    <p>İstatistik alanı yakında burada olacak. Quiz sonuçlarını ve flashkart tekrarlarını burada takip edeceksin.</p>
</section>
<?php include __DIR__ . '/alt.php'; ?>
