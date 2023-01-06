<?php
include 'includes/header.php';
include_once '../includes/orders.class.inc.php';
include_once '../includes/products.class.inc.php';
$Orders = new Orders($dbh);
$Products = new Products($dbh);

if(!isset($_GET['order_id'])) {
    echo 'Заказ не найден';
}
else {
    $order_id = intval($_GET['order_id']);
    $order = $Orders->SelectOrder($order_id);
?>
    <h1 class="admin-heading">Заказ №<?= $order->getId(); ?></h1>
    <div class="admin-form-buttons">
        <a href="/admin/orders.php" class="btn">Назад</a>
        <button type="submit" class="btn green" form="order-form">Сохранить</button>
    </div>
    <form action="/admin/handlers/order.php" method="post" id="order-form">
        <div class="info-line"><b>Метод оплаты:</b> <?= Orders::GetPaymentName($order->getPayment()); ?></div>
        <div class="info-line"><b>Имя клиента:</b> <?= $order->getName(); ?></div>
        <div class="info-line"><b>Телефон:</b> <?= $order->getPhone(); ?></div>
        <div class="info-line"><b>Адрес доставки:</b> <?= $order->getAddress(); ?></div>
        <div class="info-line"><b>Сумма:</b> <?= $order->getAmount(); ?>р.</div>
        <div class="info-line"><b>Дата заказа:</b> <?= date('d.m.Y H:i', strtotime($order->getDate())); ?></div>
        <?php if($order->getOddMoney() > 0) { ?>
            <div class="info-line"><b>Сдача с:</b> <?= $order->getOddMoney(); ?>р. (<?= $order->getOddMoney() - $order->getAmount(); ?>р.)</div>
        <?php }?>
        <div class="info-line"><b>Комментарий:</b> <pre><?= $order->getComment(); ?></pre></div>
        <div class="info-line"><b>Статус:</b>
            <select name="status">
                <option value="1"<?= $order->getStatus() == 1?' selected':''; ?>><?= Orders::GetStatusName(1); ?></option>
                <option value="2"<?= $order->getStatus() == 2?' selected':''; ?>><?= Orders::GetStatusName(2); ?></option>
                <option value="3"<?= $order->getStatus() == 3?' selected':''; ?>><?= Orders::GetStatusName(3); ?></option>
            </select>
        </div>

        <input type="hidden" name="save" value="<?= $order->getId(); ?>">
    </form>
    <table class="admin-table" style="max-width: 700px; margin-top: 40px;">
        <tr>
            <th>Товар</th>
            <th>Вариант</th>
            <th>Кол-во</th>
            <th>Цена</th>
            <th>Сумма</th>
        </tr>
        <?php
        $order_products = $Orders->SelectOrderProducts($order_id);
        foreach($order_products as $order_product) {
            $product = $Products->SelectProduct($order_product->product_id);
            $attribute = $product->GetAttributeById($order_product->attribute_id);
        ?>
            <tr>
                <td><a href="/admin/product.php?product_id=<?= $product->getId();?>"><?= $product->getTitle(); ?></a></td>
                <td><?= $attribute->name; ?></td>
                <td><?= $order_product->count; ?></td>
                <td><?= $attribute->price; ?></td>
                <td><?= $attribute->price * $order_product->count; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
<?php
}
include 'includes/footer.php';
