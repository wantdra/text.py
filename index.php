<?php
session_start();

function loadJson(string $path, $default)
{
    if (!file_exists($path)) {
        return $default;
    }

    $contents = file_get_contents($path);
    $data = json_decode($contents, true);

    return is_array($data) ? $data : $default;
}

function saveJson(string $path, $data): void
{
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

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

$wordsFile = __DIR__ . '/data/words.json';
$messagesFile = __DIR__ . '/data/messages.json';
$usersFile = __DIR__ . '/data/users.json';

$words = loadJson($wordsFile, []);
$messages = loadJson($messagesFile, []);
$users = loadJson($usersFile, []);

$errors = [];
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type'])) {
    if (!validateCsrf($_POST['csrf'] ?? '')) {
        $errors[] = 'Güvenlik doğrulaması başarısız oldu.';
    } elseif ($_POST['form_type'] === 'login') {
        $username = sanitize($_POST['username'] ?? '');

        if (strlen($username) < 2) {
            $errors[] = 'Lütfen en az 2 karakterlik bir isim girin.';
        } else {
            $userKey = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $username));
            if (!isset($users[$userKey])) {
                $users[$userKey] = [
                    'name' => $username,
                    'known_words' => [],
                    'streak' => 1,
                    'last_seen' => date('Y-m-d'),
                    'created_at' => date('c'),
                    'reviewed_today' => 0
                ];
            } else {
                $today = date('Y-m-d');
                $yesterday = date('Y-m-d', strtotime('-1 day'));
                $lastSeen = $users[$userKey]['last_seen'] ?? null;

                if ($lastSeen === $yesterday) {
                    $users[$userKey]['streak'] = ($users[$userKey]['streak'] ?? 1) + 1;
                } elseif ($lastSeen !== $today) {
                    $users[$userKey]['streak'] = 1;
                    $users[$userKey]['reviewed_today'] = 0;
                }
                $users[$userKey]['last_seen'] = $today;
            }

            $_SESSION['user_key'] = $userKey;
            saveJson($usersFile, $users);
            $flash = 'Hoş geldin ' . $username . '!';
        }
    } elseif ($_POST['form_type'] === 'known' && isset($_SESSION['user_key'])) {
        $wordId = sanitize($_POST['word_id'] ?? '');
        $userKey = $_SESSION['user_key'];

        if ($wordId && array_filter($words, fn($w) => $w['id'] === $wordId)) {
            $user = $users[$userKey] ?? null;
            if ($user) {
                if (!in_array($wordId, $user['known_words'], true)) {
                    $user['known_words'][] = $wordId;
                    $user['reviewed_today'] = ($user['reviewed_today'] ?? 0) + 1;
                }
                $user['last_seen'] = date('Y-m-d');
                $users[$userKey] = $user;
                saveJson($usersFile, $users);
                $flash = 'Kelime listene eklendi.';
            }
        }
    }
}

$currentUser = null;
if (isset($_SESSION['user_key']) && isset($users[$_SESSION['user_key']])) {
    $currentUser = $users[$_SESSION['user_key']];
}

$knownCount = $currentUser['known_words'] ?? [];
$knownCount = is_array($knownCount) ? count($knownCount) : 0;
$totalCount = count($words);
$remaining = max($totalCount - $knownCount, 0);
$progressPercent = $totalCount > 0 ? round(($knownCount / $totalCount) * 100) : 0;

$recommendation = 'Her gün 15 kelimeyle devam et, 1 ayda büyük yol kat edersin.';
if ($knownCount > 0) {
    $dailyTarget = max(5, min(25, intval(($remaining / 30) + 1)));
    $daysLeft = $dailyTarget > 0 ? ceil($remaining / $dailyTarget) : 0;
    $targetDate = $daysLeft > 0 ? date('d M Y', strtotime("+{$daysLeft} days")) : 'bugün';
    $recommendation = "Günde {$dailyTarget} kelime ile ilerlersen yaklaşık {$daysLeft} günde bitirirsin. Tahmini bitiş: {$targetDate}";
}

$randomMessage = $messages[array_rand($messages)] ?? 'Motivasyon için yeni mesaj ekleyin.';

function escapeOutput(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
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
                            <div>
                                <div class="streak-label">Günlük Seri</div>
                                <div class="streak-number" id="streakNumber"><?php echo intval($currentUser['streak'] ?? 1); ?></div>
                            </div>
                        </div>
                        <div class="streak-label"><?php echo escapeOutput('Bugün ' . ($currentUser['reviewed_today'] ?? 0) . ' kelime çalıştın.'); ?></div>
                    </div>
                    <div class="badges-card">
                        <h3>Başarıların</h3>
                        <div class="badges-grid" id="badgeGrid">
                            <div class="badge <?php echo $knownCount >= 10 ? '' : 'locked'; ?>">
                                <div class="badge-icon">🏅</div>
                                <div class="badge-name">İlk 10</div>
                            </div>
                            <div class="badge <?php echo $knownCount >= 25 ? '' : 'locked'; ?>">
                                <div class="badge-icon">⚡</div>
                                <div class="badge-name">25 Kelime</div>
                            </div>
                            <div class="badge <?php echo $knownCount >= 50 ? '' : 'locked'; ?>">
                                <div class="badge-icon">🎯</div>
                                <div class="badge-name">50 Kelime</div>
                            </div>
                            <div class="badge locked">
                                <div class="badge-icon">🔒</div>
                                <div class="badge-name">Rozetler yakında</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="today-review" id="review">
                    <h3>Bugün Tekrar Edilecek</h3>
                    <div class="today-count"><?php echo max(3, $remaining); ?></div>
                    <p class="today-subtitle">cümle ve kelime seni bekliyor</p>
                    <button class="btn btn-primary" id="startReview">Tekrara Başla →</button>
                </div>

                <div class="recommendation-box">
                    <h4>💡 Sana Özel Öneri</h4>
                    <p class="recommendation-text" id="recommendationText"><?php echo escapeOutput($recommendation); ?></p>
                </div>

                <div class="activity-section" id="words">
                    <h3>Kelime Listesi</h3>
                    <p class="muted">Yeni eklenen tüm kelimeler burada. Bildiklerine tıkla, ilerlemen artsın.</p>
                    <div class="word-grid">
                        <?php foreach ($words as $word): ?>
                            <div class="word-card <?php echo in_array($word['id'], $currentUser['known_words'] ?? [], true) ? 'known' : ''; ?>">
                                <div>
                                    <div class="word-title"><?php echo escapeOutput($word['german']); ?></div>
                                    <div class="word-subtitle"><?php echo escapeOutput($word['turkish']); ?></div>
                                    <div class="word-sentence"><?php echo escapeOutput($word['sentence']); ?></div>
                                </div>
                                <form method="POST">
                                    <input type="hidden" name="form_type" value="known" />
                                    <input type="hidden" name="csrf" value="<?php echo getCsrfToken(); ?>" />
                                    <input type="hidden" name="word_id" value="<?php echo escapeOutput($word['id']); ?>" />
                                    <button class="btn btn-secondary" type="submit" <?php echo in_array($word['id'], $currentUser['known_words'] ?? [], true) ? 'disabled' : ''; ?>>Biliyorum</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
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
                            <div class="stat-label">Bildiklerin</div>
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

                <div class="motivation-section">
                    <div class="motivation-card">
                        <h3>Motivasyon Köşesi</h3>
                        <p id="motivationText"><?php echo escapeOutput($randomMessage); ?></p>
                    </div>
                    <div class="sentence-rotation">
                        <div class="rotator-title">Örnek Cümleler</div>
                        <ul id="sentenceList">
                            <?php foreach ($words as $word): ?>
                                <li><?php echo escapeOutput($word['sentence']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
</div>
<script>window.appWords = <?php echo json_encode($words, JSON_UNESCAPED_UNICODE); ?>;</script>
<script src="assets/js/app.js"></script>
</body>
</html>
