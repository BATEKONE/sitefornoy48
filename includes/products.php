<?php
include_once BASEDIR.'/includes/products.class.inc.php';

function get_cat($category_id = 0, $page = 0) {
    global $dbh;
    $response = [];

    $Products = new Products($dbh);
    $Categories = new Categories($dbh);

    if($category_id == 0) {
        $products = $Products->SelectProductsPages([
            'page' => $page,
        ]);
        $products_count = $Products->SelectProductsPagesCount();
    }
    else {
        $_category = $Categories->SelectCategory($category_id);
        $args = [
            'page' => $page,
        ];
        if($category_id == 1)
            $args['orderby'] = 'price';

        $nested_categories = Config::GetNestedCategories($category_id);
        if(!sizeof($nested_categories) != 1) {
            $products = $Products->SelectProductsByCategoryNested($category_id, $args, $nested_categories);
            $products_count = $Products->SelectProductsByCategoryNestedCount($category_id, $nested_categories);
        }
        else {
            $products = $Products->SelectProductsByCategory($category_id, $args);
            $products_count = $Products->SelectProductsByCategoryCount($category_id);
        }

    }

    $cat_text = '';
    $categories = $Categories->SelectCategories($category_id);
    if(!empty($categories)) {
        $cat_text .= '<div class="main_menu_other_list main_menu_other_list-sub">';
        foreach($categories as $category) { /* @var $category Category */
            $cat_text .= '<a href="?cat='.$category->getId().'" data-id="'.$category->getId().'" class="main_menu_under_list show_under_cat">
                    <img class="main_menu_other_img" src="'.Config::GetCategoryImageWebDir().'/'.$category->getImage().'" alt="">
                    <div class="main_other_menu_under_list_button">'.$category->getTitle().'</div>
                </a>';
        }
        $cat_text .= '
            </div>
            <div class="under_cat-products big_menu_block_bigger"></div>
            <button type="button" class="big_menu_button load_more_cat hidden" data-cat_id="" data-page="2">Показать ещё</button>';
    }

    $text = '';
    $prev_category_id = $category_id;
    foreach($products as $k => $product) {
        if($k == 0) {
            $_category = $Categories->SelectCategory($product->getCategoryId());
            $prev_category_id = $product->getCategoryId();
        }
        if($product->getCategoryId() != $prev_category_id) {
            $_category = $Categories->SelectCategory($product->getCategoryId());
            $text .= '<h2 style="width: 100%; text-align: center; margin-bottom: 20px;">'.$_category->getTitle().'</h2>';
        }

        $text .= '<div class="big_menu_block">';
        if(!empty($product->getImages())) {
            $text .= '<a href="'.Config::GetProductImageWebDir().'/'.$product->getImage(0)->image.'" data-fancybox="product_'.$product->getId().'"><img class="big_menu_img" src = "'.Config::GetProductImageWebDir().'/'.$product->GetImage(0)->image_min.'" alt = "" ></a>';
            for($i = 1; $i < sizeof($product->getImages()); $i++) {
                $text .= '<a href="'.Config::GetProductImageWebDir().'/'.$product->getImage($i)->image.'" data-fancybox="product_'.$product->getId().'"></a>';
            }
        }
        $text .=  '<h1 class="big_menu_heading1">'.$_category->getTitle().'</h1>
                    <h2 class="big_menu_heading2">'.$product->getTitle().'</h2>';
        foreach($product->getAttributes() as $attribute) {
            $text .= '<div class="product-price">';
            $text .= '<h3 class="big_menu_heading3 product-attribute">'.number_format($attribute->price, 0, '.', ' ').' руб. <span class="product-price-attr_name">'.$attribute->name.'</span></h3>';
            $text .= '<a class="big_menu_button add_to_cart'.($product->getStatus() == 0?' disabled':'').'" href="/basket?product_id='.$product->getId().'" data-id="'.$product->getId().'" data-attribute_id="'.$attribute->id.'">'.'<i class="fa fa-shopping-cart"></i></a>';
            $text .= '</div>';
        }
        $text .= '<p class="big_menu_txt">'.($product->getDescription()).'</p>';
        $text .= ($product->getStatus() == 0?'<div class="not_position">Нет в наличии</div>':'');
        $text .= '</div>';

        $prev_category_id = $product->getCategoryId();
    }


    $addition_text = '';
    $additions = Config::GetCategoryAdditons();
    if(isset($additions[$category_id])) {
        foreach($additions[$category_id] as $addition_id) {
            $category = $Categories->SelectCategory($addition_id);
            $addition_text .= '<h2 style="text-align: center; margin: 20px 0 20px 0;">'.$category->getTitle().'</h2>';
            $addition_cat = get_cat_cart($addition_id);
            $addition_text .= '<div class="section_slick-slider">';
            $addition_text .= $addition_cat['products'];
            $addition_text .= '</div>';
        }
    }

    $gallery_text = false;
    if(is_dir(BASEDIR.'/images/categories_gallery/'.$category_id)) {
        $gallery = scandir(BASEDIR.'/images/categories_gallery/'.$category_id);
        array_shift($gallery);
        array_shift($gallery);
        if(!empty($gallery)) {
            $gallery_text = '<div class="cat-gallery">';
            foreach ($gallery as $gallery_item) {
                $gallery_text .= '<div class="cat-gallery-item"><a href="/images/categories_gallery/'.$category_id.'/'.$gallery_item.'" data-fancybox="cat_gallery"><img src="/images/categories_gallery/'.$category_id.'/'.$gallery_item.'"></a></div>';
            }
            $gallery_text .= '</div>';
        }
    }

    $response['products'] = $text;
    $response['categories'] = $cat_text;
    $response['addition_products'] = $addition_text;
    $response['count'] = sizeof($products);
    $response['cat_gallery'] = $gallery_text;
    $response['show_more'] = ($products_count - ($page * PRODUCTS_PER_PAGE)) >= 0;
    $response['success'] = true;

    return $response;
}

function get_cat_cart($category_id) {
    global $dbh;
    $response = [];

    $Products = new Products($dbh);
    $Categories = new Categories($dbh);

    $category = $Categories->SelectCategory($category_id);
    $products = $Products->SelectProductsByCategory($category_id, [
        'limit' => -1,
    ]);

    $text = '';
    foreach($products as $product) {
        $text .= '<div class="big_menu_block">';
        if(!empty($product->getImages())) {
            $text .= '<a href="'.Config::GetProductImageWebDir().'/'.$product->getImage(0)->image.'" data-fancybox="product_'.$product->getId().'"><img class="big_menu_img_cart" src = "'.Config::GetProductImageWebDir().'/'.$product->GetImage(0)->image_min.'" alt = "" ></a>';
            for($i = 1; $i < sizeof($product->getImages()); $i++) {
                $text .= '<a href="'.Config::GetProductImageWebDir().'/'.$product->getImage($i)->image.'" data-fancybox="product_'.$product->getId().'"></a>';
            }
        }

        $text .= '<h1 class="big_menu_heading1_cart">'.$category->getTitle().'</h1>
                    <h2 class="big_menu_heading2_cart">'.$product->getTitle().'</h2>';
        foreach($product->getAttributes() as $attribute) {
            $text .= '<div class="product_cart-price">';
            $text .= '<h3 class="big_menu_heading3_cart product-attribute">'.number_format($attribute->price, 0, '.', ' ').' руб. <span class="product-price-attr_name_cart">'.$attribute->name.'</span></h3>';
            $text .= '<a class="big_menu_button_cart add_to_cart add_to_cart_cart'.($product->getStatus() == 0?' disabled':'').'" href="/basket?product_id='.$product->getId().'" data-id="'.$product->getId().'" data-attribute_id="'.$attribute->id.'">'.'<i class="fa fa-shopping-cart"></i></a>';
            $text .= '</div>';
        }
        $text .= '<p class="big_menu_txt_cart">'.($product->getDescription()).'</p>';
        $text .= ($product->getStatus() == 0?'<div class="not_position_cart">Нет в наличии</div>':'');
        $text .= '</div>';
    }

    $response['products'] = $text;
    $response['count'] = sizeof($products);
    $response['success'] = true;

    return $response;
}

if(isset($_POST['get_cat'])) {
    $category_id = intval($_POST['get_cat']);
    $page = isset($_POST['page'])?intval($_POST['page']):1;

    $response = get_cat($category_id, $page);
    die(json_encode($response, JSON_UNESCAPED_UNICODE));
}