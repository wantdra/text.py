<?php
// Tek seferlik bilgilendirme mesajlarını gösterir.
?>
<?php if (!empty($flash)): ?>
    <div class="alert alert-info"><?php echo e($flash); ?></div>
<?php endif; ?>
<?php if ($error = \App\Core\Session::getFlash('error')): ?>
    <div class="alert alert-danger"><?php echo e($error); ?></div>
<?php endif; ?>
