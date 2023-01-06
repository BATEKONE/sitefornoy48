<?php
include_once 'auth.php';
include_once BASEDIR.'/connect.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <link rel="manifest" href="/images/site.webmanifest">
    <title>Администрирование</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="/admin/css/admin.css">
    <link rel="stylesheet" href="/admin/css/admin_media.css?v=1.2">
</head>
<body>
<div class="wrapper">
    <div class="menu">
        <ul>
            <li><a href="/admin/"><i class="fa fa-home"></i> <span>Главная</span></a></li>
            <li><a href="/admin/products.php"><i class="fa fa-shopping-cart"></i> <span>Товары</span></a></li>
            <li><a href="/admin/orders.php"><i class="fa fa-shopping-basket"></i> <span>Заказы</span></a></li>
            <li><a href="/admin/settings.php"><i class="fa fa-cog"></i> <span>Настройки</span></a></li>
        </ul>
    </div>
    <div class="content">