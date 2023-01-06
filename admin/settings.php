<?php
include_once 'includes/header.php';
?>
    <h1 class="admin-heading">Настройки сайта</h1>
    <div class="admin-form-buttons">
        <button class="btn green" type="submit" form="settings-form">Сохранить</button>
    </div>
    <form action="/admin/handlers/settings.php" id="settings-form" method="post" class="admin-form">
        <div class="admin-form-row">
            <div class="admin-form-label">Почта, на которую приходят заказы</div>
            <input type="email" name="admin_email" placeholder="Почта" value="<?= htmlspecialchars(Config::GetSettings('admin_email')); ?>" required>
        </div>
        <input type="hidden" name="save">
    </form>
<?php
include_once 'includes/footer.php';

