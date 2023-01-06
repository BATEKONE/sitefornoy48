<?php
include_once '../../config.php';
include_once BASEDIR.'/connect.php';
include_once BASEDIR.'/includes/products.class.inc.php';

$Categories = new Categories($dbh);

if(isset($_POST['save'])) {
    $mode = $_POST['save'];
    $title = addslashes(trim($_POST['title']));
    $image = $_FILES['image'];

    $fields = [
        'title' => $title,
        'image' => '',
    ];

    if($image['error'] == 0) {
        $basename = basename($image['name']);
        $basename_tmp = $basename;
        $filename = substr($basename, 0, strrpos($basename, '.'));
        $ext = substr($basename, strrpos($basename, '.'));
        $i = 1;
        while(file_exists(CATEGORY_IMAGE_DIR.'/'.$basename_tmp))
            $basename_tmp = $filename.'-'.($i++).$ext;
        $basename = $basename_tmp;

        if(move_uploaded_file($image["tmp_name"], CATEGORY_IMAGE_DIR.'/'.$basename))
            $fields['image'] = $basename;
    }
    else
        unset($fields['image']);

    switch($mode) {
        case 'edit': {
            $category_id = $_POST['category_id'];
            $category = $Categories->SelectCategory($category_id);

            if(!empty($fields['image']) && !empty($category->getImage())) {
                if(file_exists(CATEGORY_IMAGE_DIR.'/'.$category->getImage()))
                    unlink(CATEGORY_IMAGE_DIR.'/'.$category->getImage());
            }

            $Categories->UpdateCategory($category_id, $fields);

            header("Location: ".$_SERVER['HTTP_REFERER']);
            break;
        }
        case 'new': {
            $parent_id = $_POST['parent_id'];
            $fields['parent'] = $parent_id;

            $category_id = $Categories->AddCategory($fields);

            header("Location: /admin/category.php?category_id=".$category_id);
            break;
        }
    }
}

if(isset($_POST['delete'])) {
    $category_id = intval($_POST['delete']);
    $category = $Categories->SelectCategory($category_id);
    $parent_id = $category->getParent();
    $Categories->DeleteCategory($category_id);

    header('Location: /admin/products.php?cat='.$parent_id);
}