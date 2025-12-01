<?php
// Kullanıcı dashboard ekranı ve istatistikler.
include __DIR__ . '/../layouts/header.php';
?>
<?php include __DIR__ . '/../partials/_flash.php'; ?>
<div class="row g-3">
    <div class="col-md-8">
        <div class="card p-4 shadow mb-3">
            <h1 class="h4 mb-3">Hallo <?php echo e($user['name']); ?> 👋</h1>
            <p class="mb-1">Heutiges Ziel: 10 Fragen in Kompetenz III wiederholen.</p>
            <p class="text-muted">Fokus auf falsch beantwortete Fragen der letzten 7 Tage.</p>
            <a class="btn btn-primary" href="#">Quiz starten</a>
        </div>
        <div class="card p-4 shadow">
            <h2 class="h5 mb-3">Letzte Lernpfade</h2>
            <ul class="mb-0">
                <li>Kompetenz II · Hygiene & Dokumentation – 8/10 richtig</li>
                <li>Kompetenz IV · Notfallsituationen – 6/10 richtig</li>
            </ul>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 shadow mb-3">
            <h2 class="h6 text-uppercase">Gesamt-Fortschritt</h2>
            <p class="mb-0">Fragen gelöst: <?php echo e($stats['total_questions']); ?></p>
            <p class="mb-0">Heute: <?php echo e($stats['completed_today']); ?></p>
            <p class="mb-0">Genauigkeit: <?php echo e($stats['accuracy']); ?>%</p>
        </div>
        <div class="card p-3 shadow">
            <h2 class="h6 text-uppercase">Schwächste Themen</h2>
            <ul class="mb-0">
                <?php foreach ($stats['weak_topics'] as $topic): ?>
                    <li><?php echo e($topic); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
