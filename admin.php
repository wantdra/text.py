<?php
session_start();
require_once __DIR__ . '/db.php';

const ADMIN_PASSWORD = 'admin123';

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

$pdo = db();
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrf($_POST['csrf'] ?? '')) {
        $errors[] = 'Güvenlik doğrulaması başarısız oldu.';
    } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
        $password = $_POST['password'] ?? '';
        if ($password === ADMIN_PASSWORD) {
            $_SESSION['admin'] = true;
            $success = 'Giriş başarılı, hoş geldin!';
        } else {
            $errors[] = 'Parola hatalı.';
        }
    } elseif (!isset($_SESSION['admin'])) {
        $errors[] = 'Önce giriş yapmalısın.';
    } elseif ($_POST['action'] === 'add_word') {
        $german = sanitize($_POST['german'] ?? '');
        $turkish = sanitize($_POST['turkish'] ?? '');
        $sentence = sanitize($_POST['sentence'] ?? '');

        if ($german && $turkish) {
            $stmt = $pdo->prepare('INSERT INTO words (german, turkish, sentence) VALUES (:german, :turkish, :sentence)');
            $stmt->execute([
                'german' => $german,
                'turkish' => $turkish,
                'sentence' => $sentence ?: '—',
            ]);
            $success = 'Kelime eklendi ve herkes için görünür.';
        } else {
            $errors[] = 'Almanca ve Türkçe alanları zorunlu.';
        }
    } elseif ($_POST['action'] === 'add_message') {
        $message = sanitize($_POST['message'] ?? '');
        if ($message) {
            $stmt = $pdo->prepare('INSERT INTO messages (body) VALUES (:body)');
            $stmt->execute(['body' => $message]);
            $success = 'Motivasyon mesajı eklendi.';
        } else {
            $errors[] = 'Mesaj alanı boş olamaz.';
        }
    }
}

$messagesStmt = $pdo->query('SELECT id, body FROM messages ORDER BY id DESC');
$messages = $messagesStmt->fetchAll();

$isLogged = isset($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-shell">
        <header>
            <div>
                <div class="eyebrow">Kontrol Merkezi</div>
                <h1>Almanca Kalıp Admin</h1>
            </div>
            <a class="link" href="index.php">Uygulamaya Dön</a>
        </header>

        <?php if ($success): ?>
            <div class="alert success"><?php echo escapeOutput($success); ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert error"><?php echo escapeOutput(implode(' ', $errors)); ?></div>
        <?php endif; ?>

        <?php if (!$isLogged): ?>
            <section class="panel">
                <h2>Admin Girişi</h2>
                <p>Varsayılan şifre: <strong><?php echo ADMIN_PASSWORD; ?></strong> (XAMPP üzerinde test için).</p>
                <form method="POST" class="form-grid">
                    <input type="hidden" name="csrf" value="<?php echo getCsrfToken(); ?>">
                    <input type="hidden" name="action" value="login">
                    <label>Parola
                        <input type="password" name="password" required autocomplete="current-password">
                    </label>
                    <button class="btn" type="submit">Giriş Yap</button>
                </form>
            </section>
        <?php else: ?>
            <section class="panel">
                <h2>Kelime Ekle</h2>
                <p>Admin tarafından eklenen kelimeler anında tüm kullanıcılara görünür.</p>
                <form method="POST" class="form-grid">
                    <input type="hidden" name="csrf" value="<?php echo getCsrfToken(); ?>">
                    <input type="hidden" name="action" value="add_word">
                    <label>Almanca
                        <input type="text" name="german" required>
                    </label>
                    <label>Türkçe
                        <input type="text" name="turkish" required>
                    </label>
                    <label>Örnek Cümle
                        <input type="text" name="sentence" placeholder="Guten Morgen, ...">
                    </label>
                    <button class="btn" type="submit">Kaydet</button>
                </form>
            </section>

            <section class="panel">
                <h2>Motivasyon Yazıları</h2>
                <p>Müşterek motivasyon kartında ve döngüde kullanılır.</p>
                <form method="POST" class="form-grid">
                    <input type="hidden" name="csrf" value="<?php echo getCsrfToken(); ?>">
                    <input type="hidden" name="action" value="add_message">
                    <label>Yeni mesaj
                        <textarea name="message" rows="2" required></textarea>
                    </label>
                    <button class="btn" type="submit">Mesajı Ekle</button>
                </form>

                <div class="list">
                    <?php foreach ($messages as $message): ?>
                        <div class="list-item">
                            <span>#<?php echo (int)$message['id']; ?></span>
                            <p><?php echo escapeOutput($message['body']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="panel">
                <h2>Güvenlik İpuçları</h2>
                <ul class="tips">
                    <li>Paneli XAMPP üzerinde denedikten sonra parolayı değiştirin.</li>
                    <li>database.sql dosyasını içeri aktararak tam tablo adlarıyla MySQL kurulumunu tamamlayın.</li>
                    <li>CSRF koruması ve giriş olmadan işlem yapılmaz.</li>
                </ul>
            </section>
        <?php endif; ?>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>
