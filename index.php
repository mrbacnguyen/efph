<?php
session_start();

// Kiểm tra xem người dùng đã login chưa
if (!isset($_SESSION['user_id'])) {
    // Chưa đăng nhập → chuyển về trang login
    header("Location: /login.php");
    exit();
}

// Nếu đã đăng nhập, lấy thông tin từ session
$user_email = $_SESSION['email'];
?>