<?php
// // ===== XỬ LÝ PHP =====

// // Cấu hình MySQL
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "userdb";

// $login_message = "";

// // Nếu form gửi dữ liệu
// if ($_SERVER["REQUEST_METHOD"] == "POST") {

//     $email = $_POST["email"];
//     $pass = $_POST["password"];

//     // Kết nối MySQL
//     $conn = new mysqli($servername, $username, $password, $dbname);

//     if ($conn->connect_error) {
//         die("Kết nối thất bại: " . $conn->connect_error);
//     }

//     // Lấy mật khẩu hash trong DB
//     $sql = "SELECT password FROM users WHERE username = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $email);
//     $stmt->execute();
//     $stmt->bind_result($hash);
//     $stmt->fetch();
//     $stmt->close();
//     $conn->close();

//     // Kiểm tra đăng nhập
//     if ($hash && password_verify($pass, $hash)) {
//         $login_message = "success";
//     } else {
//         $login_message = "fail";
//     }
// }
?>

<?php
session_start(); // Bắt đầu session

// Nếu người dùng đã đăng nhập rồi → có thể redirect sang trang index.php
if (isset($_SESSION['user_id'])) {
    header("Location: /index.php"); // chuyển sang trang chính
    exit();
}

// ===== XỬ LÝ PHP =====

// Cấu hình MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "userdb";

$login_message = "";

// Nếu form gửi dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $pass = $_POST["password"];

    // Kết nối MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Lấy mật khẩu hash trong DB
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Kiểm tra xem có user không
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($uid, $hash);
        $stmt->fetch();

        // Kiểm tra password
        if (!empty($hash) && password_verify($pass, $hash)) {
            // Lưu session
            $_SESSION['user_id'] = $uid;
            $_SESSION['email'] = $email;

            $login_message = "success";

            // Chuyển sang trang chính
            header("Location: index.php");
            exit();
        } else {
            $login_message = "fail";
        }
    } else {
        $login_message = "fail";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html class="light" lang="vi">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Đăng nhập vào ONE</title>

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

<style>
    body {
        font-family: 'Inter', sans-serif;
    }
</style>

<script id="tailwind-config">
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "primary": "#135bec",
                "background-light": "#f6f6f8",
                "background-dark": "#101622",
            },
            fontFamily: { "display": ["Inter"] },
        },
    },
}
</script>
</head>

<body class="font-display">

<div class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light p-4">

<div class="layout-content-container w-full max-w-md flex-col">
<div class="flex flex-col items-center gap-6 rounded-xl border border-gray-300 bg-white p-8">

<h1 class="text-primary text-[32px] font-bold text-center">ONE</h1>

<div class="text-center">
<p class="text-gray-900 text-[24px] font-bold">Đăng nhập vào tài khoản của bạn</p>
<p class="text-gray-500 text-sm">Chào mừng trở lại! Vui lòng nhập thông tin của bạn.</p>
</div>

<!-- FORM ĐĂNG NHẬP -->
<form method="POST" class="flex w-full flex-col gap-4">

<!-- THÔNG BÁO LOGIN -->
<?php if ($login_message === "success") { ?>
    <p class="text-green-600 font-medium text-center">Đăng nhập thành công!</p>
<?php } elseif ($login_message === "fail") { ?>
    <p class="text-red-600 font-medium text-center">Sai email hoặc mật khẩu!</p>
<?php } ?>

<label class="flex flex-col">
<p class="text-sm font-medium pb-2">Email</p>
<div class="relative">
<input name="email" class="form-input w-full rounded-lg border border-gray-300 h-12 pl-10 p-3" placeholder="Nhập email" type="email" required>
<span class="material-symbols-outlined text-gray-400 absolute left-3 top-1/2 -translate-y-1/2">mail</span>
</div>
</label>

<label class="flex flex-col">
<div class="flex justify-between pb-2">
<p class="text-sm font-medium">Mật khẩu</p>
<a class="text-primary text-sm hover:underline" href="#">Quên mật khẩu?</a>
</div>
<div class="relative">
<input name="password" class="form-input w-full rounded-lg border border-gray-300 h-12 pl-10 p-3" placeholder="Nhập mật khẩu" type="password" required>
<span class="material-symbols-outlined text-gray-400 absolute left-3 top-1/2 -translate-y-1/2">lock</span>
</div>
</label>

<button class="w-full bg-primary h-12 rounded-lg text-white font-semibold hover:bg-primary/90" type="submit">
    Đăng nhập
</button>

</form>

</div>
</div>

</div>



</body>
</html>
