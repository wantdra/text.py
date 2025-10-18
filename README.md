# Pflegefachmann/frau Quiz & Flashkart

Modern, hızlı ve mobil uyumlu bir hemşirelik öğrenme platformu.

## Kurulum
1. Bu klasörü web sunucun veya çalışma dizinine kopyala.
2. Terminalde `php -S localhost:8000` komutunu çalıştır.
3. Tarayıcından [http://localhost:8000/anasayfa.php](http://localhost:8000/anasayfa.php) adresine git.

## Demo Hesaplar
- **Admin:** `admin@site.local` / `admin123`
- **Öğrenci:** `ogrenci@site.local` / `ogrenci123`

## Veri Dosyaları
Tüm içerikler `veriler/` klasöründe JSON formatında tutulur:
- `kullanicilar.json`
- `dersler.json`
- `sorular.json`
- `kartlar.json`
- `oturumlar.json`

Her değişiklik öncesi sistem otomatik olarak `yedekler/` klasörüne zaman damgalı yedek bırakır; yine de düzenleme yapmadan önce dosyaların bir kopyasını almak önerilir. Yönetim paneli üzerinden yeni ders, soru, kart ve kullanıcı ekleyebilirsin.
