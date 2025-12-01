<?php
// Şifre sıfırlama e-posta talep formu.
include __DIR__ . '/../layouts/header.php';
?>
<?php include __DIR__ . '/../partials/_flash.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow">
            <h1 class="h4 mb-3">Passwort zurücksetzen</h1>
            <?php $errors = $errors ?? []; include __DIR__ . '/../partials/_errors.php'; ?>
            <form method="POST" action="<?php echo base_url('password/forgot'); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo e(\App\Core\Session::csrfToken()); ?>">
                <div class="mb-3">
                    <label class="form-label">E-Mail</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button class="btn btn-primary" type="submit">Reset-Link anzeigen</button>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
