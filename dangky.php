<?php 
require_once 'config/db.php';
require_once 'models/HocPhan.php';
require_once 'models/DangKy.php';
require_once 'models/SinhVien.php';

session_start();

$hocPhanModel = new HocPhan($conn);
$dangKyModel = new DangKy($conn);
$sinhVienModel = new SinhVien($conn);

$student = $sinhVienModel->getStudentById($_SESSION['student_id']);

if (isset($_GET['remove']) && !empty($_GET['remove'])) {
    $maHP = $_GET['remove'];
    if (isset($_SESSION['cart']) && in_array($maHP, $_SESSION['cart'])) {
        $key = array_search($maHP, $_SESSION['cart']);
        unset($_SESSION['cart'][$key]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    header('Location: dangky.php');
    exit;
}

if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header('Location: dangky.php');
    exit;
}

if (isset($_GET['save']) && isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $maDK = $dangKyModel->createRegistration($_SESSION['student_id']);
    if ($maDK) {
        foreach ($_SESSION['cart'] as $maHP) {
            $dangKyModel->addCourseToRegistration($maDK, $maHP);
            $hocPhanModel->decreaseSlots($maHP);
        }
        $_SESSION['cart'] = [];
        header('Location: dangkysuccess.php?id=' . $maDK);
        exit;
    }
}

$cartCourses = [];
$totalCredits = 0;
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $maHP) {
        $course = $hocPhanModel->getCourseById($maHP);
        if ($course) {
            $cartCourses[] = $course;
            $totalCredits += $course['SoTinChi'];
        }
    }
}

ob_start();
?>

<style>
    body {
        background-color: #f8f9fa;
    }
    .container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        color: #007bff;
    }
    .table thead {
        background-color: #ffc107;
        color: #000;
    }
    .btn-warning, .btn-success {
        color: #fff;
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4">Đăng Kí Học Phần</h2>
    <?php if (!isset($_SESSION['student_id'])): ?>
        <div class="alert alert-warning">
            <p>Bạn cần đăng nhập để đăng ký học phần.</p>
            <a href="dangNhap.php" class="btn btn-primary">Đăng Nhập</a>
        </div>
    <?php elseif (count($cartCourses) > 0): ?>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Mã HP</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartCourses as $course): ?>
                    <tr>
                        <td><?php echo $course['MaHP']; ?></td>
                        <td><?php echo $course['TenHP']; ?></td>
                        <td><?php echo $course['SoTinChi']; ?></td>
                        <td>
                            <a href="dangky.php?remove=<?php echo $course['MaHP']; ?>" class="btn btn-sm btn-danger">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><strong>Số lượng học phần: <?php echo count($cartCourses); ?></strong></td>
                    <td colspan="2"><strong>Tổng số tín chỉ: <?php echo $totalCredits; ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-4 text-center">
            <a href="dangky.php?clear=1" class="btn btn-warning">Xóa Đăng Kí</a>
            <a href="dangky.php?save=1" class="btn btn-success">Lưu Đăng Ký</a>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Không có học phần nào được chọn. <a href="hoc_phan.php">Xem danh sách học phần</a> để đăng ký.
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'views/layout.php';
?>