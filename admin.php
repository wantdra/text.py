<?php
$dataFile = __DIR__ . '/data/redemittel.json';
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

$message = null;
$messageType = null;
$germanValue = '';
$turkishValue = '';
$categoryValue = '';
$difficultyValue = '';
$usageValue = '';

function sanitizeInput($value) {
    return trim(strip_tags((string) $value));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $german = sanitizeInput($_POST['german'] ?? '');
    $turkish = sanitizeInput($_POST['turkish'] ?? '');
    $category = sanitizeInput($_POST['category'] ?? '');
    $difficulty = sanitizeInput($_POST['difficulty'] ?? '');
    $usage = trim(strip_tags((string)($_POST['usage'] ?? '')));

    $germanValue = $german;
    $turkishValue = $turkish;
    $categoryValue = $category;
    $difficultyValue = $difficulty;
    $usageValue = $usage;

    $allowedDifficulties = ['Kolay', 'Orta', 'Zor'];

    if ($german === '' || $turkish === '' || $category === '' || $difficulty === '') {
        $messageType = 'error';
        $message = 'Lütfen tüm zorunlu alanları doldurun.';
    } elseif (!in_array($difficulty, $allowedDifficulties, true)) {
        $messageType = 'error';
        $message = 'Geçerli bir zorluk seviyesi seçin.';
    } else {
        $entries = json_decode(file_get_contents($dataFile), true) ?? [];
        $slugBase = preg_replace('~[^a-z0-9]+~i', '-', strtolower($german));
        $slugBase = trim($slugBase, '-') ?: 'redemittel';
        $id = $slugBase;
        $counter = 1;
        $existingIds = array_column($entries, 'id');
        while (in_array($id, $existingIds, true)) {
            $counter += 1;
            $id = $slugBase . '-' . $counter;
        }

        $entries[] = [
            'id' => $id,
            'german' => $german,
            'turkish' => $turkish,
            'category' => $category,
            'difficulty' => $difficulty,
            'usage' => $usage,
        ];

        usort($entries, function ($a, $b) {
            $categoryCompare = strcmp($a['category'], $b['category']);
            if ($categoryCompare !== 0) {
                return $categoryCompare;
            }
            return strcmp($a['german'], $b['german']);
        });

        file_put_contents($dataFile, json_encode($entries, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $messageType = 'success';
        $message = 'Yeni Redemittel başarıyla eklendi!';
    }
}

$entries = json_decode(file_get_contents($dataFile), true) ?? [];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redemittel Admin</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --bg: #0f172a;
            --surface: #111c38;
            --surface-light: #1e2b4a;
            --text: #f8fafc;
            --muted: #cbd5f5;
            --radius: 16px;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.25), rgba(14, 116, 144, 0.35)), var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .panel {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            border-radius: var(--radius);
            padding: 2.5rem;
            width: min(960px, 96vw);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.45);
        }
        h1 {
            margin: 0 0 1rem;
            font-size: clamp(2rem, 5vw, 2.6rem);
        }
        p {
            margin-top: 0;
            color: var(--muted);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem;
            margin-bottom: 1.5rem;
        }
        label {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-weight: 600;
        }
        label.full {
            grid-column: 1 / -1;
        }
        input[type="text"],
        textarea,
        select {
            padding: 0.75rem 0.9rem;
            border-radius: 10px;
            border: 1px solid rgba(148, 163, 184, 0.4);
            background: rgba(15, 23, 42, 0.6);
            color: var(--text);
            font-size: 1rem;
            transition: border 0.2s ease, box-shadow 0.2s ease;
            resize: vertical;
            min-height: 52px;
        }
        textarea { min-height: 120px; }
        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: rgba(96, 165, 250, 0.8);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.35);
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
        }
        .btn-primary {
            background: var(--primary);
            color: #fff;
            padding: 0.85rem 1.8rem;
            border-radius: 999px;
            border: none;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        .btn-primary:hover { background: var(--primary-dark); box-shadow: 0 10px 24px rgba(37, 99, 235, 0.35); }
        .btn-link {
            color: var(--muted);
            text-decoration: none;
            font-weight: 500;
        }
        .message {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.2rem;
            font-weight: 600;
        }
        .message.success { background: rgba(16, 185, 129, 0.16); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.35); }
        .message.error { background: rgba(239, 68, 68, 0.16); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.35); }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: rgba(15, 23, 42, 0.6);
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
            text-align: left;
        }
        th {
            font-weight: 600;
            color: rgba(226, 232, 240, 0.9);
            background: rgba(37, 99, 235, 0.15);
        }
        tr:last-child td { border-bottom: none; }
        .empty-row {
            text-align: center;
            color: var(--muted);
            padding: 1.5rem;
        }
        @media (max-width: 720px) {
            .panel { padding: 1.5rem; }
            table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>
    <div class="panel">
        <h1>Redemittel Yönetimi</h1>
        <p>Yeni Almanca ifadeler ekleyin, Türkçe anlamlarıyla birlikte yayınlayın ve ana sayfadaki listeyi güncel tutun.</p>
        <?php if ($message): ?>
            <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post" class="form" id="entryForm">
            <div class="form-grid">
                <label>
                    Almanca İfade
                    <input type="text" name="german" required maxlength="120" placeholder="Örn. Ich hätte gern einen Kaffee." value="<?= htmlspecialchars($germanValue) ?>">
                </label>
                <label>
                    Türkçe Anlamı
                    <input type="text" name="turkish" required maxlength="160" placeholder="Örn. Bir kahve almak isterim." value="<?= htmlspecialchars($turkishValue) ?>">
                </label>
                <label>
                    Kategori
                    <input type="text" name="category" required maxlength="80" placeholder="Örn. Restoran" value="<?= htmlspecialchars($categoryValue) ?>">
                </label>
                <label>
                    Zorluk
                    <select name="difficulty" required>
                        <option value="">Seçiniz</option>
                        <option value="Kolay" <?= $difficultyValue === 'Kolay' ? 'selected' : '' ?>>Kolay</option>
                        <option value="Orta" <?= $difficultyValue === 'Orta' ? 'selected' : '' ?>>Orta</option>
                        <option value="Zor" <?= $difficultyValue === 'Zor' ? 'selected' : '' ?>>Zor</option>
                    </select>
                </label>
                <label class="full">
                    Kullanım Notu (isteğe bağlı)
                    <textarea name="usage" placeholder="İfadenin hangi durumda kullanıldığını kısaca açıklayın."><?= htmlspecialchars($usageValue) ?></textarea>
                </label>
            </div>
            <div class="actions">
                <button type="submit" class="btn-primary">Redemittel Ekle</button>
                <a href="index.php" class="btn-link">← Ana sayfaya dön</a>
            </div>
        </form>
        <table aria-label="Redemittel listesi">
            <thead>
                <tr>
                    <th>Almanca</th>
                    <th>Türkçe</th>
                    <th>Kategori</th>
                    <th>Zorluk</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($entries)): ?>
                    <tr>
                        <td colspan="4" class="empty-row">Henüz kayıtlı ifade bulunmuyor.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($entries as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['german']) ?></td>
                            <td><?= htmlspecialchars($item['turkish']) ?></td>
                            <td><?= htmlspecialchars($item['category']) ?></td>
                            <td><?= htmlspecialchars($item['difficulty']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script>
        document.getElementById('entryForm').addEventListener('submit', function (event) {
            const requiredFields = this.querySelectorAll('[required]');
            for (const field of requiredFields) {
                if (!field.value.trim()) {
                    event.preventDefault();
                    field.focus();
                    alert('Lütfen tüm zorunlu alanları doldurun.');
                    return;
                }
            }
        });
    </script>
</body>
</html>
