<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #FF6600;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: white;
        }

        .content {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Calculate cart count
    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
    ?>
    <nav class="navbar navbar-expand-md navbar-dark">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Danh Sách Sinh Viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hocPhan.php">Học Phần</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dangKy.php">Đăng Kí (<?php echo $cartCount; ?>)</a>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['student_id'])): ?>
                            <a class="nav-link" href="dangXuat.php">Đăng Xuất (<?php echo $_SESSION['student_id']; ?>)</a>
                        <?php else: ?>
                            <a class="nav-link" href="dangNhap.php">Đăng Nhập</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container content">
        <?php echo $content; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>