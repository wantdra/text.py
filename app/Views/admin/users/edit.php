<?php
// Admin kullanıcı düzenleme formu.
include __DIR__ . '/../../layouts/admin_header.php';
?>
<?php include __DIR__ . '/../../partials/_flash.php'; ?>
<div class="card p-4 shadow-sm">
    <h1 class="h5 mb-3">Benutzer bearbeiten</h1>
    <?php $errors = $errors ?? []; include __DIR__ . '/../../partials/_errors.php'; ?>
    <form method="POST" action="<?php echo base_url('admin/users/update'); ?>">
        <input type="hidden" name="csrf_token" value="<?php echo e(\App\Core\Session::csrfToken()); ?>">
        <input type="hidden" name="id" value="<?php echo e($user['id']); ?>">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" value="<?php echo e($user['name']); ?>" disabled>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">E-Mail</label>
                <input type="text" class="form-control" value="<?php echo e($user['email']); ?>" disabled>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Rolle</label>
                <select name="role" class="form-select">
                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Aktiv</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="active" <?php echo $user['is_active'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="active">Ja</label>
                </div>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Aktualisieren</button>
    </form>
</div>
<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
