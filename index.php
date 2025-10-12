<?php
$dataFile = __DIR__ . '/data/redemittel.json';
if (!file_exists($dataFile)) {
    $seedData = [
        [
            'id' => 'begrussung-1',
            'german' => 'Guten Morgen!',
            'turkish' => 'Günaydın!',
            'category' => 'Selamlaşma',
            'difficulty' => 'Kolay',
            'usage' => 'Güne başlarken selamlaşmak için kullanılır.'
        ],
        [
            'id' => 'danke-1',
            'german' => 'Vielen Dank für Ihre Hilfe.',
            'turkish' => 'Yardımınız için çok teşekkür ederim.',
            'category' => 'Teşekkür',
            'difficulty' => 'Kolay',
            'usage' => 'Yardım için kibar bir teşekkür ifadesi.'
        ],
        [
            'id' => 'meeting-1',
            'german' => 'Könnten wir den Termin verschieben?',
            'turkish' => 'Randevuyu erteleyebilir miyiz?',
            'category' => 'İş Görüşmesi',
            'difficulty' => 'Orta',
            'usage' => 'Resmi bir toplantı değişikliği talebi.'
        ],
        [
            'id' => 'problem-1',
            'german' => 'Ich habe ein Problem mit der Bestellung.',
            'turkish' => 'Siparişle ilgili bir sorunum var.',
            'category' => 'Müşteri Hizmetleri',
            'difficulty' => 'Orta',
            'usage' => 'Bir müşteri hizmetleri temsilcisine sorunu bildirmek için.'
        ],
        [
            'id' => 'presentation-1',
            'german' => 'Lassen Sie mich das genauer erklären.',
            'turkish' => 'Bunu daha ayrıntılı açıklamama izin verin.',
            'category' => 'Sunum',
            'difficulty' => 'Zor',
            'usage' => 'Sunum sırasında bir konuyu derinlemesine anlatmak için.'
        ],
        [
            'id' => 'everyday-1',
            'german' => 'Können Sie das bitte wiederholen?',
            'turkish' => 'Lütfen bunu tekrar edebilir misiniz?',
            'category' => 'Günlük Konuşma',
            'difficulty' => 'Kolay',
            'usage' => 'Anlaşılamayan bir ifadeyi tekrar istemek için.'
        ],
        [
            'id' => 'travel-1',
            'german' => 'Wo befindet sich die nächste U-Bahn-Station?',
            'turkish' => 'En yakın metro istasyonu nerede?',
            'category' => 'Seyahat',
            'difficulty' => 'Orta',
            'usage' => 'Yol tarifi istemek için.'
        ],
        [
            'id' => 'emergency-1',
            'german' => 'Rufen Sie bitte einen Arzt!',
            'turkish' => 'Lütfen bir doktor çağırın!',
            'category' => 'Acil Durum',
            'difficulty' => 'Zor',
            'usage' => 'Acil yardım gereken durumlarda.'
        ]
    ];
    if (!is_dir(dirname($dataFile))) {
        mkdir(dirname($dataFile), 0775, true);
    }
    file_put_contents($dataFile, json_encode($seedData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

$entries = json_decode(file_get_contents($dataFile), true) ?? [];

$categories = [];
$difficulties = [];
$normalizeDifficulty = function ($difficulty) {
    if (function_exists('mb_strtolower')) {
        return mb_strtolower($difficulty, 'UTF-8');
    }
    return strtolower($difficulty);
};
foreach ($entries as $entry) {
    $categories[$entry['category']] = true;
    $difficulties[$entry['difficulty']] = true;
}
ksort($categories);
ksort($difficulties);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redemittel - Almanca İfadeler</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<header class="site-header">
    <div class="container">
        <div class="branding">
            <h1>Redemittel</h1>
            <p class="tagline">Almanca ifadeleri keşfedin, tekrar edin ve favorilerinize ekleyin.</p>
        </div>
        <nav class="site-nav">
            <a href="index.php" class="nav-link active">Ana Sayfa</a>
            <a href="admin.php" class="nav-link">Admin Paneli</a>
        </nav>
    </div>
</header>
<main class="container">
    <section class="filters" aria-label="Filtreler">
        <div class="filter-group">
            <label for="categoryFilter">Kategori</label>
            <select id="categoryFilter">
                <option value="">Tümü</option>
                <?php foreach (array_keys($categories) as $category): ?>
                    <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label for="difficultyFilter">Zorluk</label>
            <select id="difficultyFilter">
                <option value="">Tümü</option>
                <?php foreach (array_keys($difficulties) as $difficulty): ?>
                    <option value="<?= htmlspecialchars($difficulty) ?>"><?= htmlspecialchars($difficulty) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group search-group">
            <label for="searchInput">Arama</label>
            <input type="search" id="searchInput" placeholder="Almanca veya Türkçe ifadelerde ara">
        </div>
        <div class="filter-group toggle-group">
            <input type="checkbox" id="favoritesOnly" />
            <label for="favoritesOnly">Sadece Favoriler</label>
        </div>
    </section>

    <section class="cards" id="redemittelList" aria-live="polite">
        <?php foreach ($entries as $entry): ?>
            <article class="card" data-category="<?= htmlspecialchars($entry['category']) ?>" data-difficulty="<?= htmlspecialchars($entry['difficulty']) ?>" data-id="<?= htmlspecialchars($entry['id']) ?>">
                <div class="card-header">
                    <h2 class="german"><?= htmlspecialchars($entry['german']) ?></h2>
                    <span class="difficulty difficulty-<?= htmlspecialchars($normalizeDifficulty($entry['difficulty'])) ?>"><?= htmlspecialchars($entry['difficulty']) ?></span>
                </div>
                <p class="turkish"><?= htmlspecialchars($entry['turkish']) ?></p>
                <?php if (!empty($entry['usage'])): ?>
                    <p class="usage"><?= htmlspecialchars($entry['usage']) ?></p>
                <?php endif; ?>
                <div class="card-actions">
                    <button type="button" class="btn-secondary pronounce" data-text="<?= htmlspecialchars($entry['german']) ?>">Sesli Oku</button>
                    <button type="button" class="btn-primary favorite-toggle" aria-pressed="false">Favorilere Ekle</button>
                </div>
                <span class="category-tag"><?= htmlspecialchars($entry['category']) ?></span>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="info">
        <h2>Nasıl Çalışır?</h2>
        <ol>
            <li>Filtreleri kullanarak ilginizi çeken kategori veya zorluk seviyesini seçin.</li>
            <li>"Sesli Oku" butonuna tıklayarak Google TTS ile doğru telaffuzu dinleyin.</li>
            <li>Favorilere eklediğiniz ifadeleri tekrar ziyaret edip tekrar yapın.</li>
        </ol>
    </section>
</main>
<footer class="site-footer">
    <div class="container">
        <p>© <?php echo date('Y'); ?> Redemittel Platformu · Almanca ifadeleri öğrenmek artık daha kolay.</p>
    </div>
</footer>
<script>window.REDEMITTEL_DATA = <?php echo json_encode($entries, JSON_UNESCAPED_UNICODE); ?>;</script>
<script src="assets/app.js" defer></script>
</body>
</html>
