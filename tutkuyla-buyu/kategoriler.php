<?php
$tip = $_GET['tip'] ?? 'yazi';
$yas = $_GET['yas'] ?? null;
$etiket = $_GET['etiket'] ?? null;
$dosya = $tip === 'etkinlik' ? __DIR__.'/veri/etkinlikler.json' : __DIR__.'/veri/yazilar.json';
$kayitlar = json_decode(file_get_contents($dosya), true) ?? [];
$filtreli = array_filter($kayitlar, function($k) use ($tip, $yas, $etiket){
  $yasUyar = true;
  if($yas && isset($k['yas'])){
    $yasUyar = strpos($k['yas'], $yas) !== false;
  } elseif($yas) {
    $yasUyar = false;
  }
  $etiketUyar = true;
  if($etiket && isset($k['etiket'])){
    $etiketUyar = in_array($etiket, $k['etiket']);
  }
  return $yasUyar && $etiketUyar;
});
$baslik = $tip === 'etkinlik' ? 'Etkinlik Bankası' : 'Ebeveyn Rehberleri';
include __DIR__.'/parcalar/baslik.php';
?>
<section class="hero">
  <h1><?= $baslik ?></h1>
  <p><?= $tip === 'etkinlik' ? 'Yaşa ve etikete göre filtrelenmiş etkinlik önerileri.' : 'Bilimsel temelli, uygulanabilir ebeveyn rehberleri.' ?></p>
</section>
<?php if($tip === 'etkinlik'): ?>
  <section class="yas-serit">
    <a href="?tip=<?= $tip ?>&yas=0-2">0–2</a>
    <a href="?tip=<?= $tip ?>&yas=3-4">3–4</a>
    <a href="?tip=<?= $tip ?>&yas=5-6">5–6</a>
    <a href="?tip=<?= $tip ?>&yas=7+">7+</a>
    <a href="?tip=<?= $tip ?>">Tümü</a>
  </section>
<?php endif; ?>
<section class="blog-listesi" aria-label="İçerik listesi">
  <?php if(!$filtreli): ?>
    <p>Filtrenize uygun içerik bulunamadı.</p>
  <?php else: ?>
    <?php foreach($filtreli as $k): ?>
      <a class="kart" href="/icerik.php?tip=<?= htmlspecialchars($tip) ?>&id=<?= htmlspecialchars($k['id']) ?>">
        <h3><?= htmlspecialchars($k['baslik']) ?></h3>
        <?php if(isset($k['ozet'])): ?><p><?= htmlspecialchars($k['ozet']) ?></p><?php endif; ?>
        <?php if(isset($k['amac'])): ?><p><?= htmlspecialchars($k['amac']) ?></p><?php endif; ?>
        <?php if(isset($k['yas'])): ?><p><strong>Yaş:</strong> <?= htmlspecialchars($k['yas']) ?></p><?php endif; ?>
        <div class="etiketler">
          <?php foreach(($k['etiket'] ?? []) as $e): ?>
            <span class="rozet"><?= htmlspecialchars($e) ?></span>
          <?php endforeach; ?>
        </div>
      </a>
    <?php endforeach; ?>
  <?php endif; ?>
</section>
<?php include __DIR__.'/parcalar/altlik.php'; ?>
