<?php
// Ana sayfa ve ürün özelliklerinin tanıtımı.
include __DIR__ . '/../layouts/header.php';
?>
<div class="row">
    <div class="col-lg-7 mb-3">
        <div class="card p-4 shadow">
            <h1 class="h3 mb-3">Pflegefachkraft Prüfungsvorbereitung</h1>
            <p class="lead">Strukturiertes Lernen für TELC, Staatsexamen und mündliche Prüfungen.</p>
            <ul>
                <?php foreach ($features as $item): ?>
                    <li><?php echo e($item); ?></li>
                <?php endforeach; ?>
            </ul>
            <a class="btn btn-primary" href="<?php echo base_url('register'); ?>">Jetzt registrieren</a>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card p-4 shadow mb-3">
            <h2 class="h5">Demo Quiz</h2>
            <p class="mb-0">Zwei Beispiel-Fragen zeigen, wie Kompetenz-basierte Auswertungen funktionieren.</p>
        </div>
        <div class="card p-4 shadow">
            <h2 class="h5">Neuigkeiten & Gesetzes-Updates</h2>
            <p class="mb-0">Bleibe informiert über Pflege-Standards, Hygiene-Vorgaben und Dokumentationspflichten.</p>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
