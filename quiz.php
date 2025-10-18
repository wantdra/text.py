<?php
$sayfa_baslik = 'Quiz';
require_once __DIR__ . '/yardimci.php';

$secili_ders = trim($_GET['ders'] ?? '');
$tum_sorular = json_oku('sorular.json');
$durum = 'quiz';
$sonuclar = [];
$puan = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_kontrol($_POST['token'] ?? '')) {
        $durum = 'hata';
        $hata_mesaji = 'Güvenlik doğrulaması başarısız.';
    } else {
        $quiz_sorular = $_SESSION['quiz_sorular'] ?? [];
        if (!$quiz_sorular) {
            $durum = 'hata';
            $hata_mesaji = 'Quiz süresi doldu. Lütfen yeniden başlayın.';
        } else {
            $dogru_sayisi = 0;
            foreach ($quiz_sorular as $indeks => $soru) {
                $cevap = $_POST['cevap'][$indeks] ?? '';
                $dogru = $soru['dogru'] ?? '';
                $tip = $soru['tip'] ?? 'coktan';
                $dogru_mu = false;

                if ($tip === 'dogruyanlis') {
                    $dogru_mu = strtolower($cevap) === strtolower($dogru);
                } elseif ($tip === 'kisa') {
                    $yuzde = benzerlik_yuzde($cevap, $dogru);
                    $dogru_mu = $yuzde >= 80;
                } else {
                    $dogru_mu = strtolower(trim($cevap)) === strtolower(trim($dogru));
                }

                if ($dogru_mu) {
                    $dogru_sayisi++;
                }

                $kayit = array_merge($soru, [
                    'verilen' => $cevap,
                    'dogru_mu' => $dogru_mu
                ]);
                if ($tip === 'kisa') {
                    $kayit['benzerlik'] = isset($yuzde) ? round($yuzde, 1) : null;
                }
                $sonuclar[$indeks] = $kayit;
            }
            $puan = round(($dogru_sayisi / max(1, count($quiz_sorular))) * 100);
            $durum = 'sonuc';
            unset($_SESSION['quiz_sorular']);
        }
    }
} else {
    $filtreli = array_values(array_filter($tum_sorular, function ($s) use ($secili_ders) {
        if (!$secili_ders) {
            return true;
        }
        return strtolower($s['ders'] ?? '') === strtolower($secili_ders);
    }));
    if (!$filtreli) {
        $filtreli = $tum_sorular;
    }
    shuffle($filtreli);
    $secim = array_slice($filtreli, 0, 10);
    if (!$secim) {
        $durum = 'hata';
        $hata_mesaji = 'Yeterli soru bulunamadı.';
    }
    $_SESSION['quiz_sorular'] = $secim;
}

include __DIR__ . '/ust.php';
?>
<?php if ($durum === 'hata'): ?>
    <section class="kart" data-scroll>
        <h1>Quiz</h1>
        <div class="hata"><?= $hata_mesaji ?? 'Bir sorun oluştu.' ?></div>
        <p><a class="buton" href="quiz.php<?= $secili_ders ? '?ders=' . urlencode($secili_ders) : '' ?>">Yeniden Dene</a></p>
    </section>
<?php elseif ($durum === 'sonuc'): ?>
    <section class="kart" data-scroll>
        <h1>Sonuçlar</h1>
        <p><strong>Puanınız:</strong> <?= $puan ?> / 100</p>
    </section>
    <?php foreach ($sonuclar as $indeks => $sonuc): ?>
        <section class="kart" data-scroll>
            <h2><?= ($indeks + 1) ?>. Soru</h2>
            <p><?= htmlspecialchars($sonuc['soru'] ?? '') ?></p>
            <p><strong>Doğru Cevap:</strong> <?= htmlspecialchars($sonuc['dogru'] ?? '') ?></p>
            <p><strong>Yanıtınız:</strong> <?= htmlspecialchars($sonuc['verilen'] ?? '') ?></p>
            <p><strong>Durum:</strong> <?= !empty($sonuc['dogru_mu']) ? 'Doğru' : 'Yanlış' ?></p>
            <?php if (isset($sonuc['benzerlik'])): ?>
                <p>Benzerlik: %<?= $sonuc['benzerlik'] ?></p>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
    <section class="kart" data-scroll>
        <a class="buton" href="quiz.php<?= $secili_ders ? '?ders=' . urlencode($secili_ders) : '' ?>">Yeni Quiz Başlat</a>
    </section>
<?php else: ?>
    <?php $quiz_sorular = $_SESSION['quiz_sorular'] ?? []; ?>
    <section class="kart" data-scroll>
        <h1>Quiz</h1>
        <?php if ($secili_ders): ?>
            <p><strong>Ders:</strong> <?= htmlspecialchars($secili_ders) ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="token" value="<?= csrf_token() ?>">
            <?php foreach ($quiz_sorular as $indeks => $soru): ?>
                <?php
                    $tip = $soru['tip'] ?? 'coktan';
                    $secenekler = $soru['secenekler'] ?? [];
                    if (is_string($secenekler)) {
                        $secenekler = array_filter(array_map('trim', explode(',', $secenekler)));
                    }
                    if ($tip === 'dogruyanlis') {
                        $secenekler = ['Doğru', 'Yanlış'];
                    }
                ?>
                <div class="quiz-soru kart" data-scroll>
                    <h2><?= ($indeks + 1) ?>. Soru</h2>
                    <p><?= htmlspecialchars($soru['soru'] ?? '') ?></p>
                    <?php if ($tip === 'kisa'): ?>
                        <input type="text" name="cevap[<?= $indeks ?>]" required>
                    <?php else: ?>
                        <div class="quiz-secim">
                            <?php foreach ($secenekler as $secenek): ?>
                                <label>
                                    <input type="radio" name="cevap[<?= $indeks ?>]" value="<?= htmlspecialchars($secenek) ?>" required>
                                    <span><?= htmlspecialchars($secenek) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <button class="buton" type="submit">Quiz'i Bitir</button>
        </form>
    </section>
<?php endif; ?>
<?php include __DIR__ . '/alt.php'; ?>
