<?php
$sayfa_baslik = 'Arama';
require_once __DIR__ . '/yardimci.php';

$sorgu = trim($_GET['q'] ?? '');
$soru_sonuclari = [];
$kart_sonuclari = [];

if ($sorgu !== '') {
    $aramakelime = mb_strtolower($sorgu);
    foreach (json_oku('sorular.json') as $soru) {
        $alanlar = mb_strtolower(($soru['soru'] ?? '') . ' ' . ($soru['dogru'] ?? '') . ' ' . ($soru['ders'] ?? ''));
        if (mb_strpos($alanlar, $aramakelime) !== false) {
            $soru_sonuclari[] = $soru;
        }
    }
    foreach (json_oku('kartlar.json') as $kart) {
        $alanlar = mb_strtolower(($kart['soru'] ?? '') . ' ' . ($kart['cevap'] ?? ''));
        if (mb_strpos($alanlar, $aramakelime) !== false) {
            $kart_sonuclari[] = $kart;
        }
    }
}

include __DIR__ . '/ust.php';
?>
<section class="kart">
    <h1>Arama</h1>
    <form method="get">
        <div class="form-grup">
            <label for="q">Soru veya kart ara</label>
            <input type="search" id="q" name="q" value="<?= htmlspecialchars($sorgu) ?>" placeholder="Örnek: asepsi">
        </div>
        <button class="buton" type="submit">Ara</button>
    </form>
</section>
<?php if ($sorgu !== ''): ?>
    <section class="kart">
        <h2>Soru Sonuçları (<?= count($soru_sonuclari) ?>)</h2>
        <?php if ($soru_sonuclari): ?>
            <ul>
                <?php foreach ($soru_sonuclari as $soru): ?>
                    <li><strong><?= htmlspecialchars($soru['ders'] ?? '') ?>:</strong> <?= htmlspecialchars($soru['soru'] ?? '') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Uygun soru bulunamadı.</p>
        <?php endif; ?>
    </section>
    <section class="kart">
        <h2>Flashkart Sonuçları (<?= count($kart_sonuclari) ?>)</h2>
        <?php if ($kart_sonuclari): ?>
            <ul>
                <?php foreach ($kart_sonuclari as $kart): ?>
                    <li><strong><?= htmlspecialchars($kart['soru'] ?? '') ?>:</strong> <?= htmlspecialchars($kart['cevap'] ?? '') ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Eşleşen flashkart bulunamadı.</p>
        <?php endif; ?>
    </section>
<?php endif; ?>
<?php include __DIR__ . '/alt.php'; ?>
