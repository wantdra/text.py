<?php
$sayfa_baslik = 'Kayıt Ol';
require_once __DIR__ . '/yardimci.php';

$hata = '';
$basarili = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_kontrol($_POST['token'] ?? '')) {
        $hata = 'Güvenlik doğrulaması başarısız.';
    } else {
        $ad = temiz(post_degeri('ad', ''));
        $soyad = temiz(post_degeri('soyad', ''));
        $eposta = strtolower(trim(post_degeri('eposta', '')));
        $sifre = post_degeri('sifre', '');

        if (!$ad || !$soyad || !$eposta || !$sifre) {
            $hata = 'Tüm alanlar zorunludur.';
        } elseif (!filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
            $hata = 'Geçerli bir e-posta giriniz.';
        } elseif (strlen($sifre) < 6) {
            $hata = 'Şifre en az 6 karakter olmalıdır.';
        } else {
            $kullanicilar = json_oku('kullanicilar.json');
            foreach ($kullanicilar as $k) {
                if (strtolower($k['eposta'] ?? '') === $eposta) {
                    $hata = 'Bu e-posta zaten kayıtlı.';
                    break;
                }
            }
            if (!$hata) {
                $yeni = [
                    'id' => uuid(),
                    'ad' => $ad,
                    'soyad' => $soyad,
                    'eposta' => $eposta,
                    'rol' => 'ogrenci',
                    'sifre_hash' => password_hash($sifre, PASSWORD_DEFAULT)
                ];
                $kullanicilar[] = $yeni;
                if (json_yaz('kullanicilar.json', $kullanicilar)) {
                    $basarili = 'Kayıt başarılı! Giriş yapabilirsiniz.';
                } else {
                    $hata = 'Kayıt sırasında bir sorun oluştu.';
                }
            }
        }
    }
}
include __DIR__ . '/ust.php';
?>
<section class="kart">
    <h1>Yeni Hesap Oluştur</h1>
    <?php if ($hata): ?>
        <div class="hata"><?= $hata ?></div>
    <?php elseif ($basarili): ?>
        <div class="basarili"><?= $basarili ?></div>
    <?php endif; ?>
    <form method="post" novalidate>
        <input type="hidden" name="token" value="<?= csrf_token() ?>">
        <div class="form-grup">
            <label for="ad">Ad</label>
            <input type="text" id="ad" name="ad" required value="<?= temiz($_POST['ad'] ?? '') ?>">
        </div>
        <div class="form-grup">
            <label for="soyad">Soyad</label>
            <input type="text" id="soyad" name="soyad" required value="<?= temiz($_POST['soyad'] ?? '') ?>">
        </div>
        <div class="form-grup">
            <label for="eposta">E-posta</label>
            <input type="email" id="eposta" name="eposta" required value="<?= temiz($_POST['eposta'] ?? '') ?>">
        </div>
        <div class="form-grup">
            <label for="sifre">Şifre</label>
            <input type="password" id="sifre" name="sifre" required>
        </div>
        <button class="buton" type="submit">Kayıt Ol</button>
    </form>
    <p>Zaten hesabın var mı? <a href="giris.php">Giriş yap</a>.</p>
</section>
<?php include __DIR__ . '/alt.php'; ?>
