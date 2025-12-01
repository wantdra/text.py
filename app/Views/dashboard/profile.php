<?php
// Kullanıcı profil düzenleme formu.
include __DIR__ . '/../layouts/header.php';
?>
<?php include __DIR__ . '/../partials/_flash.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4 shadow">
            <h1 class="h4 mb-3">Profil bearbeiten</h1>
            <?php $errors = $errors ?? []; include __DIR__ . '/../partials/_errors.php'; ?>
            <form method="POST" action="<?php echo base_url('dashboard/profile'); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo e(\App\Core\Session::csrfToken()); ?>">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-Mail</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e($user['email']); ?>" required>
                </div>
                <button class="btn btn-primary" type="submit">Speichern</button>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
