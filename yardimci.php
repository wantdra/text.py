<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const VERI_KLASORU = __DIR__ . '/veriler';

function json_yolu(string $dosya): string {
    return VERI_KLASORU . '/' . $dosya;
}

function json_oku(string $dosya): array {
    $yol = json_yolu($dosya);
    if (!file_exists($yol)) {
        return [];
    }
    $icerik = file_get_contents($yol);
    if ($icerik === false || $icerik === '') {
        return [];
    }
    $veri = json_decode($icerik, true);
    if (!is_array($veri)) {
        return [];
    }
    return $veri;
}

function json_yaz(string $dosya, array $veri): bool {
    $yol = json_yolu($dosya);
    $json = json_encode($veri, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return false;
    }
    $fp = fopen($yol, 'c+');
    if (!$fp) {
        return false;
    }
    try {
        if (!flock($fp, LOCK_EX)) {
            fclose($fp);
            return false;
        }
        ftruncate($fp, 0);
        $sonuc = fwrite($fp, $json);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        return $sonuc !== false;
    } catch (Throwable $e) {
        fclose($fp);
        return false;
    }
}

function temiz(?string $deger): string {
    return htmlspecialchars(trim((string) $deger), ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_kontrol(?string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string) $token);
}

function oturum_var_mi(): bool {
    return !empty($_SESSION['kullanici']);
}

function kullanici(): ?array {
    return $_SESSION['kullanici'] ?? null;
}

function kullanici_admin_mi(): bool {
    $kullanici = kullanici();
    return $kullanici && ($kullanici['rol'] ?? '') === 'admin';
}

function uuid(): string {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function benzerlik_yuzde(string $a, string $b): float {
    $a = mb_strtolower(trim($a));
    $b = mb_strtolower(trim($b));
    if ($a === $b) {
        return 100.0;
    }
    $mesafe = levenshtein($a, $b);
    $maks = max(mb_strlen($a), mb_strlen($b));
    if ($maks === 0) {
        return 0.0;
    }
    $puan = (1 - ($mesafe / $maks));
    return max(0.0, min(1.0, $puan)) * 100;
}

function post_degeri(string $anahtar, ?string $varsayilan = null): ?string {
    return isset($_POST[$anahtar]) ? trim((string) $_POST[$anahtar]) : $varsayilan;
}

function oturum_zorunlu(): void {
    if (!oturum_var_mi()) {
        header('Location: giris.php');
        exit;
    }
}

function admin_zorunlu(): void {
    if (!kullanici_admin_mi()) {
        header('Location: anasayfa.php');
        exit;
    }
}
