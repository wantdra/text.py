<?php include __DIR__.'/parcalar/baslik.php'; ?>
<section class="hero">
  <h1>Tutkuyla Büyü — Çocuğunuzun gelişim yolculuğunda yanınızdayız.</h1>
  <p>Bilimsel temelli ama sade anlatımlarla ebeveynlik, etkinlik fikirleri ve gelişim dönüm noktaları bir arada.</p>
  <a class="btn" href="/kategoriler.php?tip=yazi">Hemen Başla</a>
</section>

<section class="ozet-4" aria-label="Site bölümleri">
  <article class="kutu"><h3>Ebeveyn Rehberi</h3><p>Günlük hayata uygun, kısa ve net öneriler.</p></article>
  <article class="kutu"><h3>Etkinlik Bankası</h3><p>Yaşa göre eğlenceli ve gelişim odaklı etkinlikler.</p></article>
  <article class="kutu"><h3>Dönüm Noktaları</h3><p>Her yaşta beklenen gelişimler tablosu.</p></article>
  <article class="kutu"><h3>Blog</h3><p>Güncel yazılar ve uzman görüşleri.</p></article>
</section>

<section class="yas-serit" aria-label="Yaşa göre hızlı filtreler">
  <a href="/kategoriler.php?tip=etkinlik&yas=0-2">0–2 yaş</a>
  <a href="/kategoriler.php?tip=etkinlik&yas=3-4">3–4 yaş</a>
  <a href="/kategoriler.php?tip=etkinlik&yas=5-6">5–6 yaş</a>
  <a href="/kategoriler.php?tip=etkinlik&yas=7+">7+ yaş</a>
</section>

<section class="son-yazilar" aria-labelledby="sonYazilarBaslik">
  <h2 id="sonYazilarBaslik">Son Yazılar</h2>
  <?php
    $yazilar = json_decode(file_get_contents(__DIR__.'/veri/yazilar.json'), true);
    if ($yazilar) {
      foreach(array_slice($yazilar,0,6) as $y){
        echo '<a class="kart" href="/icerik.php?tip=yazi&id='.$y['id'].'">'
            .'<h3>'.htmlspecialchars($y['baslik']).'</h3>'
            .'<p>'.htmlspecialchars($y['ozet']).'</p>'
            .'<div class="etiketler">'.implode(' ', array_map(fn($e)=>"<span class=\"rozet\">".htmlspecialchars($e)."</span>", $y['etiket'])).'</div>'
            .'</a>';
      }
    } else {
      echo '<p>Yazılar yüklenirken bir sorun oluştu.</p>';
    }
  ?>
</section>

<section class="bulten" aria-label="Bülten">
  <h2>Haftalık ebeveynlik ipuçları e-postanıza gelsin</h2>
  <p>Yalnızca içerik paylaşırız, verilerinizi üçüncü kişilerle paylaşmayız.</p>
  <form>
    <label class="sr-only" for="bulten-email">E-posta</label>
    <input id="bulten-email" type="email" placeholder="ornek@eposta.com" required>
    <button type="submit">Abone Ol</button>
  </form>
  <p class="kvkk">Formu göndererek KVKK/GDPR kapsamında kişisel verilerinizi yalnızca bilgilendirme amacıyla paylaşmamıza onay verirsiniz.</p>
</section>

<section class="kutu" aria-label="Gelişim dönüm noktaları">
  <h2>Dönüm Noktaları</h2>
  <table class="table">
    <thead>
      <tr><th>Yaş</th><th>Motor</th><th>Dil</th><th>Bilişsel</th><th>Sosyal-Duygusal</th></tr>
    </thead>
    <tbody>
      <tr><td>2 yaş</td><td>2-3 kelime koşu, zıplama</td><td>İki kelimelik cümleler</td><td>Basit eşleştirme oyunları</td><td>Paralel oyun, duygu taklidi</td></tr>
      <tr><td>3-4 yaş</td><td>Makası temel kullanım</td><td>Neden-sonuç soruları</td><td>Sıralama yapma</td><td>Hayali oyunlarda rol alma</td></tr>
      <tr><td>5-6 yaş</td><td>Top yakalama, ip atlama</td><td>Kısa hikâye anlatma</td><td>Basit problem çözme</td><td>Empati kurma, işbirliği</td></tr>
    </tbody>
  </table>
  <p>Daha fazla detay için <a href="/kategoriler.php?tip=yazi&etiket=duygular">pozitif disiplin ve duygu yönetimi rehberlerini</a> inceleyin.</p>
</section>

<section class="kutu" aria-label="Sık sorulan sorular">
  <h2>Sık Sorulanlar</h2>
  <details>
    <summary>Ne kadar ekran süresi uygundur?</summary>
    <p>2 yaş altı için önerilen ekran süresi minimum; daha büyük çocuklarda günde 1 saate kadar kaliteli içerikler tercih edilmelidir.</p>
  </details>
  <details>
    <summary>Kardeş kıskançlığıyla nasıl baş ederim?</summary>
    <p>Her iki çocuğa da özel zaman ayırın, duygularını adlandırın ve işbirliğini teşvik eden ortak görevler oluşturun.</p>
  </details>
  <details>
    <summary>Rutin kartlarını hangi yaşta kullanmalıyım?</summary>
    <p>3 yaş itibarıyla sabah ve akşam rutinlerini görselleştirmek işbirliğini artırır; kartları birlikte hazırlamak motivasyonu güçlendirir.</p>
  </details>
</section>
<?php include __DIR__.'/parcalar/altlik.php'; ?>
