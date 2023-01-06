<?php
const BASEDIR = __DIR__;
const ADMINDIR = __DIR__.'/admin';

const PRODUCT_IMAGE_DIR = BASEDIR.'/images/images_menu';
const CATEGORY_IMAGE_DIR = BASEDIR.'/images/categories';

const ADMIN_LOGIN = 'log';
const ADMIN_PASS = 'pass';

const DB_HOST = 'localhost';
const DB_NAME = 'noy48_db';
const DB_USER = 'noy48_db';
const DB_PASS = 'qYF7e8Pg';

const PRODUCTS_PER_PAGE = 9;

class Config {
    public static function GetBaseUrl() {
        return 'https://'.$_SERVER['SERVER_NAME'];
    }
    public static function GetProductImageWebDir() {
        return Config::GetBaseUrl().str_replace(BASEDIR, '', PRODUCT_IMAGE_DIR);
    }
    public static function GetProductImageAbsDir() {
        return str_replace(BASEDIR, '', PRODUCT_IMAGE_DIR);
    }
    public static function GetCategoryImageWebDir() {
        return Config::GetBaseUrl().str_replace(BASEDIR, '', CATEGORY_IMAGE_DIR);
    }
    public static function GetCategoryImageAbsDir() {
        return str_replace(BASEDIR, '', CATEGORY_IMAGE_DIR);
    }
    public static function GetSettings($name) {
        /* @var $dbh PDO */
        include BASEDIR.'/connect.php';
        $query = $dbh->query("SELECT `value` FROM `Settings` WHERE `name` = '$name'");
        if($query)
            return $query->fetchColumn();
        return false;
    }
    public static function UpdateSettings($name, $value) {
        /* @var $dbh PDO */
        include BASEDIR.'/connect.php';
        $value = addslashes($value);
        $query = $dbh->query("UPDATE `Settings` SET `value` = '$value' WHERE `name` = '$name'");
        return $query;
    }
    public static function GetCategoryAdditons() {
        return [
            1 => [6],
            2 => [6],
            3 => [6],
            4 => [6],
        ];
    }
    public static function GetNestedCategories($category_id = null) {
        $nested_categories = [
            12 => [13],
            13 => [12],
            16 => [13],
            19 => [20],
        ];

        if(!is_null($category_id)) {
            if(isset($nested_categories[$category_id])) {
                $ret = $nested_categories[$category_id];
                array_unshift($ret, $category_id);
                return $ret;
            }
            else
                return [$category_id];
        }
        else
            return $nested_categories;
    }
}