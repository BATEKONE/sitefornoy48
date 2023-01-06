<?php
include_once '../config.php';
include_once '../connect.php';
include_once '../includes/products.class.inc.php';

function resize_image($file, $w, $h, $crop=FALSE) {
    $image_name = substr(basename($file), 0, strrpos(basename($file), '.'));
    $image_ext = substr(basename($file), strrpos(basename($file), '.'));

    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    if(in_array($image_ext, ['.jpeg', '.jpg']))
        $src = imagecreatefromjpeg($file);
    else
        $src = imagecreatefrompng($file);
    if(basename($file))
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

$query = $dbh->query("SELECT * FROM `Product_images`")->fetchAll(PDO::FETCH_OBJ);
foreach($query as $item)
    echo $item->id.' '.$item->image.' '.$item->image_min.'<br />';
die();
$Products = new Products($dbh);
$products = $Products->SelectProducts();
foreach($products as $product) {
    /* @var $product Product */
    $images = $product->getImages();
    foreach($images as $image) {
        if(file_exists(PRODUCT_IMAGE_DIR.'/'.$image->image)) {
            $image_name = substr($image->image, 0, strrpos($image->image, '.'));
            $image_ext = substr($image->image, strrpos($image->image, '.'));
            if(!file_exists(PRODUCT_IMAGE_DIR.'/'.$image_name.'_min'.$image_ext)) {
                $dst = resize_image(PRODUCT_IMAGE_DIR.'/'.$image->image, 500, 500);
                if(imagepng($dst, PRODUCT_IMAGE_DIR.'/'.$image_name.'_min'.$image_ext))
                    $dbh->query("UPDATE `Product_images` SET `image_min` = '{$image_name}_min{$image_ext}' WHERE `id` = {$image->id}");
            }
        }
        /*if(file_exists(PRODUCT_IMAGE_DIR.'/'.$image->image)) {
            $image_name = substr($image->image, 0, strrpos($image->image, '.'));
            $image_ext = substr($image->image, strrpos($image->image, '.'));
            $dst = resize_image(PRODUCT_IMAGE_DIR.'/'.$image->image, 1920, 1920);
            if(imagepng($dst, PRODUCT_IMAGE_DIR.'/'.$image->image))
                echo $image->image.'<br />';
        }*/
    }
}