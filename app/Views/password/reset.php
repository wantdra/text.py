<?php
// Token ile gelen şifre belirleme formu.
include __DIR__ . '/../layouts/header.php';
?>
<?php include __DIR__ . '/../partials/_flash.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow">
            <h1 class="h4 mb-3">Neues Passwort setzen</h1>
            <?php $errors = $errors ?? []; include __DIR__ . '/../partials/_errors.php'; ?>
            <form method="POST" action="<?php echo base_url('password/reset'); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo e(\App\Core\Session::csrfToken()); ?>">
                <input type="hidden" name="token" value="<?php echo e($token); ?>">
                <div class="mb-3">
                    <label class="form-label">Neues Passwort</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Passwort (Wiederholung)</label>
                    <input type="password" name="password_confirm" class="form-control" required>
                </div>
                <button class="btn btn-primary" type="submit">Passwort speichern</button>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
