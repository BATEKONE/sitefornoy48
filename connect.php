<?php
include_once 'config.php';
try {
    $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=UTF8', DB_USER, DB_PASS);
} catch (PDOException $e) {
    print "Error!: ".$e->getMessage()."<br/>";
    die();
}
