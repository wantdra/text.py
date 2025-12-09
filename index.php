<?php
session_start();
require_once __DIR__ . '/db.php';

function sanitize(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function getCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }

    return $_SESSION['csrf_token'];
}

function validateCsrf(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function escapeOutput(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function slugifyUser(string $username): string
{
    $slug = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($username));
    return trim($slug, '-') ?: bin2hex(random_bytes(4));
}

$pdo = db();
$errors = [];
$flash = '';

function findUserByKey(PDO $pdo, string $userKey): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM users WHERE user_key = :user_key LIMIT 1');
    $stmt->execute(['user_key' => $userKey]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function getKnownWordIds(PDO $pdo, int $userId): array
{
    $stmt = $pdo->prepare('SELECT word_id FROM known_words WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $userId]);
    return array_map(fn($row) => (int)$row['word_id'], $stmt->fetchAll());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type'])) {
    if (!validateCsrf($_POST['csrf'] ?? '')) {
        $errors[] = 'Güvenlik doğrulaması başarısız oldu.';
    } elseif ($_POST['form_type'] === 'login') {
        $username = sanitize($_POST['username'] ?? '');

        if (strlen($username) < 2) {
            $errors[] = 'Lütfen en az 2 karakterlik bir isim girin.';
        } else {
            $userKey = slugifyUser($username);
            $user = findUserByKey($pdo, $userKey);
            $today = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day'));

            if (!$user) {
                $insert = $pdo->prepare('INSERT INTO users (user_key, name, streak, last_seen, created_at, reviewed_today) VALUES (:user_key, :name, 1, :last_seen, NOW(), 0)');
                $insert->execute([
                    'user_key' => $userKey,
                    'name' => $username,
                    'last_seen' => $today,
                ]);
            } else {
                $streak = (int)($user['streak'] ?? 1);
                $lastSeen = $user['last_seen'];

                if ($lastSeen === $yesterday) {
                    $streak += 1;
                } elseif ($lastSeen !== $today) {
                    $streak = 1;
                }

                $update = $pdo->prepare('UPDATE users SET streak = :streak, last_seen = :today, reviewed_today = CASE WHEN last_seen = :today THEN reviewed_today ELSE 0 END WHERE id = :id');
                $update->execute([
                    'streak' => $streak,
                    'today' => $today,
                    'id' => $user['id'],
                ]);
            }

            $_SESSION['user_key'] = $userKey;
            $flash = 'Hoş geldin ' . $username . '!';
        }
    } elseif ($_POST['form_type'] === 'known' && isset($_SESSION['user_key'])) {
        $wordId = (int)($_POST['word_id'] ?? 0);
        $userKey = $_SESSION['user_key'];
        $user = findUserByKey($pdo, $userKey);

        if ($wordId > 0 && $user) {
            $wordExists = $pdo->prepare('SELECT id FROM words WHERE id = :id');
            $wordExists->execute(['id' => $wordId]);
            if ($wordExists->fetch()) {
                $knownInsert = $pdo->prepare('INSERT IGNORE INTO known_words (user_id, word_id) VALUES (:user_id, :word_id)');
                $knownInsert->execute([
                    'user_id' => $user['id'],
                    'word_id' => $wordId,
                ]);

                $increment = $pdo->prepare('UPDATE users SET reviewed_today = reviewed_today + 1, last_seen = :today WHERE id = :id');
                $increment->execute([
                    'today' => date('Y-m-d'),
                    'id' => $user['id'],
                ]);

                $flash = 'Kelime listene eklendi.';
            }
        }
    }
}

$currentUser = null;
if (isset($_SESSION['user_key'])) {
    $currentUser = findUserByKey($pdo, $_SESSION['user_key']);
}

$wordsStmt = $pdo->query('SELECT id, german, turkish, sentence FROM words ORDER BY id ASC');
$words = $wordsStmt->fetchAll();

$messagesStmt = $pdo->query('SELECT body FROM messages ORDER BY id ASC');
$messages = array_column($messagesStmt->fetchAll(), 'body');

$knownIds = [];
if ($currentUser) {
    $knownIds = getKnownWordIds($pdo, (int)$currentUser['id']);
}

$knownCount = count($knownIds);
$totalCount = count($words);
$remaining = max($totalCount - $knownCount, 0);
$progressPercent = $totalCount > 0 ? round(($knownCount / $totalCount) * 100) : 0;

$recommendation = 'Her gün 15 kelimeyle devam et, 1 ayda büyük yol kat edersin.';
if ($knownCount > 0 && $totalCount > 0) {
    $dailyTarget = max(5, min(25, intval(($remaining / 30) + 1)));
    $daysLeft = $dailyTarget > 0 ? (int)ceil($remaining / $dailyTarget) : 0;
    $targetDate = $daysLeft > 0 ? date('d M Y', strtotime("+{$daysLeft} days")) : 'bugün';
    $recommendation = "Günde {$dailyTarget} kelime ile ilerlersen yaklaşık {$daysLeft} günde bitirirsin. Tahmini bitiş: {$targetDate}";
}

$randomMessage = $messages ? $messages[array_rand($messages)] : 'Motivasyon için yeni mesaj ekleyin.';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almanca Kalıp Uygulaması</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <h2>Almanca</h2>
        <nav>
            <a href="#" class="active">Giriş</a>
            <a href="#words">Kelimeler</a>
            <a href="#review">Tekrar Et</a>
            <a href="#progress">İlerleme</a>
            <a href="#reminders">Hatırlatıcı</a>
            <a href="#account">Hesabım</a>
            <a href="admin.php">Admin</a>
        </nav>
    </aside>

    <main class="main">
        <div class="top-bar">
            <h1>Giriş</h1>
            <div class="top-controls">
                <div class="lang-toggle">TR → DE</div>
            </div>
        </div>

        <?php if ($flash): ?>
            <div class="notice success"><?php echo escapeOutput($flash); ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="notice error"><?php echo escapeOutput(implode(' ', $errors)); ?></div>
        <?php endif; ?>

        <?php if (!$currentUser): ?>
            <section class="auth-card">
                <h2>Başlamak için adını yaz</h2>
                <p>İlerlemeni saklayalım, sana özel öneriler oluşturalım.</p>
                <form method="POST" class="auth-form">
                    <input type="hidden" name="form_type" value="login">
                    <input type="hidden" name="csrf" value="<?php echo getCsrfToken(); ?>">
                    <label for="username">İsmin</label>
                    <input id="username" name="username" type="text" required maxlength="64" placeholder="Örn: Ayşe" />
                    <button type="submit" class="btn btn-primary">Devam Et</button>
                </form>
            </section>
        <?php else: ?>
            <section class="hero">
                <h2>Hoş geldin, <?php echo escapeOutput($currentUser['name']); ?>!</h2>
                <p>Her gün birkaç dakika, düzenli tekrar ve akıllı ezberleme sistemi ile Almanca'yı ustala.</p>
            </section>

            <section class="content" id="account">
                <div class="streak-section">
                    <div class="streak-card">
                        <div class="streak-header">
                            <span class="streak-icon">🔥</span>
                        </div>
                        <div class="streak-number" id="streakNumber"><?php echo (int)$currentUser['streak']; ?></div>
                        <div class="streak-label">gün üst üste çalıştın! Harikasın!</div>
                    </div>

                    <div class="badges-card">
                        <h3>Başarılarım</h3>
                        <div class="badges-grid">
                            <div class="badge">
                                <div class="badge-icon">🏆</div>
                                <div class="badge-name">İlk 50</div>
                            </div>
                            <div class="badge">
                                <div class="badge-icon">⚡</div>
                                <div class="badge-name">7 Gün Seri</div>
                            </div>
                            <div class="badge">
                                <div class="badge-icon">🎯</div>
                                <div class="badge-name">100 Kelime</div>
                            </div>
                            <div class="badge locked">
                                <div class="badge-icon">💎</div>
                                <div class="badge-name">Usta</div>
                            </div>
                            <div class="badge locked">
                                <div class="badge-icon">🌟</div>
                                <div class="badge-name">30 Gün</div>
                            </div>
                            <div class="badge locked">
                                <div class="badge-icon">📖</div>
                                <div class="badge-name">500 Kelime</div>
                            </div>
                            <div class="badge locked">
                                <div class="badge-icon">🚀</div>
                                <div class="badge-name">Rocket</div>
                            </div>
                            <div class="badge locked">
                                <div class="badge-icon">🏅</div>
                                <div class="badge-name">Champion</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="today-review" id="review">
                    <h3>Bugün Tekrar Edilecek</h3>
                    <div class="today-count"><?php echo max(1, $remaining); ?></div>
                    <p class="today-subtitle">cümle ve kelime seni bekliyor</p>
                    <button class="btn btn-primary">Tekrara Başla →</button>
                </div>

                <div class="recommendation-box">
                    <h4>💡 Sana Özel Öneri</h4>
                    <p class="recommendation-text" id="recommendationText">
                        <?php echo escapeOutput($recommendation); ?>
                    </p>
                </div>

                <div class="activity-section">
                    <h3>Son 7 Günlük Aktiviten</h3>
                    <div class="activity-chart">
                        <div class="activity-bar-wrapper">
                            <div class="activity-count">8</div>
                            <div class="activity-bar" style="height: 55%;"></div>
                            <div class="activity-day">Pzt</div>
                        </div>
                        <div class="activity-bar-wrapper">
                            <div class="activity-count">12</div>
                            <div class="activity-bar" style="height: 80%;"></div>
                            <div class="activity-day">Sal</div>
                        </div>
                        <div class="activity-bar-wrapper">
                            <div class="activity-count">15</div>
                            <div class="activity-bar" style="height: 100%;"></div>
                            <div class="activity-day">Çar</div>
                        </div>
                        <div class="activity-bar-wrapper">
                            <div class="activity-count">10</div>
                            <div class="activity-bar" style="height: 67%;"></div>
                            <div class="activity-day">Per</div>
                        </div>
                        <div class="activity-bar-wrapper">
                            <div class="activity-count">14</div>
                            <div class="activity-bar" style="height: 93%;"></div>
                            <div class="activity-day">Cum</div>
                        </div>
                        <div class="activity-bar-wrapper">
                            <div class="activity-count">9</div>
                            <div class="activity-bar" style="height: 60%;"></div>
                            <div class="activity-day">Cmt</div>
                        </div>
                        <div class="activity-bar-wrapper">
                            <div class="activity-count">11</div>
                            <div class="activity-bar" style="height: 73%;"></div>
                            <div class="activity-day">Paz</div>
                        </div>
                    </div>
                </div>

                <div class="progress-section" id="progress">
                    <div class="progress-header">
                        <h3>İlerleme Durumun</h3>
                        <p class="progress-subtitle">Toplam içerikte ne kadar yol kat ettin</p>
                    </div>
                    
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?php echo $progressPercent; ?>%;">
                            <span class="progress-label"><?php echo $progressPercent; ?>%</span>
                        </div>
                    </div>

                    <div class="progress-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $knownCount; ?></div>
                            <div class="stat-label">Bildiğin</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $totalCount; ?></div>
                            <div class="stat-label">Toplam</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $remaining; ?></div>
                            <div class="stat-label">Kalan</div>
                        </div>
                    </div>
                </div>

                <div class="calculator-section">
                    <div class="calc-header">
                        <h3>Hesap Makinesi</h3>
                        <p class="calc-subtitle">Ne kadar sürede tamamlarsın? Hemen hesapla!</p>
                    </div>

                    <div class="calc-type-toggle">
                        <button class="calc-type-btn active" data-type="words">Kelime</button>
                        <button class="calc-type-btn" data-type="sentences">Cümle</button>
                        <button class="calc-type-btn" data-type="both">İkisi Birden</button>
                    </div>

                    <div class="calc-inputs">
                        <div class="input-group">
                            <label class="input-label">Günde kaç tane öğrenmek istiyorsun?</label>
                            <input type="number" class="input-field" id="perDay" value="10" min="1" max="100">
                        </div>
                        <div class="input-group">
                            <label class="input-label">Toplam öğrenilecek sayı</label>
                            <input type="number" class="input-field" id="total" value="<?php echo $remaining; ?>" min="1">
                        </div>
                    </div>

                    <div class="calc-result">
                        <div class="result-main" id="resultDays">42</div>
                        <div class="result-label">gün sonra tamamlarsın!</div>
                        <div class="result-detail" id="resultDetail">
                            Her gün 10 kelime öğrenirsen, <strong>6 hafta</strong> içinde tüm kelimeleri bitirebilirsin. 
                            <br>Bitiş tarihi: <strong id="endDate">20 Ocak 2026</strong>
                        </div>
                    </div>
                </div>

                <div class="reminder-section" id="reminders">
                    <h3>⏰ Günlük Hatırlatıcı</h3>
                    <div class="reminder-grid">
                        <div class="reminder-item">
                            <label class="input-label">Hatırlatma saati seç</label>
                            <select class="time-select" id="reminderTime">
                                <option value="09:00">09:00 - Sabah</option>
                                <option value="12:00">12:00 - Öğle</option>
                                <option value="17:00">17:00 - Akşam</option>
                                <option value="19:00" selected>19:00 - Akşam</option>
                                <option value="21:00">21:00 - Gece</option>
                            </select>
                        </div>
                        <div class="reminder-item">
                            <label class="input-label">Bildirim durumu</label>
                            <div class="toggle-container">
                                <span>Günlük hatırlatıcıyı aç</span>
                                <div class="toggle active" id="reminderToggle"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <section id="words" class="words-list">
                    <h3>Kelime Listesi</h3>
                    <div class="word-cards">
                        <?php foreach ($words as $word): ?>
                            <article class="word-card">
                                <div>
                                    <p class="word"><?php echo escapeOutput($word['german']); ?></p>
                                    <p class="meaning"><?php echo escapeOutput($word['turkish']); ?></p>
                                    <p class="sentence"><?php echo escapeOutput($word['sentence']); ?></p>
                                </div>
                                <form method="POST">
                                    <input type="hidden" name="form_type" value="known">
                                    <input type="hidden" name="csrf" value="<?php echo getCsrfToken(); ?>">
                                    <input type="hidden" name="word_id" value="<?php echo (int)$word['id']; ?>">
                                    <?php $isKnown = in_array((int)$word['id'], $knownIds, true); ?>
                                    <button class="btn <?php echo $isKnown ? 'btn-secondary' : 'btn-primary'; ?>" type="submit" <?php echo $isKnown ? 'disabled' : ''; ?>>
                                        <?php echo $isKnown ? 'Biliyorsun' : 'Biliyorum'; ?>
                                    </button>
                                </form>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            </section>
        <?php endif; ?>
    </main>
</div>
<script src="assets/js/app.js"></script>
</body>
</html>
