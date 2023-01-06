<?php

function is_night() {
    return date('G') < 9 || (date('G') >= 20 && intval(date('i')) >= 30);
}

function is_doing_ajax() {
    return isset($_REQUEST['doing_ajax']);
}