<?php
session_start();
include_once '../config.php';
$logined = isset($_COOKIE['admin_logined']);

if(isset($_POST['auth_submit'])) {
    if($_POST['login'] == ADMIN_LOGIN && $_POST['password'] == ADMIN_PASS) {
        setcookie('admin_logined', '1', time() + 60 * 60 * 24 * 7);
        $logined = true;
    }
    else
        setcookie('admin_logined', '1', time()-1);
    header('Location: '.$_SERVER['REQUEST_URI']);
}

if(!$logined) {
?>
<!doctype html>
<html lang="ru">
<head>
    <title>Авторизация</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/auth.css">

</head>
<body class="img js-fullheight" style="background-image: url(images/auth_bg.jpg);">
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5">
                <h2 class="heading-section"></h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-wrap p-0">
                    <h3 class="mb-4 text-center">Авторизация</h3>
                    <form action="" method="post" class="signin-form">
                        <div class="form-group">
                            <input name="login" type="text" class="form-control" placeholder="Логин" required>
                        </div>
                        <div class="form-group">
                            <input name="password" id="password-field" type="password" class="form-control" placeholder="Пароль" required>
                            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-primary submit px-3">Войти</button>
                        </div>
                        <input type="hidden" name="auth_submit" value="1">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/auth.js"></script>

</body>
</html>
<?php
    die();
}
?>