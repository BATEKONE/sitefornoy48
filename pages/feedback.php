<!DOCTYPE html>
<html lang="ru">
<head>
    <?php include 'includes/view/head.php'; ?>

    <link rel="stylesheet" href="/styles/gallery.css">
    <link rel="stylesheet" href="/styles/aboutus.css">
    <link rel="stylesheet" href="/styles/general.css">

    <script src="https://vk.com/js/api/openapi.js?169" type="text/javascript"></script>

    <title>Отзыв</title>
</head>
<body>
<?php include 'includes/view/header.php'; ?>
<?php include 'includes/view/header_menu.php'; ?>

<a id="feedback"></a>

    <script type="text/javascript" src="https://vk.com/js/api/openapi.js?169"></script>

    <script type="text/javascript">
        VK.init({apiId: 51498394, onlyWidgets: true});
    </script>

    <div id="vk_comments"></div>
    <script type="text/javascript">
        VK.Widgets.Comments("vk_comments", {limit: 10, attach: false});
    </script>

    <?php include 'includes/view/footer.php'; ?>
    <?php include 'includes/view/footer_scirpts.php'; ?>
    <script src="/scripts/scroll.js"></script>
</body>
</html>