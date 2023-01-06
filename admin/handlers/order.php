<?php
include_once '../../config.php';
include_once BASEDIR.'/connect.php';
include_once BASEDIR.'/includes/orders.class.inc.php';

$Orders = new Orders($dbh);

if(isset($_POST['save'])) {
    $order_id = intval($_POST['save']);
    $status = intval($_POST['status']);

    $fields = [
        'status' => $status
    ];

    $Orders->UpdateOrder($order_id, $fields);

    header('Location: '.$_SERVER['HTTP_REFERER']);
}