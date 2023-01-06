<?php

class Product {
    private $title = null;
    private $image = null;
    private $images = [];
    private $attributes = [];
    private $id = 0;
    private $category_id = 0;
    private $description = '';
    private $status = 1;

    public function getTitle() {
        return $this->title;
    }


    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    public function GetImage($i = 0) {
        if(isset($this->images[$i]))
            return $this->images[$i];
        return false;
    }
    /**
     * @param array $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    /**
     * @return array
     */
    public function getAttributes() {
        return $this->attributes;
    }
    public function setAttributes($attributes) {
        $this->attributes = $attributes;
    }
    public function GetAttributeById($attribute_id) {
        foreach($this->attributes as $attribute) {
            if($attribute->id == $attribute_id)
                return $attribute;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getCategoryId() {
        return $this->category_id;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    public function SetData($data) {
        $this->id = $data->id;
        $this->title = $data->title;
        $this->image = $data->image;
        $this->category_id = $data->category_id;
        $this->description = $data->description;
        $this->status = $data->status;
    }
}

class Products {
    /** @var PDO **/
    private $PDO;
    private $table = 'Products';

    public function __construct($PDO) {
        $this->PDO = $PDO;
    }

    public function SelectProduct($product_id) {
        $query = $this->PDO->query("SELECT * FROM `$this->table` WHERE `id` = $product_id");
        if($query) {
            $product = new Product();
            $product->SetData($query->fetchObject());
            $product->setAttributes($this->SelectProductAttributes($product_id));
            $product->setImages($this->SelectProductImages($product_id));

            return $product;
        }
        return false;
    }

    public function SelectProductAttributes($product_id) {
        $attributes = [];
        $attributes_query = $this->PDO->query("SELECT * FROM `Attribute` WHERE `product_id` = $product_id");
        if($attributes_query)
            $attributes = $attributes_query->fetchAll(PDO::FETCH_OBJ);

        return $attributes;
    }
    public function SelectProductImages($product_id) {
        $images = [];
        $images_query = $this->PDO->query("SELECT * FROM `Product_images` WHERE `product_id` = $product_id");
        if($images_query)
            $images = $images_query->fetchAll(PDO::FETCH_OBJ);

        return $images;
    }

    public function SelectProductsByCategory($category_id, $user_config = []) {
        $config = [
            'page' => 1,
            'limit' => PRODUCTS_PER_PAGE,
            'orderby' => 'id',
            'order' => 'ASC',
        ];
        foreach($user_config as $k => $item)
            $config[$k] = $item;

        $sql = "SELECT Products.* FROM `Products`
            INNER JOIN `Attribute` ON Products.id = `Attribute`.product_id 
            WHERE Products.`category_id` = '$category_id'
            GROUP BY Products.id";

        if($config['orderby'] == 'price')
            $sql .= ' ORDER BY `Attribute`.price '.$config['order'];
        else
            $sql .= ' ORDER BY Products.'.$config['orderby'].' '.$config['order'];

        if($config['limit'] >= 0)
            $sql .= " LIMIT ".(($config['page'] - 1) * $config['limit']).",".$config['limit'];


        $products = [];
        $query_products = $this->PDO->query($sql)->fetchAll(PDO::FETCH_OBJ);
        foreach($query_products as $query_product) {
            $tmp_product = new Product();
            $tmp_product->SetData($query_product);
            $tmp_product->setAttributes($this->SelectProductAttributes($tmp_product->getId()));
            $tmp_product->setImages($this->SelectProductImages($tmp_product->getId()));
            $products[] = $tmp_product;
        }
        return $products;
    }
    public function SelectProductsByCategoryCount($category_id) {
        $sql = "SELECT COUNT(`id`) FROM `Products` WHERE `category_id` = $category_id";
        return $this->PDO->query($sql)->fetchColumn();
    }


    public function SelectProductsByCategoryNested($category_id, $user_config = [], $nested_categories = []) {
        if(empty($nested_categories))
            $nested_categories = Config::GetNestedCategories($category_id);

        $config = [
            'page' => 1,
            'limit' => PRODUCTS_PER_PAGE,
            'orderby' => 'id',
            'order' => 'ASC',
        ];
        foreach($user_config as $k => $item)
            $config[$k] = $item;

        $sql = "SELECT Products.* FROM `Products`
            INNER JOIN `Attribute` ON Products.id = `Attribute`.product_id 
            WHERE Products.`category_id` IN(".implode(',', $nested_categories).")
            GROUP BY Products.id";


        $sql .= ' ORDER BY FIELD(Products.category_id, '.implode(',', $nested_categories).')';
        if($config['orderby'] == 'price')
            $sql .= ', `Attribute`.price '.$config['order'];
        else
            $sql .= ', Products.'.$config['orderby'].' '.$config['order'];

        
        if($config['limit'] >= 0)
            $sql .= " LIMIT ".(($config['page'] - 1) * $config['limit']).",".$config['limit'];


        $products = [];
        $query_products = $this->PDO->query($sql)->fetchAll(PDO::FETCH_OBJ);
        foreach($query_products as $query_product) {
            $tmp_product = new Product();
            $tmp_product->SetData($query_product);
            $tmp_product->setAttributes($this->SelectProductAttributes($tmp_product->getId()));
            $tmp_product->setImages($this->SelectProductImages($tmp_product->getId()));
            $products[] = $tmp_product;
        }
        return $products;
    }
    public function SelectProductsByCategoryNestedCount($category_id, $nested_categories = []) {
        if(empty($nested_categories))
            $nested_categories = Config::GetNestedCategories($category_id);

        $sql = "SELECT COUNT(`id`) FROM `Products` WHERE `category_id` IN(".implode(',', $nested_categories).")";
        return $this->PDO->query($sql)->fetchColumn();
    }


    public function SelectProductsPages($user_config = []) {
        $config = [
            'page' => 1,
            'limit' => 9,
            'orderby' => 'id',
        ];
        foreach($user_config as $k => $item)
            $config[$k] = $item;
        $sql = "SELECT * FROM `Products` ORDER BY `category_id`";
        if($config['limit'] >= 0)
            $sql .= " LIMIT ".(($config['page'] - 1) * $config['limit']).",".$config['limit'];


        $products = [];
        $query_products = $this->PDO->query($sql)->fetchAll(PDO::FETCH_OBJ);
        foreach($query_products as $query_product) {
            $tmp_product = new Product();
            $tmp_product->SetData($query_product);
            $tmp_product->setAttributes($this->SelectProductAttributes($tmp_product->getId()));
            $tmp_product->setImages($this->SelectProductImages($tmp_product->getId()));
            $products[] = $tmp_product;
        }
        return $products;
    }
    public function SelectProductsPagesCount() {
        $sql = "SELECT COUNT(`id`) FROM `Products`";
        return $this->PDO->query($sql)->fetchColumn();
    }
    public function SelectProducts() {
        $sql = "SELECT * FROM `Products`";

        $products = [];
        $query_products = $this->PDO->query($sql)->fetchAll(PDO::FETCH_OBJ);
        foreach($query_products as $query_product) {
            $tmp_product = new Product();
            $tmp_product->SetData($query_product);
            $tmp_product->setAttributes($this->SelectProductAttributes($tmp_product->getId()));
            $tmp_product->setImages($this->SelectProductImages($tmp_product->getId()));
            $products[] = $tmp_product;
        }
        return $products;
    }
    public function UpdateProduct($product_id, $fields) {
        $sql = "UPDATE `Products` SET ";
        foreach($fields as $k => $v)
            $sql = $sql."`$k` = '$v', ";
        $sql[strlen($sql)-2] = ' ';
        $sql = $sql."WHERE `id`='".$product_id."' LIMIT 1";

        $this->PDO->query($sql);
    }

    public function AddProduct($fields) {
        $sql = "INSERT INTO `Products` SET ";
        foreach($fields as $k => $v)
            $sql = $sql."`$k` = '$v', ";
        $sql[strlen($sql)-2] = ' ';

        $this->PDO->query($sql);
        return $this->PDO->lastInsertId();
    }

    public function UpdateAttributes($product_id, $attributes) {
        $exist_ids = [];
        foreach($attributes as $attribute) {
            if(!empty($attribute['id'])) {
                $this->PDO->query("UPDATE `Attribute` SET `name` = '{$attribute['name']}', `price` = '{$attribute['price']}' WHERE `id` = '{$attribute['id']}'");
                $exist_ids[] = $attribute['id'];
            }
            else {
                $this->PDO->query("INSERT INTO `Attribute` SET `name` = '{$attribute['name']}', `price` = '{$attribute['price']}', `product_id` = {$product_id}");
                $exist_ids[] = $this->PDO->lastInsertId();
            }
        }
        if(!empty($exist_ids))
            $this->PDO->query("DELETE FROM `Attribute` WHERE `id` NOT IN (".implode(',', $exist_ids).") AND `product_id` = $product_id");
    }
    public function UpdateImages($product_id, $images) {
        $exist_ids = [];
        foreach($images as $image) {
            if(empty($image['id'])) {
                $this->PDO->query("INSERT INTO `Product_images` SET `image` = '{$image['image']}', `image_min` = '{$image['image_min']}', `product_id` = {$product_id}");
                $exist_ids[] = $this->PDO->lastInsertId();
            }
            else {
                $exist_ids[] = $image['id'];
            }
        }
        if(empty($exist_ids))
            $exist_ids[] = 0;

        $images_query = $this->PDO->query("SELECT * FROM `Product_images` WHERE `id` NOT IN (".implode(',', $exist_ids).") AND `product_id` = $product_id");
        if($images_query) {
            foreach($images_query->fetchAll(PDO::FETCH_OBJ) as $not_exists_image) {
                if(file_exists(PRODUCT_IMAGE_DIR.'/'.$not_exists_image->image))
                    unlink(PRODUCT_IMAGE_DIR.'/'.$not_exists_image->image);
                if(file_exists(PRODUCT_IMAGE_DIR.'/'.$not_exists_image->image_min))
                    unlink(PRODUCT_IMAGE_DIR.'/'.$not_exists_image->image_min);
            }
        }
        $this->PDO->query("DELETE FROM `Product_images` WHERE `id` NOT IN (".implode(',', $exist_ids).") AND `product_id` = $product_id");
    }

    public function DeleteProduct($product_id) {
        $product = $this->SelectProduct($product_id);
        foreach($product->getImages() as $image) {
            if(file_exists(PRODUCT_IMAGE_DIR.'/'.$image->image))
                unlink(PRODUCT_IMAGE_DIR.'/'.$image->image);
            if(file_exists(PRODUCT_IMAGE_DIR.'/'.$image->image_min))
                unlink(PRODUCT_IMAGE_DIR.'/'.$image->image_min);
        }

        $this->PDO->query("DELETE FROM `Products` WHERE `id` = '$product_id'");
    }
}

class Category {
    private $id = 0;
    private $title = '';
    private $image = null;
    private $parent = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function SetData($data) {
        $this->id = $data->id;
        $this->title = $data->title;
        $this->image = $data->image;
        $this->parent = $data->parent;
    }
}

class Categories {
    /** @var PDO **/
    private $PDO;

    public function __construct($PDO) {
        $this->PDO = $PDO;
    }

    public function SelectCategory($category_id) {
        $query = $this->PDO->query("SELECT * FROM `Category` WHERE `id` = '$category_id'");
        if($query) {
            $category = new Category();
            $category->SetData($query->fetch(PDO::FETCH_OBJ));
            return $category;
        }
        return false;
    }

    public function SelectCategories($parent = -1) {
        if($parent == -1)
            $sql = "SELECT * FROM `Category`";
        else
            $sql = "SELECT * FROM `Category` WHERE `parent` = $parent";
        $query = $this->PDO->query($sql);
        $categories = [];
        if($query) {
            foreach($query->fetchAll(PDO::FETCH_OBJ) as $item) {
                $category = new Category();
                $category->SetData($item);
                $categories[] = $category;
            }
        }
        return $categories;
    }

    public function UpdateCategory($category_id, $fields) {
        $sql = "UPDATE `Category` SET ";
        foreach($fields as $k => $v)
            $sql = $sql."`$k` = '$v', ";
        $sql[strlen($sql)-2] = ' ';
        $sql = $sql."WHERE `id`='".$category_id."' LIMIT 1";

        $this->PDO->query($sql);
    }

    public function AddCategory($fields) {
        $sql = "INSERT INTO `Category` SET ";
        foreach($fields as $k => $v)
            $sql = $sql."`$k` = '$v', ";
        $sql[strlen($sql)-2] = ' ';

        $this->PDO->query($sql);
        return $this->PDO->lastInsertId();
    }

    public function DeleteCategory($category_id) {
        $category = $this->SelectCategory($category_id);
        if(file_exists(PRODUCT_IMAGE_DIR.'/'.$category->getImage()))
            unlink(PRODUCT_IMAGE_DIR.'/'.$category->getImage());

        $this->PDO->query("DELETE FROM `Category` WHERE `id` = '$category_id'");
    }

}