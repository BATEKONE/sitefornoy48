<?php
session_start();
include 'config.php';
include 'connect.php';
include_once 'includes/common.php';
include_once 'includes/basket.class.inc.php';

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$urlArray = array_values(array_filter(explode('/', $url)));

$handler = false;
if(!is_doing_ajax()) {
    if(empty($urlArray))
        $handler = 'pages/main.php';
    else if($urlArray[0] == 'basket')
        $handler = 'pages/basket.php';
    else if($urlArray[0] == 'aboutus')
        $handler = 'pages/aboutus.php';
    else if($urlArray[0] == 'gallery')
        $handler = 'pages/gallery.php';
    else if($urlArray[0] == 'feedback')
        $handler = 'pages/feedback.php';
    else if($urlArray[0] == 'main')
        $handler = 'pages/main.php';
}
else {
    if($urlArray[0] == 'ajax') {
        
        if($urlArray[1] == 'products')
            $handler = 'includes/products.php';
        else if($urlArray[1] == 'basket')
            $handler = 'includes/basket.php';
        else if($urlArray[1] == 'order')
            $handler = 'includes/order.php';
    }
}
if($handler)
    include $handler;
else
    header("HTTP/1.1 404 Not Found");