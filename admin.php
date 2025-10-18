<?php
$sayfa_baslik = 'Admin Paneli';
require_once __DIR__ . '/yardimci.php';
admin_zorunlu();

function ham_girdi(string $anahtar): string
{
    return trim((string)($_POST[$anahtar] ?? ''));
}

$kaynak_tanimlari = [
    'dersler' => [
        'etiket' => 'Dersler',
        'dosya' => 'dersler.json',
        'alanlar' => [
            ['anahtar' => 'ad', 'baslik' => 'Ad', 'placeholder' => 'Ders adı'],
            ['anahtar' => 'aciklama', 'baslik' => 'Açıklama', 'placeholder' => 'Kısa açıklama'],
        ],
        'varsayilan' => function (): array {
            return [
                'id' => uuid(),
                'ad' => 'Yeni Ders',
                'aciklama' => 'Açıklama ekleyin',
            ];
        },
    ],
    'sorular' => [
        'etiket' => 'Sorular',
        'dosya' => 'sorular.json',
        'alanlar' => [
            ['anahtar' => 'ders', 'baslik' => 'Ders', 'placeholder' => 'Ders adı'],
            ['anahtar' => 'tip', 'baslik' => 'Tip', 'placeholder' => 'dogruyanlis/coktan/kisa'],
            ['anahtar' => 'soru', 'baslik' => 'Soru', 'placeholder' => 'Soru metni'],
            ['anahtar' => 'dogru', 'baslik' => 'Doğru Cevap', 'placeholder' => 'Doğru cevap'],
            ['anahtar' => 'secenekler', 'baslik' => 'Seçenekler', 'placeholder' => 'Virgülle veya satır satır'],
        ],
        'varsayilan' => function (): array {
            return [
                'id' => uuid(),
                'ders' => 'Yeni Ders',
                'tip' => 'dogruyanlis',
                'soru' => 'Yeni soru içeriği',
                'dogru' => 'Doğru',
                'secenekler' => ['Doğru', 'Yanlış'],
            ];
        },
    ],
    'kartlar' => [
        'etiket' => 'Kartlar',
        'dosya' => 'kartlar.json',
        'alanlar' => [
            ['anahtar' => 'soru', 'baslik' => 'Soru', 'placeholder' => 'Kart sorusu'],
            ['anahtar' => 'cevap', 'baslik' => 'Cevap', 'placeholder' => 'Kart cevabı'],
            ['anahtar' => 'sonraki_tekrar', 'baslik' => 'Sonraki Tekrar', 'placeholder' => 'ISO tarih/saat'],
        ],
        'varsayilan' => function (): array {
            return [
                'id' => uuid(),
                'soru' => 'Yeni kart sorusu',
                'cevap' => 'Cevap metni',
                'sonraki_tekrar' => date('c'),
            ];
        },
    ],
    'kullanicilar' => [
        'etiket' => 'Kullanıcılar',
        'dosya' => 'kullanicilar.json',
        'alanlar' => [
            ['anahtar' => 'ad', 'baslik' => 'Ad', 'placeholder' => 'Ad'],
            ['anahtar' => 'soyad', 'baslik' => 'Soyad', 'placeholder' => 'Soyad'],
            ['anahtar' => 'eposta', 'baslik' => 'E-posta', 'placeholder' => 'eposta@site.local'],
            ['anahtar' => 'rol', 'baslik' => 'Rol', 'placeholder' => 'admin | ogrenci'],
        ],
        'varsayilan' => function (): array {
            $gecici = 'gecici' . random_int(1000, 9999);
            return [
                'id' => uuid(),
                'ad' => 'Yeni',
                'soyad' => 'Kullanıcı',
                'eposta' => "yeni-{$gecici}@site.local",
                'rol' => 'ogrenci',
                'sifre_hash' => password_hash($gecici, PASSWORD_DEFAULT),
                '_gecici_sifre' => $gecici,
            ];
        },
    ],
];

$aktif_kaynak = $_GET['kaynak'] ?? 'dersler';
if (!array_key_exists($aktif_kaynak, $kaynak_tanimlari)) {
    $aktif_kaynak = 'dersler';
}

function liste_yukle(array $tanim): array
{
    $liste = json_oku($tanim['dosya']);
    if (!is_array($liste)) {
        return [];
    }
    return $liste;
}

function liste_kaydet(string $kaynak, array $tanim, array $liste): bool
{
    foreach ($liste as &$kayit) {
        if (!isset($kayit['id'])) {
            $kayit['id'] = uuid();
        }
    }
    unset($kayit);
    return json_yaz($tanim['dosya'], $liste);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['islem'])) {
    header('Content-Type: application/json; charset=UTF-8');
    if (!csrf_kontrol($_POST['token'] ?? '')) {
        echo json_encode(['basarili' => false, 'mesaj' => 'Güvenlik doğrulaması başarısız.']);
        exit;
    }

    $kaynak = $_POST['kaynak'] ?? '';
    if (!isset($kaynak_tanimlari[$kaynak])) {
        echo json_encode(['basarili' => false, 'mesaj' => 'Bilinmeyen kaynak.']);
        exit;
    }
    $tanim = $kaynak_tanimlari[$kaynak];
    $liste = liste_yukle($tanim);
    $islem = $_POST['islem'];

    if ($islem === 'guncelle') {
        $id = ham_girdi('id');
        $alan = ham_girdi('alan');
        $deger = $_POST['deger'] ?? '';
        $bulundu = false;
        foreach ($liste as &$kayit) {
            if (($kayit['id'] ?? '') === $id) {
                $bulundu = true;
                if ($kaynak === 'sorular' && $alan === 'secenekler') {
                    $parcalar = preg_split('/\r?\n|,/', (string)$deger);
                    $kayit['secenekler'] = array_values(array_filter(array_map('trim', $parcalar), fn($v) => $v !== ''));
                } elseif ($kaynak === 'kartlar' && $alan === 'sonraki_tekrar') {
                    $zaman = strtotime((string)$deger);
                    $kayit['sonraki_tekrar'] = $zaman ? date('c', $zaman) : date('c');
                } elseif ($kaynak === 'kullanicilar' && $alan === 'rol') {
                    $kayit['rol'] = in_array($deger, ['admin', 'ogrenci'], true) ? $deger : 'ogrenci';
                } else {
                    $kayit[$alan] = trim((string)$deger);
                }
                break;
            }
        }
        unset($kayit);
        if (!$bulundu) {
            echo json_encode(['basarili' => false, 'mesaj' => 'Kayıt bulunamadı.']);
            exit;
        }
        if (liste_kaydet($kaynak, $tanim, $liste)) {
            echo json_encode(['basarili' => true, 'mesaj' => 'Kayıt güncellendi.']);
        } else {
            echo json_encode(['basarili' => false, 'mesaj' => 'Kaydedilemedi.']);
        }
        exit;
    }

    if ($islem === 'ekle') {
        $varsayilan = $tanim['varsayilan']();
        $gecici_sifre = $varsayilan['_gecici_sifre'] ?? null;
        unset($varsayilan['_gecici_sifre']);
        $liste[] = $varsayilan;
        if (liste_kaydet($kaynak, $tanim, $liste)) {
            echo json_encode([
                'basarili' => true,
                'mesaj' => 'Yeni kayıt eklendi.',
                'kayit' => $varsayilan,
                'geciciSifre' => $gecici_sifre,
            ]);
        } else {
            echo json_encode(['basarili' => false, 'mesaj' => 'Yeni kayıt kaydedilemedi.']);
        }
        exit;
    }

    if ($islem === 'json_kaydet') {
        $ham = $_POST['ham'] ?? '';
        $cozum = json_decode((string)$ham, true);
        if (!is_array($cozum)) {
            echo json_encode(['basarili' => false, 'mesaj' => 'Geçerli JSON giriniz.']);
            exit;
        }
        if (liste_kaydet($kaynak, $tanim, $cozum)) {
            echo json_encode(['basarili' => true, 'mesaj' => 'JSON kaydedildi.']);
        } else {
            echo json_encode(['basarili' => false, 'mesaj' => 'JSON kaydedilemedi.']);
        }
        exit;
    }

    echo json_encode(['basarili' => false, 'mesaj' => 'Desteklenmeyen işlem.']);
    exit;
}

$veri_paketi = [];
foreach ($kaynak_tanimlari as $anahtar => $tanim) {
    $veri = liste_yukle($tanim);
    $veri_paketi[$anahtar] = [
        'etiket' => $tanim['etiket'],
        'alanlar' => $tanim['alanlar'],
        'veri' => $veri,
    ];
}

include __DIR__ . '/ust.php';
?>
<section class="kart admin-panel" data-scroll>
    <div class="admin-baslik">
        <h1>Hızlı Yönetim</h1>
        <p>Kaynak seç, satırı çift tıkla düzenle, odaktan çıkınca otomatik kaydedilsin. + Satır ile anında ekleme yap.</p>
    </div>
    <div class="sekme-menu" role="tablist">
        <?php foreach ($kaynak_tanimlari as $anahtar => $tanim): ?>
            <button type="button" class="buton buton-sekme" data-kaynak="<?= htmlspecialchars($anahtar) ?>" role="tab" aria-selected="<?= $aktif_kaynak === $anahtar ? 'true' : 'false' ?>">
                <?= htmlspecialchars($tanim['etiket']) ?>
            </button>
        <?php endforeach; ?>
    </div>
    <div class="admin-aksiyon">
        <button type="button" class="buton buton-ikincil" id="yeni-satir">+ Satır Ekle</button>
        <button type="button" class="buton buton-ikincil" id="json-indir">JSON İndir</button>
    </div>
    <div class="admin-bilgi" id="admin-bilgi" role="status" aria-live="polite"></div>
    <div class="yonetim-tablosu" id="admin-tablosu" aria-live="polite"></div>
    <div class="ham-json">
        <label for="ham-json-metin">Ham JSON</label>
        <textarea id="ham-json-metin" spellcheck="false" rows="8"></textarea>
        <div class="ham-json-aksiyon">
            <button type="button" class="buton" id="ham-json-kaydet">Ham JSON'u Kaydet</button>
        </div>
        <p class="ipucu">Kaydetmeden önce JSON biçiminin geçerli olduğundan emin olun. Her kayıtta <code>yedekler/</code> klasörüne otomatik yedek düşer.</p>
    </div>
    <input type="hidden" id="admin-csrf" value="<?= csrf_token() ?>">
</section>
<script type="application/json" id="admin-veri">
<?= json_encode([
    'aktif' => $aktif_kaynak,
    'kaynaklar' => $veri_paketi,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
<?php include __DIR__ . '/alt.php'; ?>
