<?php
include_once 'includes/header.php';

$count_orders = $dbh->query("SELECT COUNT(`id`) FROM `Orders`")->fetchColumn();
$count_clients = $dbh->query("SELECT COUNT(DISTINCT `phone`) FROM Orders")->fetchColumn();
$last_order = date('d.m.Y H:i', strtotime($dbh->query("SELECT `date` FROM Orders ORDER BY `date` DESC LIMIT 1")->fetchColumn()));
?>
<!--    <img src="images/dashboard.jpg" style="width: 100%; height: 300px;" alt="">-->
<h1 class="admin-heading">Администрирование</h1>
<div class="dashboard">
    <div class="dashboard-row">
        <div class="dashboard-elem">
            <div class="dashboard-elem-title">Общее количество заказов</div>
            <div class="dashboard-elem-number"><?= $count_orders; ?></div>
        </div>
        <div class="dashboard-elem">
            <div class="dashboard-elem-title">Количество клиентов</div>
            <div class="dashboard-elem-number"><?= $count_clients; ?></div>
        </div>
        <div class="dashboard-elem">
            <div class="dashboard-elem-title">Последний заказ</div>
            <div class="dashboard-elem-date"><?= $last_order; ?></div>
        </div>
    </div>
</div>
<?php
include_once 'includes/footer.php';
