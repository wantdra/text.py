# Proje Dizin Yapısı

```
text.py/
├── README.md
├── composer.json
├── main.py
├── package.json
├── postcss.config.js
├── tailwind.config.js
├── vite.config.js
├── .env.example
├── storage/
│   └── app/
│       └── imports/
│           └── words.csv
├── routes/
│   ├── api.php
│   └── web.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   ├── lang/
│   │   └── tr/
│   │       └── app.php
│   └── views/
│       ├── components/
│       │   └── layouts/
│       │       └── app.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── layouts/
│       │   └── app.blade.php
│       ├── learn/
│       │   └── index.blade.php
│       └── livewire/
│           ├── games/
│           │   ├── artikel-select.blade.php
│           │   ├── cloze-quiz.blade.php
│           │   ├── drag-drop.blade.php
│           │   └── plural-input.blade.php
│           └── learn-card.blade.php
├── database/
│   ├── factories/
│   │   ├── GameScoreFactory.php
│   │   ├── ReviewLogFactory.php
│   │   ├── UserFactory.php
│   │   ├── UserWordFactory.php
│   │   └── WordFactory.php
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_010000_create_words_table.php
│   │   ├── 2024_01_01_020000_create_user_words_table.php
│   │   ├── 2024_01_01_030000_create_review_logs_table.php
│   │   └── 2024_01_01_040000_create_game_scores_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── WordSeeder.php
├── app/
│   ├── Filament/
│   │   └── Resources/
│   │       ├── UserResource.php
│   │       ├── WordResource.php
│   │       ├── UserResource/
│   │       │   └── Pages/
│   │       │       ├── CreateUser.php
│   │       │       ├── EditUser.php
│   │       │       └── ListUsers.php
│   │       └── WordResource/
│   │           └── Pages/
│   │               ├── CreateWord.php
│   │               ├── EditWord.php
│   │               └── ListWords.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php
│   │   │   ├── GameController.php
│   │   │   ├── LearnController.php
│   │   │   └── StatsController.php
│   │   └── Livewire/
│   │       ├── Games/
│   │       │   ├── ArtikelSelect.php
│   │       │   ├── ClozeQuiz.php
│   │       │   ├── DragDrop.php
│   │       │   └── PluralInput.php
│   │       └── LearnCard.php
│   ├── Models/
│   │   ├── GameScore.php
│   │   ├── ReviewLog.php
│   │   ├── User.php
│   │   ├── UserWord.php
│   │   └── Word.php
│   ├── Policies/
│   │   ├── UserPolicy.php
│   │   └── WordPolicy.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── AuthServiceProvider.php
│   └── Services/
│       └── SrsService.php
└── docs/
    └── FILE_STRUCTURE.md
```

Bu dosya yapısı proje kökünden itibaren önemli klasör ve dosyaları göstermektedir.
