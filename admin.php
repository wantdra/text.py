<?php
$sayfa_baslik = 'Admin Paneli';
require_once __DIR__ . '/yardimci.php';
admin_zorunlu();

$sekme = $_GET['sekme'] ?? 'ders';
$mesaj = '';
$hata = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_kontrol($_POST['token'] ?? '')) {
        $hata = 'Güvenlik doğrulaması başarısız.';
    } else {
        $form = $_POST['form'] ?? '';
        if ($form === 'ders') {
            $ad = temiz(post_degeri('ders_ad'));
            $aciklama = temiz(post_degeri('ders_aciklama'));
            if ($ad && $aciklama) {
                $dersler = json_oku('dersler.json');
                $dersler[] = [
                    'id' => uuid(),
                    'ad' => $ad,
                    'aciklama' => $aciklama
                ];
                if (json_yaz('dersler.json', $dersler)) {
                    $mesaj = 'Ders eklendi.';
                } else {
                    $hata = 'Ders kaydedilemedi.';
                }
            } else {
                $hata = 'Tüm alanlar zorunludur.';
            }
            $sekme = 'ders';
        } elseif ($form === 'soru') {
            $ders = temiz(post_degeri('soru_ders'));
            $tip = $_POST['soru_tip'] ?? 'coktan';
            $soru_metin = temiz(post_degeri('soru_metin'));
            $dogru = trim(post_degeri('soru_dogru'));
            $secenekler = $_POST['soru_secenekler'] ?? '';
            if ($ders && $tip && $soru_metin && $dogru) {
                $liste = json_oku('sorular.json');
                $kayit = [
                    'id' => uuid(),
                    'ders' => $ders,
                    'tip' => $tip,
                    'soru' => $soru_metin,
                    'dogru' => $dogru,
                ];
                if ($tip !== 'kisa') {
                    $secenek_dizi = array_filter(array_map('trim', explode(',', (string) $secenekler)));
                    $kayit['secenekler'] = array_values($secenek_dizi);
                }
                $liste[] = $kayit;
                if (json_yaz('sorular.json', $liste)) {
                    $mesaj = 'Soru eklendi.';
                } else {
                    $hata = 'Soru kaydedilemedi.';
                }
            } else {
                $hata = 'Soru eklemek için tüm alanları doldurunuz.';
            }
            $sekme = 'soru';
        } elseif ($form === 'kart') {
            $soru = temiz(post_degeri('kart_soru'));
            $cevap = trim(post_degeri('kart_cevap'));
            if ($soru && $cevap) {
                $kartlar = json_oku('kartlar.json');
                $kartlar[] = [
                    'id' => uuid(),
                    'soru' => $soru,
                    'cevap' => $cevap,
                    'sonraki_tekrar' => date('c')
                ];
                if (json_yaz('kartlar.json', $kartlar)) {
                    $mesaj = 'Kart eklendi.';
                } else {
                    $hata = 'Kart kaydedilemedi.';
                }
            } else {
                $hata = 'Kart için soru ve cevap giriniz.';
            }
            $sekme = 'kart';
        }
    }
}

$dersler = json_oku('dersler.json');
$sorular = json_oku('sorular.json');
$kartlar = json_oku('kartlar.json');

include __DIR__ . '/ust.php';
?>
<section class="kart">
    <h1>Hızlı Yönetim</h1>
    <div class="sekme-menu">
        <a class="buton" href="admin.php?sekme=ders">Dersler</a>
        <a class="buton" href="admin.php?sekme=soru">Sorular</a>
        <a class="buton" href="admin.php?sekme=kart">Kartlar</a>
    </div>
    <?php if ($hata): ?>
        <div class="hata"><?= $hata ?></div>
    <?php elseif ($mesaj): ?>
        <div class="basarili"><?= $mesaj ?></div>
    <?php endif; ?>
</section>

<?php if ($sekme === 'ders'): ?>
    <section class="kart">
        <h2>Yeni Ders</h2>
        <form method="post">
            <input type="hidden" name="token" value="<?= csrf_token() ?>">
            <input type="hidden" name="form" value="ders">
            <div class="form-grup">
                <label for="ders_ad">Ad</label>
                <input type="text" id="ders_ad" name="ders_ad" required>
            </div>
            <div class="form-grup">
                <label for="ders_aciklama">Açıklama</label>
                <textarea id="ders_aciklama" name="ders_aciklama" required></textarea>
            </div>
            <button class="buton" type="submit">Ders Ekle</button>
        </form>
    </section>
    <section class="kart">
        <h2>Son Dersler</h2>
        <ul>
            <?php foreach (array_slice(array_reverse($dersler), 0, 10) as $ders): ?>
                <li><strong><?= htmlspecialchars($ders['ad'] ?? '') ?>:</strong> <?= htmlspecialchars($ders['aciklama'] ?? '') ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php elseif ($sekme === 'soru'): ?>
    <section class="kart">
        <h2>Yeni Soru</h2>
        <form method="post">
            <input type="hidden" name="token" value="<?= csrf_token() ?>">
            <input type="hidden" name="form" value="soru">
            <div class="form-grup">
                <label for="soru_ders">Ders</label>
                <input type="text" id="soru_ders" name="soru_ders" list="dersler" required>
                <datalist id="dersler">
                    <?php foreach ($dersler as $ders): ?>
                        <option value="<?= htmlspecialchars($ders['ad'] ?? '') ?>">
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="form-grup">
                <label for="soru_tip">Tip</label>
                <select id="soru_tip" name="soru_tip">
                    <option value="dogruyanlis">Doğru / Yanlış</option>
                    <option value="coktan">Çoktan Seçmeli</option>
                    <option value="kisa">Kısa Cevap</option>
                </select>
            </div>
            <div class="form-grup">
                <label for="soru_metin">Soru</label>
                <textarea id="soru_metin" name="soru_metin" required></textarea>
            </div>
            <div class="form-grup">
                <label for="soru_dogru">Doğru Cevap</label>
                <input type="text" id="soru_dogru" name="soru_dogru" required>
            </div>
            <div class="form-grup">
                <label for="soru_secenekler">Seçenekler (virgülle ayırın, kısa cevapta boş bırakın)</label>
                <textarea id="soru_secenekler" name="soru_secenekler"></textarea>
            </div>
            <button class="buton" type="submit">Soru Ekle</button>
        </form>
    </section>
    <section class="kart">
        <h2>Son Sorular</h2>
        <ul>
            <?php foreach (array_slice(array_reverse($sorular), 0, 10) as $soru): ?>
                <li><strong><?= htmlspecialchars($soru['ders'] ?? '') ?>:</strong> <?= htmlspecialchars($soru['soru'] ?? '') ?> (<?= htmlspecialchars($soru['tip'] ?? '') ?>)</li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php else: ?>
    <section class="kart">
        <h2>Yeni Kart</h2>
        <form method="post">
            <input type="hidden" name="token" value="<?= csrf_token() ?>">
            <input type="hidden" name="form" value="kart">
            <div class="form-grup">
                <label for="kart_soru">Soru</label>
                <textarea id="kart_soru" name="kart_soru" required></textarea>
            </div>
            <div class="form-grup">
                <label for="kart_cevap">Cevap</label>
                <textarea id="kart_cevap" name="kart_cevap" required></textarea>
            </div>
            <button class="buton" type="submit">Kart Ekle</button>
        </form>
    </section>
    <section class="kart">
        <h2>Son Kartlar</h2>
        <ul>
            <?php foreach (array_slice(array_reverse($kartlar), 0, 10) as $kart): ?>
                <li><strong><?= htmlspecialchars($kart['soru'] ?? '') ?>:</strong> <?= htmlspecialchars($kart['cevap'] ?? '') ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>

<section class="kart">
    <h2>JSON İçe/Dışa Aktarma</h2>
    <p>JSON dosyalarını <code>veriler/</code> klasöründe bulabilirsiniz. İçe/dışa aktarma otomasyonu için buraya ileride araçlar eklenebilir.</p>
</section>
<?php include __DIR__ . '/alt.php'; ?>
