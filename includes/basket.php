<?php



if(isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $attribute_id = $_POST['attribute_id'];

    $basket = &$_SESSION['basket'];

    if(isset($basket[$product_id])) {
        if($basket[$product_id][$attribute_id] < 100)
            $basket[$product_id][$attribute_id]++;
    }
    else
        $basket[$product_id] = [$attribute_id => 1];

    $response = ['count' => Basket::BasketCount()];
    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}
if(isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    $attribute_id = $_POST['attribute_id'];

    $basket = &$_SESSION['basket'];

    unset($basket[$product_id][$attribute_id]);

    $response = ['count' => Basket::BasketCount()];
    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}
if(isset($_POST['update_count'])) {
    $product_id = $_POST['product_id'];
    $attribute_id = $_POST['attribute_id'];
    $count = $_POST['count'];

    $basket = &$_SESSION['basket'];

    $basket[$product_id][$attribute_id] = $count;

    die('Кол-во товара: '.$count);
}