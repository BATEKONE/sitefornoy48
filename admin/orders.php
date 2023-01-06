<?php
$page_scripts = [
    '/admin/js/notices.js',
];
include 'includes/header.php';
include_once BASEDIR.'/includes/orders.class.inc.php';

$Orders = new Orders($dbh);
if(isset($_GET['status']) && $_GET['status'] == 0)
    unset($_GET['status']);
?>
<h1 class="admin-heading">Заказы</h1>

<div class="admin-form-buttons">
    <button type="button" class="btn enable-notice">Включить уведомления</button>
</div>


<form class="filter">
    <div class="filter__title">Фильтр по статусу</div>
    <div class="filter__row">
        <select name="status" class="filter__select">
            <option value="0"<?= !isset($_GET['status'])?' selected':''; ?>>Не выбрано</option>
            <option value="1"<?= @$_GET['status'] == 1?' selected':''; ?>><?= Orders::GetStatusName(1); ?></option>
            <option value="2"<?= @$_GET['status'] == 2?' selected':''; ?>><?= Orders::GetStatusName(2); ?></option>
            <option value="3"<?= @$_GET['status'] == 3?' selected':''; ?>><?= Orders::GetStatusName(3); ?></option>
        </select>
        <button type="submit" class="btn">Фильтровать</button>
    </div>
</form>

<div class="table-div-admin">
    <table class="admin-table orders-table">
        <thead>
            <tr>
                <th>Номер</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>Сумма</th>
                <th>Тип оплаты</th>
                <th>Сдача с суммы</th>
                <th>Статус</th>
                <th>Дата заказа</th>
                <th>Комментарий</th>
                <th width="90"></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $orders = isset($_GET['status'])?$Orders->SelectOrdersByStatus($_GET['status']):$Orders->SelectOrders();
                foreach($orders as $order) {
                    /* @var $order Order */
            ?>
                        <tr data-id="<?= $order->getId(); ?>">
                            <td><?= $order->getId(); ?></td>
                            <td><?= $order->getName(); ?></td>
                            <td><?= $order->getPhone(); ?></td>
                            <td><?= $order->getAddress(); ?></td>
                            <td><?= $order->getAmount(); ?>р.</td>
                            <td><?= Orders::GetPaymentName($order->getPayment()); ?></td>
                            <td><?= $order->getOddMoney(); ?>р. (<?= $order->getOddMoney() - $order->getAmount(); ?>р.)</td>
                            <td><div class="order-status order-status--<?= Orders::GetStatusColor($order->getStatus()); ?>"><?= Orders::GetStatusName($order->getStatus()); ?></div></td>
                            <td><?= date('d.m.Y H:i', strtotime($order->getDate())); ?></td>
                            <td><?= $order->getComment(); ?></td>
                            <td><a href="/admin/order.php?order_id=<?= $order->getId(); ?>" class="btn">Изменить</a></td>
                        </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>
<?php
include 'includes/footer.php';
