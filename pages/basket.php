<?php
session_start();
include "connect.php";
include_once 'includes/products.class.inc.php';
include_once 'includes/products.php';

$Products = new Products($dbh);

if(!isset($_SESSION['basket']))
    $_SESSION['basket'] = [];
$basket = $_SESSION['basket'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="/scripts/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="/scripts/slick/slick-theme.css"/>

    <?php include 'includes/view/head.php'; ?>
    <link rel="stylesheet" href="styles/basket.css">

    <title>Корзина</title>
</head>
<body>
<header>
    <hgroup>
        <h1 class="basket_heading1">Корзина</h1>
        <h2 class="basket_heading2"><a href="/" class="backet_main">Главная</a> / Корзина</h2>
    </hgroup>
</header>

<main>
    <section class="section_basket_main_food_and_additionally">
        <div class="section_basket_main_food">
            <div class="block_number_two">
                <div class="section_basket_appellation">
                    <hgroup>
                        <div class="section_basket_appellation_heading delete"></div>
                        <div class="section_basket_appellation_heading product">Товар</div>
                        <div class="section_basket_appellation_heading price">Цена</div>
                        <div class="section_basket_appellation_heading count">Количество</div>
                    </hgroup>
                </div>

                <?php
                $full_price = 0;
                foreach($basket as $product_id => $attributes) {
                    $product = $Products->SelectProduct($product_id);
                    foreach($attributes as $attribute_id => $count) {
                        $attribute = $product->GetAttributeById($attribute_id);
                        $full_price += $attribute->price * $count;
                        ?>
                        <div class="section_basket_one_stroke" data-id="<?= $product->getId(); ?>" data-attribute_id="<?= $attribute_id; ?>">
                            <div class="buttons">
                                <span class="delete-btn remove_product">x</span>
                            </div>

                            <div class="section_basket_product">
                                <div class="section_basket_product-image">
                                    <img src="<?= Config::GetProductImageWebDir().'/'.$product->getImage()->image_min; ?>" alt="">
                                </div>
                                <div class="section_basket_product-text">
                                    <div class="section_basket_product-title"><?= $product->getTitle(); ?></div>
                                    <div class="section_basket_product-attribute"><?= $attribute->name; ?></div>
                                </div>
                            </div>

                            <div class="section_basket_price">
                                <span class="section_basket_price-number"
                                    data-price="<?= $attribute->price; ?>"><?= number_format($attribute->price * $count, 0, '.', ' '); ?></span>р.
                            </div>

                            <div class="section_basket_plus_and_minus">
                                <button class="count_basket_button minus-btn" type="button" name="button">
                                    <img src="images/minus.png" alt="">
                                </button>
                                <input type="number" name="count[<?= $product_id; ?>]" value="<?= $count; ?>" min="1" max="100" class="count_input">
                                <button class="count_basket_button plus-btn" type="button" name="button">
                                    <img src="images/plus.png" alt="">
                                </button>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <div class="section_price_after_main">
                    <span class="section_price_all">Итого: </span><span class="section_price-value"><?= number_format($full_price, 0, '.', ' '); ?></span>р.
                </div>
            </div>
        </div>
        <div class="section_slick">
                <div class="section_slick-slider">
                    <?php
                        $cat = get_cat_cart(6);
                        echo $cat['products'];
                    ?>
                </div>
                <div class="section_slick-slider">
                    <?php
                    $cat = get_cat_cart(5);
                    echo $cat['products'];
                    ?>
                </div>
                <div class="section_slick-slider">
                    <?php
                    $cat = get_cat_cart(12);
                    echo $cat['products'];
                    ?>
                </div>
        </div>
    </section>

    <section class="section_delivery_and_other_func">
        <form action="" method="post" class="order-form">
            <div class="section_delivery">
                <h1 class="section_delivery_heading">Доставка:</h1>
                <input type="text" id="name" name="name" placeholder="Ваше имя" required>
                <input type="text" id="address" name="address" placeholder="Адрес доставки" required>
                <input type="tel" id="phone" name="phone" placeholder="Номер телефона" required>
            </div>

            <div class="section_pay">
                <h1 class="section_pay_heading">Оплата:</h1>
                <div id="section_pay_and_how">
                    <div class="section_pay_pay">
                        <label class="button_label">
                            <input type="radio" name="pay" value="cash" checked> Наличными
                        </label>
                        <label class="button_label">
                            <input type="radio" name="pay" value="card"> Картой
                        </label>
                    </div>
                    <div class="section_pay_how">
                        <select name="odd_money_trigger" id="odd_money_trigger">
                            <option value="0">Без сдачи</option>
                            <option value="1">Со сдачей</option>
                        </select>
                        <input type="number" id="odd_money" class="hidden" name="odd_money" placeholder="Сдача с" min="<?= $full_price; ?>">
                    </div>
                </div>
            </div>

            <div class="section_comm">
                <h1 class="section_comm_comm">Комментарий:</h1>
                <textarea id="section_comment" type="text" name="comment" placeholder="Пожелания к заказу"></textarea>
            </div>
            <div class="section_price">
                <p class="section_price_all">Итого: </p><div><span class="section_price-value"><?= number_format($full_price, 0, '.', ' '); ?></span>р.</div>
            </div>
            <?php if(is_night()) { ?>
                <p style="margin-top: 15px;">Заказы принимаются с 9:00 до 20:30</p>
            <?php } else { ?>
                <button class="section_delivery_button"<?= ($full_price < 700)?' disabled':''; ?>>Оформить заказ</button>
            <?php } ?>

        </form>
        <div class="section_slick2">
            <div class="section_slick-slider">
                <?php
                $cat = get_cat_cart(6);
                echo $cat['products'];
                ?>
            </div>
            <div class="section_slick-slider">
                <?php
                $cat = get_cat_cart(5);
                echo $cat['products'];
                ?>
            </div>
            <div class="section_slick-slider">
                <?php
                $cat = get_cat_cart(12);
                echo $cat['products'];
                ?>
            </div>
        </div>
    </section>
</main>

<div class="order-success order-success--success">
    <div class="order-success_text">
        <h3>Заказ успешно оформлен</h3>
        <p>Наш менеджер свяжется с вами для уточнения деталей заказа</p>
        <a href="/">Продолжить покупки</a>
    </div>
</div>
<div class="order-success order-success--error">
    <div class="order-success_text">
        <h3>Ошибка оформления заказа</h3>
        <p class="message">Наш менеджер свяжется с вами для уточнения деталей заказа</p>
    </div>
</div>
<div class="added-to-cart">
    Товар добавлен в <a href="/basket" class="addet_to_cart_link">корзину</a>
</div>


<?php include 'includes/view/footer_scirpts.php'; ?>
<script type="text/javascript" src="/scripts/slick/slick.js"></script>
<script src="/scripts/slick.js"></script>
</body>
</html>