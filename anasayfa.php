<?php
$sayfa_baslik = 'Pflegefachmann/frau Quiz & Flashkart';
include __DIR__ . '/ust.php';
$kullanici = kullanici();
?>
<section class="kart">
    <h1>Pflegefachmann/frau Quiz & Flashkart Platformuna Hoş Geldiniz</h1>
    <p>Hemşirelik eğitiminde uzmanlaşmak için dinamik quizler, hedef odaklı öğrenme planları ve aralıklı tekrar flashkartlarıyla hızınızı artırın.</p>
    <div class="buton-grup">
        <a class="buton" href="dersler.php">Derslere Git</a>
        <a class="buton" href="quiz.php">Hızlı Quiz</a>
    </div>
</section>
<section class="kart">
    <h2>Bugünkü Hedef</h2>
    <p>10 soruluk quizini tamamlayarak tekrar oranını %100'e çıkar.</p>
    <div class="progres" aria-hidden="true">
        <div class="progres-ic" style="width: 60%;"></div>
    </div>
    <p><strong>İlerleme:</strong> %60</p>
</section>
<section class="kart">
    <h2>Son Çalışmalar</h2>
    <ul>
        <li>İlaç Yönetimi quizinde %80 başarı</li>
        <li>Temel Anatomi kartlarını 5 dakikada tekrar ettin</li>
        <li>Hastane Hijyeni dersine yeni kartlar eklendi</li>
    </ul>
    <?php if (!$kullanici): ?>
        <p><a class="buton" href="kayit.php">Bugün kayıt ol ve ilerlemeni takip et</a></p>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/alt.php'; ?>
