<?php
// Form hata mesajlarını listeler.
?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $message): ?>
                <li><?php echo e($message); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
