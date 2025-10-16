# DeutschLern

DeutschLern, Almanca isimlerin artikellerini ve çoğullarını aralıklı tekrar (SRS) metoduyla öğretmek için hazırlanmış Laravel 10 + Livewire + Filament tabanlı bir öğrenme uygulamasıdır. Proje PHP 8.2, MySQL ve Tailwind CSS kullanır.

## Özellikler

- Laravel Breeze kimlik doğrulama akışı (giriş, kayıt, parola sıfırlama, e-posta doğrulama).
- Livewire destekli öğrenme kartı ve oyunlar: Artikel seç, çoğulu yaz, cloze ve sürükle-bırak.
- Yorumlarla belgelenmiş SRS motoru (ZOR/ORTA/KOLAY butonları, kolaylık katsayısı, leech tespiti).
- Filament Admin paneli ile kelime CRUD, CSV içe aktarma ve kullanıcı yönetimi.
- MySQL indeksleri sayesinde `user_words.due_at` ve `review_logs.reviewed_at` sorguları optimize.

## Kurulum

1. Depoyu klonlayın ve PHP / Node bağımlılıklarını yükleyin:
   ```bash
   composer install
   npm install
   ```
2. Ortam dosyasını hazırlayın ve uygulama anahtarını üretin:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Veritabanı ayarlarınızı `.env` dosyasında düzenleyin ve migrasyon + seed komutunu çalıştırın:
   ```bash
   php artisan migrate --seed
   ```
4. Vite ile varlıkları derleyin:
   ```bash
   npm run build
   ```
5. Uygulamayı çalıştırmak için aşağıdaki komutları ayrı terminallerde kullanabilirsiniz:
   ```bash
   php artisan serve
   npm run dev
   ```

### Varsayılan Giriş Bilgileri

- Admin e-posta: `admin@example.com`
- Parola: `admin12345`

## API Uçları

Tüm uçlar `auth:sanctum` ile korunur.

- `GET /api/learn/next` – sıradaki due veya yeni kelime.
- `POST /api/learn/answer` – SRS güncellemesi ve log kaydı.
- `GET /api/stats/overview` – günlük özet, doğruluk, zor kelimeler.
- `GET /api/games/pool?type=artikel|plural|cloze` – oyun havuzu için 20 kelime.
- `POST /api/games/score` – skor kaydı.

## Filament Admin

Filament paneli varsayılan olarak `/admin` yolunda yer alır. Burada kelime kayıtlarını yönetebilir, CSV içe aktarımı yapabilir ve kullanıcıları görüntüleyebilirsiniz.

## CSV İçe Aktarma

`storage/app/imports/words.csv` dosyasında örnek formatı bulabilirsiniz:

```
lemma,gender,plural,example_sentence,example_translation,level,tags
Haus,n,Häuser,Das Haus ist groß.,Ev büyük.,A1,"[""ev"",""yer""]"
Hund,m,Hunde,Der Hund bellt.,Köpek havlıyor.,A1,"[""hayvan""]"
Zeit,f,Zeiten,Die Zeit vergeht.,Zaman geçiyor.,A2,"[""soyut""]"
```

## Test ve Geliştirme

- Tüm testleri çalıştırmak için: `php artisan test`
- Kod stilini denetlemek için: `vendor/bin/pint`

## Teslimat

`php artisan migrate --seed` komutu veritabanını hazırlar ve en az 50 kelimelik örnek sözlüğü yükler. Uygulama, `.env.example`, migrasyonlar, seeder'lar ve model fabrikalarıyla birlikte dağıtıma hazırdır.
