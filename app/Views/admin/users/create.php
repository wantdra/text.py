<?php
// Admin kullanıcı oluşturma formu.
include __DIR__ . '/../../layouts/admin_header.php';
?>
<?php include __DIR__ . '/../../partials/_flash.php'; ?>
<div class="card p-4 shadow-sm">
    <h1 class="h5 mb-3">Neuen Benutzer anlegen</h1>
    <?php $errors = $errors ?? []; include __DIR__ . '/../../partials/_errors.php'; ?>
    <form method="POST" action="<?php echo base_url('admin/users'); ?>">
        <input type="hidden" name="csrf_token" value="<?php echo e(\App\Core\Session::csrfToken()); ?>">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo e($old['name'] ?? ''); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">E-Mail</label>
                <input type="email" name="email" class="form-control" value="<?php echo e($old['email'] ?? ''); ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Passwort</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Rolle</label>
                <select name="role" class="form-select">
                    <option value="user" <?php echo (($old['role'] ?? '') === 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo (($old['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Speichern</button>
    </form>
</div>
<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
