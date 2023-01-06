<?php
$page_scripts = ['/admin/js/product.js'];

include_once 'includes/header.php';
include_once BASEDIR.'/includes/products.class.inc.php';

if(isset($_GET['category_id']))
    $mode = 'new';
else if(isset($_GET['product_id']))
    $mode = 'edit';
else
    $mode = false;

$Categories = new Categories($dbh);

if($mode == 'edit') {
    $Products = new Products($dbh);
    $product_id = intval($_GET['product_id']);
    $product = $Products->SelectProduct($product_id);
}
else if($mode == 'new') {
    $category_id = intval($_GET['category_id']);
    $cat_name = $Categories->SelectCategory($category_id)->getTitle();
}

if(!$mode || ($mode == 'edit' && !$product))
    echo 'Продукт с таким id не найден!';
else {
?>
    <h1 class="admin-heading"><?= ($mode == 'edit')?'Товар "'.$product->getTitle().'"':'Добавить товар в категорию "'.$cat_name.'"'; ?></h1>
    <div class="admin-form-buttons">
        <a href="/admin/products.php?cat=<?= ($mode == 'edit')?$product->getCategoryId():$category_id; ?>" class="btn">Назад</a>
        <button type="submit" class="btn green" form="product-form">Сохранить</button>
        <?php if($mode == 'edit') { ?>
            <button type="submit" class="btn red product-delete" form="delete-form">Удалить</button>
        <?php } ?>
    </div>
    <?php if($mode == 'edit') { ?>
        <form id="delete-form" method="post" action="/admin/handlers/product.php">
            <input type="hidden" name="delete" value="<?= $product->getId(); ?>">
        </form>
    <?php } ?>
    <form id="product-form" action="/admin/handlers/product.php" method="post" class="admin-form" enctype="multipart/form-data">
        <div class="admin-form-row">
            <div class="admin-form-label">Название</div>
            <input type="text" name="title" placeholder="Название" value="<?= ($mode == 'edit')?htmlspecialchars($product->getTitle()):''; ?>" required>
        </div>
        <div class="admin-form-row">
            <div class="admin-form-label">Описание</div>
            <textarea name="description" placeholder="Описание"><?= ($mode == 'edit')?htmlspecialchars($product->getDescription()):''; ?></textarea>
        </div>
        <div class="admin-form-row">
            <div class="admin-form-label">Изображения</div>
            <input type="file" name="image" id="product-image" accept="image/*">

            <div class="image-elem template">
                <div>
                    <div class="image-elem-name"></div>
                    <img src="" alt="">
                </div>
                <div class="image-elem-delete hidden">
                    <i class="fa fa-times"></i>
                </div>
                <input type="file" name="images[]" accept="image/*">
                <input type="hidden" name="images_ids[]" value="">
            </div>
            <div class="images-wrap">
                <?php
                if($mode == 'edit') {
                    foreach($product->getImages() as $image) { ?>
                        <div class="image-elem">
                            <div>
                                <div class="image-elem-name"></div>
                                <a href="<?= Config::GetBaseUrl().Config::GetProductImageAbsDir().'/'.$image->image; ?>" data-fancybox="product"><img src="<?= Config::GetBaseUrl().Config::GetProductImageAbsDir().'/'.$image->image_min; ?>" class="admin-image-min" alt=""></a>
                            </div>
                            <div class="image-elem-delete">
                                <i class="fa fa-times"></i>
                            </div>
                            <input type="file" name="images[]" accept="image/*">
                            <input type="hidden" name="images_ids[]" value="<?= $image->id; ?>">
                        </div>
                <?php
                    }
                }
                ?>
            </div>

            <?php if($mode=='edit' && !empty($product->getImage())) { ?>
            <!--<div style="margin-top: 10px;">
                <a href="<?/*= Config::GetBaseUrl().Config::GetProductImageAbsDir().'/'.$product->getImage(); */?>" data-fancybox="product">
                    <img src="<?/*= Config::GetBaseUrl().Config::GetProductImageAbsDir().'/'.$product->getImage(); */?>" alt="" class="admin-image-min" />
                </a>
            </div>-->
            <?php } ?>
        </div>
        <div class="admin-form-row">
            <div class="admin-form-label">Статус</div>
            <ul class="radio-wrap">
                <li><label><input type="radio" name="status" value="1"<?= ($mode == 'edit' && $product->getStatus() == 1)?' checked':''; ?>> В наличии</label></li>
                <li><label><input type="radio" name="status" value="0"<?= (($mode == 'edit' && $product->getStatus() == 0) || $mode == 'new')?' checked':''; ?>> Нет в наличии</label></li>
            </ul>
        </div>
        <div class="admin-form-row">
            <div class="admin-form-label">Цены</div>
            <div class="admin-form-attributes">
                <?php
                if($mode == 'edit' && sizeof($product->getAttributes()) >= 1) {
                    $attributes = $product->getAttributes();
                    $count = sizeof($attributes);
                    foreach($attributes as $i => $attribute) { ?>
                        <div class="admin-form-attribute">
                            <input type="text" name="attribute_name[]" placeholder="Название" value="<?= htmlspecialchars($attribute->name); ?>" class="admin-form-attribute-title">
                            <input type="number" name="attribute_price[]" placeholder="Цена" value="<?= $attribute->price; ?>" min="0">
                            <input type="hidden" name="attribute_id[]" value="<?= $attribute->id; ?>">
                            <div class="admin-form-attribute-delete<?= ($count == 1)?' hidden':''; ?>">
                                <i class="fa fa-times"></i>
                            </div>
                        </div>
                <?php }
                }
                else {
                ?>
                    <div class="admin-form-attribute">
                        <input type="text" name="attribute_name[]" placeholder="Название" class="admin-form-attribute-title">
                        <input type="number" name="attribute_price[]" placeholder="Цена" value="0">
                        <input type="hidden" name="attribute_id[]" value="">
                        <div class="admin-form-attribute-delete hidden">
                            <i class="fa fa-times"></i>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <button type="button" class="btn admin-form-attribute-add"><i class="fa fa-plus"></i> Добавить вариацию</button>
        </div>
        <?php if($mode == 'new') { ?>
            <input type="hidden" name="category_id" value="<?= $category_id; ?>">
        <?php } ?>
        <?php if($mode == 'edit') { ?>
            <input type="hidden" name="product_id" value="<?= $product_id; ?>">
        <?php } ?>
        <input type="hidden" name="save" value="<?= $mode; ?>">
    </form>
<?php } ?>
<?php
include_once 'includes/footer.php';
