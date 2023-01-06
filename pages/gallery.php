<!DOCTYPE html>
<html lang="ru">
<head>
<?php include 'includes/view/head.php'; ?>

    <link rel="stylesheet" href="/styles/gallery.css">
    <link rel="stylesheet" href="/styles/aboutus.css">
    <link rel="stylesheet" href="/styles/general.css">
    <title>Фотогалерея</title>
</head>
<body>
    <?php include 'includes/view/header.php'; ?>

    <main>
        <?php include 'includes/view/header_menu.php'; ?>

        <a id="gallery"></a>
        
        <section class="gallery_images">
            <div class="gallery_images_wrapper">
                <a data-fancybox="image" href="/images/photogallery/gallery1.png">
                    <img class="rounded" src="/images/photogallery/gallery1.png"/>
                </a>
                <a data-fancybox="image" href="/images/doner.png">
                    <img class="rounded" src="/images/doner.png"/>
                </a>
                <a data-fancybox="image" href="/images/pek1.png">
                    <img class="rounded" src="/images/pek1.png"/>
                </a>
                <a data-fancybox="image" href="/images/photogallery/z3.png">
                    <img class="rounded" src="/images/photogallery/z3.png"/>
                </a>
                <a data-fancybox="image" href="/images/photogallery/nbc.png">
                    <img class="rounded" src="/images/photogallery/nbc.png"/>
                </a>
                <a data-fancybox="image" href="/images/photogallery/z4.png">
                    <img class="rounded" src="/images/photogallery/z4.png"/>
                </a>
                <a data-fancybox="image" href="/images/photogallery/Chiken_Burger.png">
                    <img class="rounded" src="/images/photogallery/Chiken_Burger.png"/>
                </a>
                <a data-fancybox="image" href="/images/photogallery/long-island-iced-tea.png">
                    <img class="rounded" src="/images/photogallery/long-island-iced-tea.png"/>
                </a>
                <a data-fancybox="image" href="/images/photogallery/der.png">
                    <img class="rounded" src="/images/photogallery/der.png"/>
                </a>
                <a data-fancybox="image" href="/images/photogallery/fri.png">
                    <img class="rounded" src="/images/photogallery/fri.png"/>
                </a>
                <a data-fancybox="image" href="/images/photogallery/naggets.png">
                    <img class="rounded" src="/images/photogallery/naggets.png"/>
                </a>
                <a data-fancybox="image" href="/images/photogallery/n1.png">
                    <img class="rounded" src="/images/photogallery/n1.png"/>
                </a>
                <a data-fancybox="image" href="/images/photogallery/n2.png">
                    <img class="rounded" src="/images/photogallery/n2.png"/>
                </a>
                <a></a>
                <a></a>
                <a></a>
            </div>
        </section>
        <section class="section_video">
            <div class="section_video_block">
                <video src="/images/video.mp4" class="section_mp4" controls autoplay preload"></video>
            </div>
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