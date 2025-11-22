<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Thiết lập cơ sở dữ liệu
$servername = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "userdb";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $inputUsername = $_POST['username'] ?? '';
    $inputPassword = $_POST['password'] ?? '';

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $inputUsername]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($inputPassword, $user['password']) && $user['role'] === 'admin') {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            // Chỉ gán lỗi khi form đã submit
            $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
        }

    } catch (PDOException $e) {
        $error = "Kết nối thất bại: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Đăng nhập Hệ thống Quản trị One EFPH</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#0A4D8C", // Xanh dương đậm
              "background-light": "#F5F7FA",
              "background-dark": "#101922",
              "text-light": "#212529",
              "text-dark": "#F5F7FA",
              "subtle-text-light": "#495057",
              "subtle-text-dark": "#A0AEC0",
              "border-light": "#dbe0e6",
              "border-dark": "#2d3748",
              "error": "#DC3545"
            },
            fontFamily: {
              "display": ["Inter", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
<style>
      .material-symbols-outlined {
        font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24
      }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden" style='font-family: Inter, "Noto Sans", sans-serif;'>
<div class="layout-container flex h-full grow flex-col">
<div class="px-4 flex flex-1 justify-center items-center py-5">
<div class="layout-content-container flex flex-col w-full max-w-md flex-1">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<div class="flex flex-col items-center justify-center p-8 bg-white dark:bg-background-dark rounded-xl shadow-sm border border-border-light dark:border-border-dark">
<!-- Logo -->
<div class="mb-6">
<div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
<span class="material-symbols-outlined text-primary text-4xl">
                                corporate_fare
                            </span>
</div>
</div>
<!-- HeadlineText -->

<h1 class="text-text-light dark:text-text-dark tracking-tight text-[28px] font-bold leading-tight px-4 text-center pb-2">Đăng nhập Hệ thống Quản trị One EFPH</h1>
<!-- BodyText -->
<p class="text-subtle-text-light dark:text-subtle-text-dark text-base font-normal leading-normal pb-6 pt-1 px-4 text-center">Vui lòng nhập thông tin của bạn để tiếp tục.</p>
<?php if (!empty($error)): ?>
    <p class="text-error text-sm font-medium text-center mb-4"><?php echo $error; ?></p>
<?php endif; ?>

<div class="w-full flex flex-col gap-4">
<!-- TextField Username -->
<div class="flex w-full flex-wrap items-end gap-4">
<label class="flex flex-col min-w-40 flex-1">
<p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal pb-2">Tên đăng nhập</p>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-white dark:bg-background-dark focus:border-primary dark:focus:border-primary h-12 placeholder:text-subtle-text-light dark:placeholder:text-subtle-text-dark p-[15px] text-base font-normal leading-normal" placeholder="Nhập tên đăng nhập của bạn" value="" name="username"/>
</label>
</div>
<!-- TextField Password -->
<div class="flex w-full flex-wrap items-end gap-4">
<label class="flex flex-col min-w-40 flex-1">
<p class="text-text-light dark:text-text-dark text-sm font-medium leading-normal pb-2">Mật khẩu</p>
<div class="relative flex w-full flex-1 items-stretch">
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-white dark:bg-background-dark focus:border-primary dark:focus:border-primary h-12 placeholder:text-subtle-text-light dark:placeholder:text-subtle-text-dark p-[15px] pr-12 text-base font-normal leading-normal" placeholder="Nhập mật khẩu" type="password" name="password"/>
<button aria-label="Toggle password visibility" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-subtle-text-light dark:text-subtle-text-dark">
<span class="material-symbols-outlined text-xl">visibility_off</span>
</button>
</div>
</label>
</div>
<!-- Optional Link: Forgot Password -->
<div class="flex justify-end w-full mt-1">
<a class="text-sm font-medium text-primary hover:underline" href="#">Quên mật khẩu?</a>
</div>
<!-- Login Button -->
<div class="w-full pt-4">
<button class="flex h-12 w-full items-center justify-center rounded-lg bg-primary px-6 text-base font-semibold text-white shadow-sm transition-colors hover:bg-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary" type="submit">Đăng nhập</button>
</div>
</div>
</div>
</form>
<!-- Footer -->
<div class="pt-8 text-center">
<p class="text-sm text-subtle-text-light dark:text-subtle-text-dark">© 2024 Nhà xuất bản Kinh tế - Tài chính. All rights reserved.</p>
</div>
</div>
</div>
</div>
</div>
</body></html>