<?php
$sorgu = trim($_GET['q'] ?? '');
$sonuclar = [];
if($sorgu){
  $dosyalar = [
    ['tip'=>'yazi','path'=>__DIR__.'/veri/yazilar.json'],
    ['tip'=>'etkinlik','path'=>__DIR__.'/veri/etkinlikler.json']
  ];
  foreach($dosyalar as $d){
    $liste = json_decode(file_get_contents($d['path']), true) ?? [];
    foreach($liste as $kayit){
      $aranan = strtolower($kayit['baslik'].' '.($kayit['ozet'] ?? '').' '.implode(' ', $kayit['etiket'] ?? []));
      if(strpos($aranan, strtolower($sorgu)) !== false){
        $kayit['tip'] = $d['tip'];
        $sonuclar[] = $kayit;
      }
    }
  }
}
include __DIR__.'/parcalar/baslik.php';
?>
<section class="hero">
  <h1>Arama</h1>
  <form method="get" class="form-grup" style="max-width:420px;margin:0 auto;">
    <label class="sr-only" for="q">Arama</label>
    <input id="q" name="q" value="<?= htmlspecialchars($sorgu) ?>" placeholder="Duygu düzenleme, kardeş kıskançlığı...">
  </form>
</section>
<section class="blog-listesi">
  <?php if(!$sorgu): ?>
    <p>İhtiyacınız olan konuyu yazın.</p>
  <?php elseif(!$sonuclar): ?>
    <p>"<?= htmlspecialchars($sorgu) ?>" için sonuç bulunamadı.</p>
  <?php else: ?>
    <?php foreach($sonuclar as $s): ?>
      <a class="kart" href="/icerik.php?tip=<?= htmlspecialchars($s['tip']) ?>&id=<?= htmlspecialchars($s['id']) ?>">
        <h3><?= htmlspecialchars($s['baslik']) ?></h3>
        <?php if(isset($s['ozet'])): ?><p><?= htmlspecialchars($s['ozet']) ?></p><?php endif; ?>
        <div class="etiketler">
          <?php foreach(($s['etiket'] ?? []) as $e): ?>
            <span class="rozet"><?= htmlspecialchars($e) ?></span>
          <?php endforeach; ?>
        </div>
      </a>
    <?php endforeach; ?>
  <?php endif; ?>
</section>
<?php include __DIR__.'/parcalar/altlik.php'; ?>
