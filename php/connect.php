<?php
try {
    $user = 'noy48_db';
    $pass = 'qYF7e8Pg';
    $dbh = new PDO('mysql:host=localhost;dbname=noy48_db;charset=UTF8', $user, $pass);
} catch (PDOException $e) {
    print "Error!: ".$e->getMessage()."<br/>";
    die();
}