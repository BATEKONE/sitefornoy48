<?php
include_once 'includes/orders.class.inc.php';
$Orders = new Orders($dbh);

if(isset($_POST['add_order'])) {
    if(is_night()) {
        $response = [
            'success' => false,
            'error' => 'Заказы принимаются с 9:00 до 20:30',
        ];
        die(json_encode($response, JSON_UNESCAPED_UNICODE));
    }
    $name = addslashes(trim($_POST['name']));
    $address = addslashes(trim($_POST['address']));
    $phone = addslashes(trim($_POST['phone']));
    $comment = addslashes(trim($_POST['comment']));
    $odd_money = intval($_POST['odd_money']);
    $pay = $_POST['pay'];

    $basket = Basket::GetBasket();

    $products = [];
    foreach($basket as $product_id => $item) {
        foreach($item as $attribute_id => $count) {
            $products[] = [
                'id' => $product_id,
                'attribute_id' => $attribute_id,
                'count' => $count,
            ];
        }
    }

    $fields = [
        'payment' => $pay,
        'phone' => $phone,
        'name' => $name,
        'address' => $address,
        'amount' => Basket::BasketAmount(),
        'odd_money' => $odd_money,
        'comment' => $comment,
        'date' => date('Y-m-d H:i:s'),
        'status' => 1,
    ];

    $order_id = $Orders->AddOrder($fields, $products);
    $success = $Orders->SendOrder($order_id);
    Basket::Truncate();

    $response = [];
    $response['success'] = $success;
//    $response['debug'] = Config::GetSettings('admin_email');

    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}