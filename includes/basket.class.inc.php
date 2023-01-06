<?php

class Basket {
    public static function BasketCount() {
        if(!isset($_SESSION['basket']))
            return 0;
        else {
            $count = 0;
            foreach($_SESSION['basket'] as $item)
                $count += sizeof($item);
            return $count;
        }
    }

    public static function BasketAmount() {
        global $dbh;
        include_once 'includes/products.class.inc.php';

        $Products = new Products($dbh);
        if(!isset($_SESSION['basket']))
            return 0;
        else {
            $amount = 0;
            foreach($_SESSION['basket'] as $product_id => $item) {
                $product = $Products->SelectProduct($product_id);
                foreach($item as $attribute_id => $count) {
                    $price = $product->GetAttributeById($attribute_id)->price;
                    $amount += $price * $count;
                }
            }
            return $amount;
        }
    }
    public static function GetBasket() {
        if(isset($_SESSION['basket']))
            return $_SESSION['basket'];
        return [];
    }

    public static function Truncate() {
        $_SESSION['basket'] = [];
    }
}