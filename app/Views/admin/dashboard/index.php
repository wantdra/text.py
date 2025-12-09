<?php
// Admin paneli dashboard görünümü.
include __DIR__ . '/../../layouts/admin_header.php';
?>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <p class="text-muted mb-1">Nutzer gesamt</p>
            <h3><?php echo e($stats['total_users']); ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <p class="text-muted mb-1">Aktive Nutzer</p>
            <h3><?php echo e($stats['active_users']); ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <p class="text-muted mb-1">Fragen gesamt</p>
            <h3><?php echo e($stats['total_questions']); ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <p class="text-muted mb-1">Heute gelöst</p>
            <h3><?php echo e($stats['solved_today']); ?></h3>
        </div>
    </div>
</div>
<div class="card shadow-sm p-3">
    <h2 class="h6">Letzte Registrierungen</h2>
    <div class="table-responsive">
        <table class="table table-dark table-sm">
            <thead><tr><th>ID</th><th>Name</th><th>E-Mail</th><th>Rolle</th><th>Registriert</th></tr></thead>
            <tbody>
                <?php foreach ($recent as $user): ?>
                    <tr>
                        <td><?php echo e($user['id']); ?></td>
                        <td><?php echo e($user['name']); ?></td>
                        <td><?php echo e($user['email']); ?></td>
                        <td><?php echo e($user['role']); ?></td>
                        <td><?php echo e($user['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
