<?php
$sayfa_baslik = 'Dersler';
require_once __DIR__ . '/yardimci.php';
$dersler = json_oku('dersler.json');
include __DIR__ . '/ust.php';
?>
<section class="kart">
    <h1>Ders Listesi</h1>
    <p>Pflegefachmann/frau eğitimine dair temel dersleri keşfedin. Her ders için özel quiz ve flashkart içerikleri mevcut.</p>
    <div class="tablo-kapsayici">
        <table class="tablo">
            <thead>
                <tr>
                    <th>Ad</th>
                    <th>Açıklama</th>
                    <th>Eylem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dersler as $ders): ?>
                    <tr>
                        <td><?= htmlspecialchars($ders['ad'] ?? '') ?></td>
                        <td><?= htmlspecialchars($ders['aciklama'] ?? '') ?></td>
                        <td><a class="buton" href="quiz.php?ders=<?= urlencode($ders['ad'] ?? '') ?>">Quiz</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/alt.php'; ?>
