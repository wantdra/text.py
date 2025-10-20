<?php
$sayfa_baslik = 'Flashkart Tekrarı';
require_once __DIR__ . '/yardimci.php';

$kartlar = json_oku('kartlar.json');
$mesaj = '';
$hata = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_kontrol($_POST['token'] ?? '')) {
        $hata = 'Güvenlik doğrulaması başarısız.';
    } else {
        $kart_id = $_POST['kart_id'] ?? '';
        $seviye = $_POST['seviye'] ?? '';
        $bulundu = false;
        foreach ($kartlar as &$kart) {
            if (($kart['id'] ?? '') === $kart_id) {
                $bulundu = true;
                $simdi = time();
                if ($seviye === 'zor') {
                    $kart['sonraki_tekrar'] = date('c', $simdi + 60);
                } elseif ($seviye === 'orta') {
                    $kart['sonraki_tekrar'] = date('c', $simdi + 600);
                } else {
                    $kart['sonraki_tekrar'] = date('c', $simdi + 86400);
                }
                break;
            }
        }
        unset($kart);
        if ($bulundu) {
            if (json_yaz('kartlar.json', $kartlar)) {
                $mesaj = 'Tekrar zamanı güncellendi.';
                header('Location: flashkart.php');
                exit;
            } else {
                $hata = 'Güncelleme sırasında bir hata oluştu.';
            }
        } else {
            $hata = 'Kart bulunamadı.';
        }
    }
}

usort($kartlar, function ($a, $b) {
    return strtotime($a['sonraki_tekrar'] ?? 'now') <=> strtotime($b['sonraki_tekrar'] ?? 'now');
});

$due = array_filter($kartlar, function ($kart) {
    $zaman = strtotime($kart['sonraki_tekrar'] ?? 'now');
    return $zaman <= time();
});

$gosterilecek = reset($due);
include __DIR__ . '/ust.php';
?>
<section class="kart" data-scroll>
    <h1>Flashkart Tekrarı</h1>
    <p>Aralıklı tekrar algoritması ile kartlarını zamanında gözden geçir.</p>
    <?php if ($hata): ?>
        <div class="hata"><?= $hata ?></div>
    <?php elseif ($mesaj): ?>
        <div class="basarili"><?= $mesaj ?></div>
    <?php endif; ?>
</section>
<?php if ($gosterilecek): ?>
    <section class="kart flas-kart" data-scroll>
        <h2>Sıradaki Kart</h2>
        <p><?= htmlspecialchars($gosterilecek['soru'] ?? '') ?></p>
        <button class="buton" type="button" data-aksiyon="cevabi-goster" data-hedef="kart-cevap">Cevabı Gör</button>
        <div class="flas-cevap" id="kart-cevap"><?= nl2br(htmlspecialchars($gosterilecek['cevap'] ?? '')) ?></div>
        <form method="post" class="srs-butons">
            <input type="hidden" name="token" value="<?= csrf_token() ?>">
            <input type="hidden" name="kart_id" value="<?= htmlspecialchars($gosterilecek['id'] ?? '') ?>">
            <button class="buton" name="seviye" value="zor">Zor</button>
            <button class="buton" name="seviye" value="orta">Orta</button>
            <button class="buton" name="seviye" value="kolay">Kolay</button>
        </form>
        <small>Sonraki tekrar: <?= htmlspecialchars(date('d.m.Y H:i', strtotime($gosterilecek['sonraki_tekrar'] ?? 'now'))) ?></small>
    </section>
<?php else: ?>
    <section class="kart" data-scroll>
        <h2>Tebrikler!</h2>
        <p>Şu anda tekrar edilmesi gereken kart bulunmuyor. Yeni kartlar ekleyerek veya belirli bir süre sonra tekrar gelerek bilgini tazele.</p>
    </section>
<?php endif; ?>
<?php include __DIR__ . '/alt.php'; ?>
