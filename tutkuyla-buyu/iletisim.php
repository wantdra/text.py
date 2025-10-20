<?php include __DIR__.'/parcalar/baslik.php'; ?>
<section class="hero">
  <h1>İletişim</h1>
  <p>Randevu, atölye veya işbirliği taleplerinizi iletmek için formu doldurun. 48 saat içinde dönüş yapılır.</p>
</section>
<form class="kutu" method="post" action="#">
  <div class="form-grup">
    <label for="adsoyad">Ad Soyad</label>
    <input id="adsoyad" name="adsoyad" required>
  </div>
  <div class="form-grup">
    <label for="email">E-posta</label>
    <input id="email" name="email" type="email" required>
  </div>
  <div class="form-grup">
    <label for="mesaj">Mesajınız</label>
    <textarea id="mesaj" name="mesaj" required></textarea>
  </div>
  <label class="kvkk"><input type="checkbox" required> KVKK/GDPR kapsamında kişisel verilerimin iletişim amacıyla işlenmesini onaylıyorum.</label>
  <button class="btn" type="submit">Gönder</button>
  <p class="kvkk">Bu form yalnızca bilgi vermek için kullanılır; veriler üçüncü kişilerle paylaşılmaz.</p>
</form>
<?php include __DIR__.'/parcalar/altlik.php'; ?>
