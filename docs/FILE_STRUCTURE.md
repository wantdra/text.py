# Proje Dizin Yapısı

```
text.py/
├── .env.example
├── .gitignore
├── README.md
├── artisan
├── bootstrap/
│   ├── app.php
│   └── cache/
│       └── .gitignore
├── composer.json
├── app/
│   ├── Console/Kernel.php
│   ├── Exceptions/Handler.php
│   ├── Filament/Resources/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Kernel.php
│   │   ├── Livewire/
│   │   └── Middleware/
│   ├── Models/
│   ├── Policies/
│   ├── Providers/
│   └── Services/SrsService.php
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── broadcasting.php
│   ├── cache.php
│   ├── cors.php
│   ├── database.php
│   ├── filament.php
│   ├── filesystems.php
│   ├── hashing.php
│   ├── livewire.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── sanctum.php
│   ├── services.php
│   ├── session.php
│   ├── view.php
│   └── vite.php
├── database/
│   ├── factories/
│   │   ├── GameScoreFactory.php
│   │   ├── ReviewLogFactory.php
│   │   ├── UserFactory.php
│   │   ├── UserWordFactory.php
│   │   └── WordFactory.php
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_000500_create_password_reset_tokens_table.php
│   │   ├── 2024_01_01_000600_create_failed_jobs_table.php
│   │   ├── 2024_01_01_000700_create_personal_access_tokens_table.php
│   │   ├── 2024_01_01_000800_create_sessions_table.php
│   │   ├── 2024_01_01_010000_create_words_table.php
│   │   ├── 2024_01_01_020000_create_user_words_table.php
│   │   ├── 2024_01_01_030000_create_review_logs_table.php
│   │   └── 2024_01_01_040000_create_game_scores_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── WordSeeder.php
├── docs/
│   └── FILE_STRUCTURE.md
├── package.json
├── postcss.config.js
├── phpunit.xml
├── public/
│   ├── .htaccess
│   └── index.php
├── resources/
│   ├── css/app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   ├── lang/tr/
│   │   ├── app.php
│   │   ├── auth.php
│   │   └── passwords.php
│   └── views/
│       ├── auth/
│       │   ├── confirm-password.blade.php
│       │   ├── forgot-password.blade.php
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── reset-password.blade.php
│       │   └── verify-email.blade.php
│       ├── components/
│       │   ├── application-logo.blade.php
│       │   ├── auth-session-status.blade.php
│       │   ├── guest-layout.blade.php
│       │   ├── input-error.blade.php
│       │   ├── input-label.blade.php
│       │   ├── layouts/app.blade.php
│       │   ├── primary-button.blade.php
│       │   ├── secondary-button.blade.php
│       │   └── text-input.blade.php
│       ├── dashboard/index.blade.php
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── guest.blade.php
│       ├── learn/index.blade.php
│       └── livewire/
│           ├── games/
│           │   ├── artikel-select.blade.php
│           │   ├── cloze-quiz.blade.php
│           │   ├── drag-drop.blade.php
│           │   └── plural-input.blade.php
│           └── learn-card.blade.php
├── routes/
│   ├── api.php
│   ├── auth.php
│   ├── channels.php
│   ├── console.php
│   └── web.php
├── storage/
│   ├── app/
│   │   ├── imports/words.csv
│   │   └── public/.gitignore
│   ├── framework/
│   │   ├── cache/.gitignore
│   │   ├── sessions/.gitignore
│   │   └── views/.gitignore
│   └── logs/.gitignore
├── tailwind.config.js
├── tests/
│   ├── CreatesApplication.php
│   ├── Feature/
│   │   └── ExampleTest.php
│   └── TestCase.php
├── vite.config.js
└── server.php
```
