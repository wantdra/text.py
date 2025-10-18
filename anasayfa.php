<?php
$sayfa_baslik = 'Pflegefachmann/frau Quiz & Flashkart';
include __DIR__ . '/ust.php';
$kullanici = kullanici();
?>
<section class="hero" data-scroll>
    <h1>Pflegefachmann/frau Quiz &amp; Flashkart Merkezine Hoş Geldin</h1>
    <p>Klinik bilginizi güçlendirmek için hızlı quiz akışları, hedef takip araçları ve aralıklı tekrar kartları aynı ekranda. Hem mobilde hem masaüstünde ışık hızında.</p>
    <div class="buton-grup">
        <a class="buton" href="dersler.php">Derslere Git</a>
        <a class="buton" href="quiz.php">Hızlı Quiz</a>
    </div>
</section>
<section class="kart" data-scroll>
    <h2>Bugünkü Hedef</h2>
    <p>10 soruluk quizini tamamlayarak tekrar oranını %100'e çıkar. Öğrenme ritmini takip et, kalan sorular için plan yap.</p>
    <div class="progres" aria-hidden="true">
        <div class="progres-ic" style="width: 60%;"></div>
    </div>
    <p><strong>İlerleme:</strong> %60 · <span>Günlük odak: Anatomi ve İlaç Yönetimi</span></p>
</section>
<section class="kart" data-scroll>
    <h2>Son Çalışmalar</h2>
    <ul>
        <li>İlaç Yönetimi quizinde %80 başarı yakalandı</li>
        <li>Temel Anatomi flashkartları 5 dakikada tekrarlandı</li>
        <li>Hastane Hijyeni dersine iki yeni soru eklendi</li>
    </ul>
    <?php if (!$kullanici): ?>
        <p><a class="buton" href="kayit.php">Bugün kayıt ol ve ilerlemeni takip et</a></p>
    <?php else: ?>
        <p><a class="buton buton-ikincil" href="profil.php">Profiline göz at</a></p>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/alt.php'; ?>
