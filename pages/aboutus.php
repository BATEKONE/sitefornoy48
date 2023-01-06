<!DOCTYPE html>
<html lang="ru">
<head>
<?php include 'includes/view/head.php'; ?>

    <link rel="stylesheet" href="/styles/aboutus.css">
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="/styles/general.css">
    <title>О нас</title>
</head>
<body>
    <?php include 'includes/view/header.php'; ?>

    <main>
        <?php include 'includes/view/header_menu.php'; ?>
        <a id="aboutus"></a>

        <section class="delivery_big_heading">
            <h1 class="delivery_big_heading1">Доставка бесплатная <br> от 700 руб</h1>
            <div class="delivery_big_heading_call_and_number">
                <img class="delivery_big_heading_call" src="images/call.svg" alt="">
                <a href="tel:+7(4742)71-28-88" class="delivery_big_heading_link">+7(4742)-<span>71-28-88</span></a>
            </div>
            <div class="delivery_big_heading_two_head">
                <h2 class="delivery_big_heading_chart1">График доставки:</h2>
                <h3 class="delivery_big_heading_chart2">пн-вс 09:00-21:00</h3>
            </div>
        </section>

        <section class="section_video">
            <img class="section_video_img1" src="/images/pek1.png" alt="">
            <div class="section_video_block">
                <video src="/images/video.mp4" class="section_mp4" controls autoplay preload"></video>
            </div>
            <img class="section_video_img1" src="/images/doner.png" alt="">
        </section>
    </main>

    <?php include 'includes/view/footer.php'; ?>
    <?php include 'includes/view/footer_scirpts.php'; ?>
    <script src="/scripts/volume.js"></script>
    <script src="/scripts/scroll.js"></script>
    <script type="text/javascript" src="/scripts/slick/slick.js"></script>
    <script src="/scripts/slick.js"></script>
</body>
</html>