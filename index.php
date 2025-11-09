<?php
    if (isset($_COOKIE['user_login']) && $_COOKIE['user_login'] === 'true') {
        echo "Bạn đã đăng nhập rồi.";
    } else {
        header("Location: page/login.php");
        exit;
    }
?>