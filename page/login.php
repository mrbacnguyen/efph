<?php
    $message = "";
    $message_class = ""; // Thêm biến để lưu class CSS

    if (isset($_COOKIE['user_login']) && $_COOKIE['user_login'] === 'true') {
        header("Location: /index.php");
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // 1. Lấy dữ liệu từ $_POST
        $username = $_POST['username'] ?? ''; // Sử dụng null coalesce operator cho an toàn
        $password = $_POST['password'] ?? '';
        
        // 2. Xử lý logic tại đây (Ví dụ: Kiểm tra dữ liệu, kết nối DB)
        if ($username == "admin" && $password == "admin1231@") {
            $message = "Form đã được xử lý thành công! Xin chào, " . htmlspecialchars($username) . ".";
            $message_class = "success"; // Gán class thành công
            // CHUYỂN HƯỚNG hoặc thiết lập cookie tại đây
            setcookie('user_login', 'true', time() + 3600, "/"); // Thiết lập cookie đăng nhập
            header("Location: /index.php");
        } else {
            $message = "Lỗi, đăng nhâp không thành công. Vui lòng thử lại.";
            $message_class = "error"; // Gán class lỗi
        }
    }
?>

<!DOCTYPE html>
<html lang="vi-VN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang đăng nhập</title>
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/login_style.css">
</head>
<body>

    <?php if (!empty($message)): ?>
        <p class="message <?php echo $message_class; ?>"><?php echo $message; ?></p>
    <?php endif; ?>
    
    <div class="form-login">
        <h2>Đăng nhập</h2>
        <form class="form-login-login" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" required>
            <br><br>
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>
            <br><br>
            <input type="submit" value="Đăng nhập">
        </form>
    </div>


</body>
</html>