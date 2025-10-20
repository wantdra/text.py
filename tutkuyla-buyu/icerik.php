<?php
$tip = $_GET['tip'] ?? 'yazi';
$id = $_GET['id'] ?? '';
$veriDosya = $tip === 'etkinlik' ? __DIR__.'/veri/etkinlikler.json' : __DIR__.'/veri/yazilar.json';
$kayitlar = json_decode(file_get_contents($veriDosya), true) ?? [];
$icerik = null;
foreach ($kayitlar as $k) {
  if ($k['id'] === $id) {
    $icerik = $k;
    break;
  }
}
if(!$icerik){
  include __DIR__.'/parcalar/baslik.php';
  echo '<section class="hero"><h1>İçerik bulunamadı</h1><p>Aradığınız sayfa kaldırılmış olabilir.</p></section>';
  include __DIR__.'/parcalar/altlik.php';
  exit;
}
include __DIR__.'/parcalar/baslik.php';
?>
<article class="icerik-detay">
  <header class="hero">
    <h1><?= htmlspecialchars($icerik['baslik']) ?></h1>
    <?php if(isset($icerik['etiket'])): ?>
      <p>
        <?php foreach($icerik['etiket'] as $etiket): ?>
          <span class="rozet"><?= htmlspecialchars($etiket) ?></span>
        <?php endforeach; ?>
      </p>
    <?php endif; ?>
  </header>
  <?php if($tip === 'etkinlik'): ?>
    <section class="kutu">
      <h2>Yaş Grubu</h2>
      <p><?= htmlspecialchars($icerik['yas']) ?></p>
    </section>
    <section class="kutu">
      <h2>Amaç</h2>
      <p><?= htmlspecialchars($icerik['amac']) ?></p>
    </section>
    <section class="kutu">
      <h2>Malzemeler</h2>
      <ul>
        <?php foreach($icerik['malzemeler'] as $madde): ?>
          <li><?= htmlspecialchars($madde) ?></li>
        <?php endforeach; ?>
      </ul>
    </section>
    <section class="kutu">
      <h2>Adımlar</h2>
      <ol>
        <?php foreach($icerik['adimlar'] as $adim): ?>
          <li><?= htmlspecialchars($adim) ?></li>
        <?php endforeach; ?>
      </ol>
    </section>
    <section class="kutu">
      <h2>Kazanımlar</h2>
      <ul>
        <?php foreach(($icerik['kazanımlar'] ?? []) as $kazanım): ?>
          <li><?= htmlspecialchars($kazanım) ?></li>
        <?php endforeach; ?>
      </ul>
    </section>
    <?php if(!empty($icerik['uyarlama'])): ?>
      <section class="kutu">
        <h2>Uyarlama</h2>
        <p><?= htmlspecialchars($icerik['uyarlama']) ?></p>
      </section>
    <?php endif; ?>
    <?php if(!empty($icerik['uyari'])): ?>
      <section class="kutu">
        <h2>Uyarılar</h2>
        <p><?= htmlspecialchars($icerik['uyari']) ?></p>
      </section>
    <?php endif; ?>
  <?php else: ?>
    <section class="kutu">
      <?= $icerik['icerik'] ?>
    </section>
  <?php endif; ?>
  <p><a class="btn-ikincil" href="/kategoriler.php?tip=<?= htmlspecialchars($tip) ?>">‹ Listeye dön</a></p>
</article>
<?php include __DIR__.'/parcalar/altlik.php'; ?>
