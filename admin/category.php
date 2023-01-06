<?php
$page_scripts = ['/admin/js/product.js'];

include_once 'includes/header.php';
include_once BASEDIR.'/includes/products.class.inc.php';

if(isset($_GET['parent_id']))
    $mode = 'new';
else if(isset($_GET['category_id']))
    $mode = 'edit';
else
    $mode = false;

$Categories = new Categories($dbh);

if($mode == 'edit') {
    $Products = new Products($dbh);
    $category_id = intval($_GET['category_id']);
    $category = $Categories->SelectCategory($category_id);
}
else if($mode == 'new') {
    $parent_id = intval($_GET['parent_id']);
    if($parent_id == 0)
        $cat_name = 'Корневая';
    else
        $cat_name = $Categories->SelectCategory($parent_id)->getTitle();
}

if(!$mode || ($mode == 'edit' && !$category))
    echo 'Категория с таким id не найдена!';
else {
    ?>
    <h1 class="admin-heading"><?= ($mode == 'edit')?'Категория "'.$category->getTitle().'"':'Добавить подкатегорию в категорию "'.$cat_name.'"'; ?></h1>
    <div class="admin-form-buttons">
        <a href="/admin/products.php?cat=<?= ($mode == 'edit')?$category->getParent():$parent_id; ?>" class="btn">Назад</a>
        <button type="submit" class="btn green" form="product-form">Сохранить</button>
        <?php if($mode == 'edit') { ?>
            <button type="submit" class="btn red product-delete" form="delete-form">Удалить</button>
        <?php } ?>
    </div>
    <?php if($mode == 'edit') { ?>
        <form id="delete-form" method="post" action="/admin/handlers/category.php">
            <input type="hidden" name="delete" value="<?= $category->getId(); ?>">
        </form>
    <?php } ?>
    <form id="product-form" action="/admin/handlers/category.php" method="post" class="admin-form" enctype="multipart/form-data">
        <div class="admin-form-row">
            <div class="admin-form-label">Название</div>
            <input type="text" name="title" placeholder="Название" value="<?= ($mode == 'edit')?htmlspecialchars($category->getTitle()):''; ?>" required>
        </div>
        <div class="admin-form-row">
            <div class="admin-form-label">Изображение</div>
            <input type="file" name="image"<?= ($mode == 'new')?' required':''; ?> accept="image/*">

            <?php if($mode=='edit' && !empty($category->getImage())) { ?>
                <div style="margin-top: 10px;">
                    <a href="<?= Config::GetCategoryImageWebDir().'/'.$category->getImage(); ?>" data-fancybox="product">
                        <img src="<?= Config::GetCategoryImageWebDir().'/'.$category->getImage(); ?>" alt="" class="admin-image-min" />
                    </a>
                </div>
            <?php } ?>
        </div>
        <?php if($mode == 'new') { ?>
            <input type="hidden" name="parent_id" value="<?= $parent_id; ?>">
        <?php } ?>
        <?php if($mode == 'edit') { ?>
            <input type="hidden" name="category_id" value="<?= $category_id; ?>">
        <?php } ?>
        <input type="hidden" name="save" value="<?= $mode; ?>">
    </form>
<?php } ?>
<?php
include_once 'includes/footer.php';
