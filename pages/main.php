<?php
include_once 'includes/products.php';
include_once 'includes/basket.php';
include_once 'includes/products.class.inc.php';

$Categories = new Categories($dbh);

if(isset($_GET['cat']) && $_GET['cat'] != 0)
    $cat = get_cat(intval($_GET['cat']), 1);
else
    $cat = get_cat(0, 1);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="stylesheet" type="text/css" href="/scripts/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="/scripts/slick/slick-theme.css"/>

    <?php include 'includes/view/head.php'; ?>

    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/onlymainmedia.css" media="screen and (max-width: 905px)">

    <title>НОЙ - ковчег вкусного питания</title>
</head>
<body>
<?php include 'includes/view/header.php'; ?>

<main>
    <?php include 'includes/view/header_menu.php'; ?>

    <section class="short_menu">
        <h1 class="section_short_menu_heading">Шаурма с курицей<br>всего за 180 руб.</h1>
        <div class="section_short_menu_hand_and_menu">
            <img src="images/hand.png" alt="" class="section_short_menu_hand">
            <a href="#main_menu_other" class="short_menu_link">Меню</a>
        </div>
    </section>

    <section id="main_menu_other">
        <h1 class="main_menu_other_heading">Меню</h1>
        <div class="main_menu_other_list">
            <?php foreach($Categories->SelectCategories(0) as $category) { /* @var $category Category */ ?>
                <a href="?cat=<?= $category->getId(); ?>" data-id="<?= $category->getId(); ?>" class="main_menu_under_list show_cat">
                    <img class="main_menu_other_img" src="<?= Config::GetCategoryImageWebDir().'/'.$category->getImage(); ?>" alt="">
                    <div class="main_other_menu_under_list_button"><?= $category->getTitle(); ?></div>
                </a>
            <?php } ?>
            <div class="main_menu_under_list_empty"></div>
            <div class="main_menu_under_list_empty"></div>
            <div class="main_menu_under_list_empty"></div>
            <div class="main_menu_under_list_empty"></div>
            <div class="main_menu_under_list_empty"></div><?php // Чтобы при переносе не категорию не сносило вправо (спроси почему - объясню) ?>
        </div>
    </section>

    <a id="main"></a>

    <div class="gif_img_block">
        <img src="/images/gifcar.png" class="main-animated-car _anim-items _anim-no-hide" alt="">
    </div>

    <style>
        .not_work, .site_not{
            display: flex;
            justify-content: center;
            padding: 50px 0;
            background-color: #1c7430;
            color: white;
        }
    </style>

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

    <section class="big_menu">
        <div class="big_menu_four">
            <div class="big_menu_fuck_block cat-products">
                <div class="cat-gallery-wrap">
                    <?= $cat['cat_gallery']?:''; ?>
                </div>
                <div class="cat-products-categories">
                    <?= (isset($_GET['cat']) && $_GET['cat'] != 0)?$cat['categories']:''; ?>
                </div>
                <div class="big_menu_block_bigger cat-products-inner">
                    <?= $cat['products']; ?>
                </div>
                <button type="button" class="big_menu_button load_more<?= $cat['show_more']?'':' hidden'; ?>" data-cat_id="<?= $_GET['cat'] ?? 0; ?>" data-page="2">Показать ещё</button>
                <div class="cat-product-additions">
                    <?= $cat['addition_products']; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/view/footer.php'; ?>

<div class="added-to-cart">
    Товар добавлен в <a href="/basket" class="addet_to_cart_link">корзину</a>
</div>

<?php include 'includes/view/footer_scirpts.php'; ?>
<!--<script src="/scripts/scroll.js"></script>-->
<script type="text/javascript" src="/scripts/slick/slick.js"></script>
<script src="/scripts/slick.js"></script>
</body>
</html>