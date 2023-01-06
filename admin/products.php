<?php
include_once 'includes/header.php';

$current_cat = isset($_GET['cat'])?intval($_GET['cat']):0;

$categories = $dbh->query('SELECT * FROM `Category` WHERE `parent` = '.$current_cat)->fetchAll(PDO::FETCH_CLASS);
$products = $dbh->query('SELECT * FROM `Products` WHERE `category_id` = '.$current_cat)->fetchAll(PDO::FETCH_CLASS);

$heading_text = 'Товары';
$back_link = '/admin/products.php';
if($current_cat != 0) {
    $cat_title = $dbh->query("SELECT `title` FROM `Category` WHERE `id` = '$current_cat'")->fetchColumn();
    $cat_parent = $dbh->query("SELECT `parent` FROM `Category` WHERE `id` = '$current_cat'")->fetchColumn();

    $heading_text = 'Товары категории "'.$cat_title.'"';
    $back_link = '/admin/products.php?cat='.$cat_parent;
}
?>
<h1 class="admin-heading"><?= $heading_text; ?></h1>
<div class="admin-form-buttons">
    <?php if($current_cat != 0) { ?>
        <a href="<?= $back_link; ?>" class="btn">Назад</a>
        <a href="/admin/product.php?category_id=<?= $current_cat; ?>" class="btn">Добавить товар</a>
        <a href="/admin/category.php?category_id=<?= $current_cat; ?>" class="btn">Изменить категорию</a>
    <?php } ?>
    <a href="/admin/category.php?parent_id=<?= $current_cat; ?>" class="btn">Добавить категорию</a>
</div>
<ul class="list">
<?php
foreach($categories as $category) {
    echo '<li><a href="?cat='.$category->id.'" class="icon-link"><i class="fa fa-folder-o"></i> '.$category->title.'</a></li>';
}
foreach($products as $product) {
    echo '<li><a href="product.php?product_id='.$product->id.'" class="icon-link"><i class="fa fa-file-o"></i> '.$product->title.'</a></li>';
}
?>
</ul>
<?php
include_once 'includes/footer.php';

