<section id="main_menu">
    <div class="main_menu_container container">
        <ul class="main_menu_ul">
            <a href="/" class="main_menu_link"><li class="main_menu_ul_li1">Главная</li></a>
            <a href="/basket" class="main_menu_link"><li class="main_menu_ul_li2">Корзина</li></a>
            <a href="<?= $urlArray[0] == 'aboutus'?'#aboutus':'/aboutus'; ?>" class="main_menu_link"><li class="main_menu_ul_li3">О нас</li></a>
            <a href="<?= $urlArray[0] == 'gallery'?'#gallery':'/gallery'; ?>" class="main_menu_link"><li class="main_menu_ul_li3">Фотогалерея</li></a>
            <a href="<?= $urlArray[0] == 'feedback'?'#feedback':'/feedback'; ?>" class="main_menu_link"><li class="main_menu_ul_li3">Отзыв</li></a>
        </ul>
    </div>
</section>