<?php
include_once '../../config.php';
include_once BASEDIR.'/connect.php';
include_once BASEDIR.'/includes/orders.class.inc.php';

$Orders = new Orders($dbh);

if(isset($_POST['get_notices'])) {
    $orders = $Orders->SelectUnnoticedOrders();
    $response = [
        'orders' => $orders,
        'size' => sizeof($orders),
        'orders_html' => [],
    ];
    $orders_list = [];
    foreach($orders as $order_obj) {
        $order = new Order();
        $order->SetData($order_obj);
        ob_start();
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
        $response['orders_html'][$order->getId()] = ob_get_clean();
        $orders_list[] = $order->getId();
    }

    $Orders->UpdateOrdersNoticed($orders_list);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
}