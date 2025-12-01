<?php
// Uygulama genel yapılandırma ayarlarını tutar.
return [
    'app_name' => 'Pflegefachkraft App',
    'base_url' => 'http://localhost/pflegefachkraft_app/public',
    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'pflegefachkraft',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'security' => [
        'csrf_key' => 'pflegefachkraft_csrf',
        'session_name' => 'pflegefachkraft_session',
        'login_attempt_limit' => 5,
        'login_lock_minutes' => 10,
    ],
];
