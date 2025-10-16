# DeutschLern

DeutschLern, Almanca isimlerin artikel ve çoğullarını aralıklı tekrar (SRS) ve oyunlarla pekiştirmek için hazırlanmış Laravel 10 + Livewire + Filament tabanlı bir öğrenme uygulamasıdır.

## Özellikler

- Laravel Breeze kimlik doğrulama altyapısı (kurulum sonrası `php artisan breeze:install`).
- Livewire destekli öğrenme kartı ve mini oyunlar (Artikel seçme, çoğul yazma, cloze, sürükle-bırak).
- Özel SRS algoritması: ZOR / ORTA / KOLAY butonları, kolaylık katsayısı, leech yönetimi.
- Filament Admin paneli ile kelime CRUD, CSV içe aktarma ve kullanıcı yönetimi.
- MySQL için optimize edilmiş indeksler (`user_words.due_at`, `review_logs.reviewed_at`).

## Kurulum

1. Depoyu klonlayın ve bağımlılıkları yükleyin:
   ```bash
   composer install
   npm install && npm run build
   ```
2. Ortam dosyasını hazırlayın:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Veritabanını oluşturun ve migrasyon + seed çalıştırın:
   ```bash
   php artisan migrate --seed
   ```
4. Geliştirme sunucusunu başlatın:
   ```bash
   php artisan serve
   npm run dev
   ```

Admin paneline giriş için varsayılan kullanıcı:

- E-posta: `admin@example.com`
- Parola: `admin12345`

## CSV İçe Aktarma

`storage/app/imports/words.csv` dosyası örnek formatı gösterir. Kolonlar:

```
lemma,gender,plural,example_sentence,example_translation,level,tags
Haus,n,Häuser,Das Haus ist groß.,Ev büyük.,A1,"[""ev"",""yer""]"
Hund,m,Hunde,Der Hund bellt.,Köpek havlıyor.,A1,"[""hayvan""]"
Zeit,f,Zeiten,Die Zeit vergeht.,Zaman geçiyor.,A2,"[""soyut""]"
```

Filament panelinde "CSV İçe Aktar" eylemi ile yükleyebilirsiniz.

## Test Kullanımı

- `/` dashboard ekranı günlük tekrarları ve "Öğrenmeye Başla" çağrısını gösterir.
- `/learn` sayfasında Livewire kartı ile due olan veya yeni kelimeleri çalışabilirsiniz.
- API uçları `auth:sanctum` koruması altındadır:
  - `GET /api/learn/next`
  - `POST /api/learn/answer`
  - `GET /api/stats/overview`
  - `GET /api/games/pool?type=artikel|plural|cloze`
  - `POST /api/games/score`

## SRS Notları

Kod içinde SRS hesaplama adımları ayrıntılı yorumlarla açıklanmıştır (`App\Services\SrsService`).
