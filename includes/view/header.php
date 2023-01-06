<header id="menu">
    <nav id="menu_nav">
        <div class="menu_nav_sector1">
            <img src="/images/Logo.png" alt="" class="menu_img_sector1">
        </div>
        <div class="menu_nav_sector2">
            <img src="images/delivery.png" alt="" class="menu_nav_sector2_delivery">
            <h1 class="menu_nav_sector2_heading1">Доставка</h1>
            <div class="menu_nav_sector2_call_and_number">
                <img src="images/call.svg" alt="" class="menu_nav_sector2_call">
                <a href="tel:+7(4742)71-28-88" class="menu_nav_sector2_call_link">+7(4742)-<span>71-28-88</span></a>
            </div>
            <h2 class="menu_nav_sector2_heading3">Бесплатная доставка <br> от 700 руб</h2>
            <p class="menu_nav_sector3_text">пн-вс 09:00-21:00</p>
        </div>
        <div class="menu_nav_sector3">
<!--            <div class="menu_nav_sector3-graf">-->
<!--                <h1 class="menu_nav_sector3_heading">График доставки:</h1>-->
<!--            </div>-->
            <div class="footer_information_chart2">
                <h2 class="footer_information_heading22">График работы:</h2>
                <h3 class="footer_information_heading33">пн-вс: круглосуточно</h3>
            </div>
            <div class="menu_nav_sector1_address">
                <div class="footer_information_address_and_call">
                    <img src="images/address.svg" alt="" class="menu_img">
                    <a href="https://yandex.ru/maps/org/noy/5626810961/?ll=39.658362%2C52.633953&z" class="menu_address_p" target="_blank">г. Липецк, ул. Студеновская 195</a>
                </div>
                <div class="footer_information_address_and_call">
                    <img src="images/address.svg" alt="" class="menu_img">
                    <a href="https://yandex.ru/maps/org/noy/110330510290/?ll=39.501349%2C52.549736&z" class="menu_address_p" target="_blank">г. Липецк, село Подгорное ул. Прогонная 43</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<a href="/basket" class="menu_nav_sector4_basket">
    <img src="images/basket.svg" alt="">
    <span class="basket-count"><?= Basket::BasketCount(); ?></span>
</a>
