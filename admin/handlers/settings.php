<?php
include '../../config.php';


if(isset($_POST['save'])) {
    $admin_email = $_POST['admin_email'];

    Config::UpdateSettings('admin_email', $admin_email);

    header('Location: '.$_SERVER['HTTP_REFERER']);
}
