<?php
// Kullanıcı dashboard ekranı ve istatistikler.
include __DIR__ . '/../layouts/header.php';
?>
<?php include __DIR__ . '/../partials/_flash.php'; ?>

<style>
    /* Dashboard özel düzen stili: sidebar + ana içerik */
    .dashboard-shell {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 24px;
        min-height: 80vh;
    }

    @media (max-width: 992px) {
        .dashboard-shell {
            grid-template-columns: 1fr;
        }
        .dash-sidebar {
            position: relative;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
        .dash-sidebar nav ul {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .dash-sidebar .version {
            margin-top: 12px;
        }
    }

    /* Sidebar tasarımı */
    .dash-sidebar {
        background: rgba(255,255,255,0.9);
        border: 1px solid var(--border-soft);
        border-radius: 18px;
        box-shadow: var(--shadow-soft);
        padding: 22px 18px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .dash-sidebar .logo-area {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dash-sidebar .logo-badge {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(160deg, var(--accent-blue), var(--accent-cyan));
        color: #fff;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 10px 24px rgba(37,99,235,0.28);
    }

    .dash-sidebar nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dash-sidebar nav a {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: var(--text-main);
        padding: 12px 14px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.15s ease;
    }

    .dash-sidebar nav a.active,
    .dash-sidebar nav a:hover {
        background: linear-gradient(120deg, rgba(6,182,212,0.18), rgba(37,99,235,0.12));
        box-shadow: 0 10px 24px rgba(6,182,212,0.22);
        color: var(--accent-blue);
        transform: translateY(-1px);
    }

    .dash-sidebar nav .indicator {
        width: 6px;
        height: 28px;
        border-radius: 12px;
        background: linear-gradient(120deg, var(--accent-cyan), var(--accent-blue));
    }

    .dash-sidebar .version {
        font-size: 13px;
        color: #475569;
        background: #f1f5f9;
        border-radius: 12px;
        padding: 10px 12px;
    }

    /* Top bar ve içerik başlıkları */
    .dash-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .dash-topbar .title {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .dash-topbar .title h1 {
        font-size: 26px;
        font-weight: 700;
        margin: 0;
    }

    .dash-topbar .title span {
        color: #475569;
    }

    .user-chip {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #ffffff;
        padding: 10px 14px;
        border-radius: 14px;
        border: 1px solid var(--border-soft);
        box-shadow: var(--shadow-soft);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(140deg, var(--accent-blue), var(--accent-cyan));
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    /* Kartlar ve grid yapı */
    .hero-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(120deg, rgba(6,182,212,0.12), rgba(37,99,235,0.08));
    }

    .hero-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.4), transparent 45%),
                    radial-gradient(circle at 80% 0%, rgba(34,197,94,0.35), transparent 35%);
        pointer-events: none;
    }

    .hero-card .btn-primary {
        padding: 12px 20px;
        border-radius: 999px;
        font-size: 16px;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
    }

    .stat-card h6 {
        font-weight: 700;
        letter-spacing: 1px;
        font-size: 12px;
        color: #475569;
    }

    .stat-card .value {
        font-size: 24px;
        font-weight: 700;
    }

    .bottom-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 18px;
    }

    .list-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border-radius: 12px;
        transition: all 0.15s ease;
    }

    .list-row:hover {
        background: #f8fafc;
        transform: translateY(-1px);
    }

    .badge-compact {
        border-radius: 999px;
        padding: 6px 10px;
        font-weight: 700;
        background: #e0f2fe;
        color: var(--accent-blue);
    }

    .muted {
        color: #475569;
    }
</style>

<div class="dashboard-shell">
    <!-- Sol sabit sidebar -->
    <aside class="dash-sidebar">
        <div class="logo-area">
            <div class="logo-badge">Pf</div>
            <div>
                <div class="fw-bold">Pflegefachkraft App</div>
                <div class="text-muted" style="font-size: 13px;">Lern- &amp; Prüfungsbegleiter</div>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="<?php echo base_url('/'); ?>" class=""><span class="indicator"></span>Start</a></li>
                <li><a href="<?php echo base_url('dashboard'); ?>" class="active"><span class="indicator"></span>Dashboard</a></li>
                <li><a href="#"><span class="indicator"></span>Quiz</a></li>
                <li><a href="#"><span class="indicator"></span>Lernpfade</a></li>
                <li><a href="#"><span class="indicator"></span>Statistiken</a></li>
                <li><a href="<?php echo base_url('dashboard/profile'); ?>"><span class="indicator"></span>Profil</a></li>
                <li><a href="<?php echo base_url('logout'); ?>"><span class="indicator"></span>Logout</a></li>
            </ul>
        </nav>
        <div class="version">
            Heute: 10 Fragen übrig · v0.1 Beta
        </div>
    </aside>

    <!-- Sağ ana içerik alanı -->
    <main class="dash-main">
        <div class="dash-topbar">
            <div class="title">
                <h1>Dashboard</h1>
                <span>Dein Überblick über Ziele und Lernfortschritt.</span>
            </div>
            <div class="user-chip">
                <div class="user-avatar"><?php echo strtoupper(substr(e($user['name']), 0, 1)); ?></div>
                <div>
                    <div class="fw-bold"><?php echo e($user['name']); ?></div>
                    <small class="text-muted">Student · Kurs Pflegefachkraft</small>
                </div>
                <span class="text-muted">🔔</span>
            </div>
        </div>

        <!-- Hoş geldin kartı -->
        <section class="card hero-card p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="h4 fw-bold mb-2">Hallo <?php echo e($user['name']); ?> 👋</h2>
                    <p class="fs-5 fw-semibold mb-1">Heutiges Ziel: 10 Fragen in Kompetenz III wiederholen.</p>
                    <p class="text-muted mb-3">Fokus auf falsch beantwortete Fragen der letzten 7 Tage.</p>
                    <a class="btn btn-primary" href="#">Quiz starten</a>
                    <div class="mt-3 text-muted">Empfohlen: 1 Lernpfad á 10 Fragen · ca. 15 Minuten.</div>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div class="badge-compact">Motivation</div>
                    <div class="display-6 fw-bold mt-2">78%</div>
                    <div class="muted">Durchschnittliche Genauigkeit</div>
                </div>
            </div>
        </section>

        <!-- İstatistik kartları -->
        <section class="stat-grid mb-4">
            <div class="card p-3 stat-card">
                <h6>GESAMT-FORTSCHRITT</h6>
                <div class="value mb-2">Fragen gelöst: <?php echo e($stats['total_questions']); ?></div>
                <div class="muted">Heute: <?php echo e($stats['completed_today']); ?></div>
                <div class="progress mt-3" style="height: 8px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 78%;"></div>
                </div>
            </div>
            <div class="card p-3 stat-card">
                <h6>GENAUIGKEIT</h6>
                <div class="value mb-2"><?php echo e($stats['accuracy']); ?>%</div>
                <div class="muted">Letzte 30 Tage</div>
                <div class="progress mt-3" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($stats['accuracy']); ?>%;"></div>
                </div>
            </div>
            <div class="card p-3 stat-card">
                <h6>LERNSTREAK</h6>
                <div class="value mb-1">Aktueller Streak: 5 Tage</div>
                <div class="muted">Längster Streak: 9 Tage 🔥</div>
            </div>
        </section>

        <!-- Alt bölge: son öğrenme yolları ve zayıf konular -->
        <section class="bottom-grid">
            <div class="card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="mb-0">Letzte Lernpfade</h5>
                    <a href="#" class="text-decoration-none">Alle ansehen</a>
                </div>
                <div class="list-row">
                    <div>
                        <span class="badge-compact me-2">K II</span>
                        Kompetenz II · Hygiene &amp; Dokumentation
                    </div>
                    <span class="muted">8/10 richtig</span>
                </div>
                <div class="list-row">
                    <div>
                        <span class="badge-compact me-2">K IV</span>
                        Kompetenz IV · Notfallsituationen
                    </div>
                    <span class="muted">6/10 richtig</span>
                </div>
                <div class="list-row">
                    <div>
                        <span class="badge-compact me-2">K I</span>
                        Kompetenz I · Pflegeprozess Basics
                    </div>
                    <span class="muted">9/12 richtig</span>
                </div>
            </div>
            <div class="card p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="mb-0">Schwächste Themen</h5>
                    <a href="#" class="text-decoration-none">Gezielt Training starten</a>
                </div>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($stats['weak_topics'] as $topic): ?>
                        <li class="list-row">
                            <span><?php echo e($topic); ?></span>
                            <span class="badge-compact">Erfolgsquote: 42%</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </main>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
