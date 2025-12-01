<?php
// Admin kullanıcı listeleme ekranı.
include __DIR__ . '/../../layouts/admin_header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h5">Benutzerverwaltung</h1>
    <a class="btn btn-primary btn-sm" href="<?php echo base_url('admin/users/create'); ?>">Neu</a>
</div>
<?php include __DIR__ . '/../../partials/_flash.php'; ?>
<div class="table-responsive">
    <table class="table table-dark table-sm align-middle">
        <thead><tr><th>ID</th><th>Name</th><th>E-Mail</th><th>Rolle</th><th>Aktiv</th><th>Aktion</th></tr></thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo e($user['id']); ?></td>
                    <td><?php echo e($user['name']); ?></td>
                    <td><?php echo e($user['email']); ?></td>
                    <td><?php echo e($user['role']); ?></td>
                    <td><?php echo $user['is_active'] ? 'Ja' : 'Nein'; ?></td>
                    <td><a class="btn btn-sm btn-outline-light" href="<?php echo base_url('admin/users/edit?id=' . $user['id']); ?>">Bearbeiten</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
