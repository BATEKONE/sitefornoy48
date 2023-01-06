<?php
include_once '../../config.php';
include_once BASEDIR.'/connect.php';
include_once BASEDIR.'/includes/products.class.inc.php';

$Products = new Products($dbh);

if(isset($_POST['save'])) {
    $mode = $_POST['save'];
    $title = addslashes(trim($_POST['title']));
    $description = addslashes(trim($_POST['description']));
    $status = $_POST['status'];
    
    $images = $_FILES['images'];
    $images_ids = $_POST['images_ids'];
    $db_images = [];

    $attributes_names = $_POST['attribute_name'];
    $attributes_prices = $_POST['attribute_price'];
    $attributes_ids = $_POST['attribute_id'];

    $fields = [
        'title' => $title,
        'description' => $description,
        'image' => '',
        'status' => $status,
    ];


    if(!empty($images)) {
        foreach($images['name'] as $k => $basename) {
            if($images['error'][$k] == 0 && empty($images_ids[$k])) {
                $basename_tmp = $basename;
                $filename = substr($basename, 0, strrpos($basename, '.'));
                $ext = substr($basename, strrpos($basename, '.'));
                $i = 1;
                while(file_exists(PRODUCT_IMAGE_DIR.'/'.$basename_tmp))
                    $basename_tmp = $filename.'-'.($i++).$ext;
                $basename = $basename_tmp;

                if(move_uploaded_file($images["tmp_name"][$k], PRODUCT_IMAGE_DIR.'/'.$basename)) {
                    $db_images[] = [
                        'id' => '',
                        'image' => $basename,
                        'image_min' => $basename,
                    ];
                }
            }
            else if(!empty($images_ids[$k])) {
                $db_images[] = [
                    'id' => $images_ids[$k],
                    'image' => '',
                    'image_min' => '',
                ];
            }
        }
    }

    $attributes = [];
    foreach($attributes_ids as $i => $attribute_id) {
        if(empty($attributes_names[$i]))
            continue;

        $attributes[] = [
            'id' => $attribute_id,
            'name' => $attributes_names[$i],
            'price' => $attributes_prices[$i],
        ];
    }

    switch($mode) {
        case 'edit': {
            $product_id = $_POST['product_id'];
            $product = $Products->SelectProduct($product_id);

/*            if(!empty($fields['image']) && !empty($product->getImage())) {
                if(file_exists(PRODUCT_IMAGE_DIR.'/'.$product->getImage()))
                    unlink(PRODUCT_IMAGE_DIR.'/'.$product->getImage());
            }*/

            $Products->UpdateProduct($product_id, $fields);
            $Products->UpdateAttributes($product_id, $attributes);
            $Products->UpdateImages($product_id, $db_images);

            header("Location: ".$_SERVER['HTTP_REFERER']);
            break;
        }
        case 'new': {
            $category_id = $_POST['category_id'];
            $fields['category_id'] = $category_id;

            $product_id = $Products->AddProduct($fields);
            $Products->UpdateAttributes($product_id, $attributes);
            $Products->UpdateImages($product_id, $db_images);

            header("Location: /admin/product.php?product_id=".$product_id);
            break;
        }
    }
}

if(isset($_POST['delete'])) {
    $product_id = intval($_POST['delete']);
    $product = $Products->SelectProduct($product_id);
    $category_id = $product->getCategoryId();
    $Products->DeleteProduct($product_id);

    header('Location: /admin/products.php?cat='.$category_id);
}