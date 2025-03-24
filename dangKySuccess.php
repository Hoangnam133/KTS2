<?php
require_once 'config/db.php';
require_once 'models/DangKy.php';
require_once 'models/HocPhan.php';

session_start();
if (!isset($_SESSION['student_id'])) {
    header('Location: dangNhap.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: hoc_phan.php');
    exit;
}

$dangKyModel = new DangKy($conn);
$hocPhanModel = new HocPhan($conn);

$maDK = $_GET['id'];
$registration = $dangKyModel->getRegistrationById($maDK);
$courses = $dangKyModel->getRegistrationCourses($maDK);

if (!$registration || $registration['MaSV'] !== $_SESSION['student_id']) {
    header('Location: hoc_phan.php');
    exit;
}

ob_start();
?>

<style>
   .card {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.card-header {
    background-color: #007bff; /* Xanh dương */
    color: white;
    border-bottom: 1px solid #dee2e6;
    padding: 15px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.card-body {
    padding: 20px;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    border-collapse: collapse;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
    padding: 8px;
    text-align: left;
}

.table-bordered th {
    background-color: #ffc107; /* Màu vàng */
    color: black;
}

.alert-success {
    background-color: #007bff; /* Xanh dương */
    border-color: #0056b3;
    color: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success a {
    color: white;
    text-decoration: underline;
}

.mt-4 {
    margin-top: 1.5rem;
}

</style>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <h4>Thông Tin Học Phần Đã Lưu</h4>
                <p><a href="index.php">Về trang chủ</a></p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Kết quả sau khi đăng ký học phần:</h5>
                </div>
                <div class="card-body">
                    <h6>Thông tin đăng ký:</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th>MaDK</th>
                            <th>NgayDK</th>
                            <th>MaSV</th>
                        </tr>
                        <tr>
                            <td><?php echo $registration['MaDK']; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($registration['NgayDK'])); ?></td>
                            <td><?php echo $registration['MaSV']; ?></td>
                        </tr>
                    </table>

                    <h6 class="mt-4">Chi tiết đăng ký:</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th>MaDK</th>
                            <th>MaHP</th>
                        </tr>
                        <?php $i = 1; ?>
                        <?php while ($course = $courses->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $course['MaDK']; ?></td>
                                <td><?php echo $course['MaHP']; ?></td>
                            </tr>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layout.php';
?>