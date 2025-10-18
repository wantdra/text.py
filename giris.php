<?php
$sayfa_baslik = 'Giriş Yap';
require_once __DIR__ . '/yardimci.php';

$hata = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_kontrol($_POST['token'] ?? '')) {
        $hata = 'Güvenlik doğrulaması başarısız.';
    } else {
        $eposta = strtolower(trim(post_degeri('eposta', '')));
        $sifre = post_degeri('sifre', '');
        if (!$eposta || !$sifre) {
            $hata = 'Lütfen e-posta ve şifre giriniz.';
        } else {
            $kullanicilar = json_oku('kullanicilar.json');
            foreach ($kullanicilar as $k) {
                if (strtolower($k['eposta'] ?? '') === $eposta) {
                    $dogrulandi = false;
                    if (!empty($k['sifre_hash']) && password_verify($sifre, $k['sifre_hash'])) {
                        $dogrulandi = true;
                    } elseif (!empty($k['sifre']) && password_verify($sifre, $k['sifre'])) {
                        $dogrulandi = true;
                    } elseif (!empty($k['sifre_duz']) && hash_equals($k['sifre_duz'], $sifre)) {
                        $dogrulandi = true;
                    }
                    if ($dogrulandi) {
                        $_SESSION['kullanici'] = [
                            'id' => $k['id'] ?? '',
                            'ad' => $k['ad'] ?? '',
                            'soyad' => $k['soyad'] ?? '',
                            'eposta' => $k['eposta'] ?? '',
                            'rol' => $k['rol'] ?? 'ogrenci'
                        ];
                        header('Location: profil.php');
                        exit;
                    }
                }
            }
            $hata = 'Bilgiler doğrulanamadı.';
        }
    }
}
include __DIR__ . '/ust.php';
?>
<section class="kart">
    <h1>Giriş Yap</h1>
    <?php if ($hata): ?>
        <div class="hata"><?= $hata ?></div>
    <?php endif; ?>
    <form method="post" novalidate>
        <input type="hidden" name="token" value="<?= csrf_token() ?>">
        <div class="form-grup">
            <label for="eposta">E-posta</label>
            <input type="email" id="eposta" name="eposta" required value="<?= temiz($_POST['eposta'] ?? '') ?>">
        </div>
        <div class="form-grup">
            <label for="sifre">Şifre</label>
            <input type="password" id="sifre" name="sifre" required>
        </div>
        <button class="buton" type="submit">Giriş Yap</button>
    </form>
    <p>Hesabın yok mu? <a href="kayit.php">Kayıt ol</a>.</p>
</section>
<?php include __DIR__ . '/alt.php'; ?>
