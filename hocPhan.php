<?php 
// Include database connection and models
require_once 'config/db.php';
require_once 'models/HocPhan.php';
require_once 'models/SinhVien.php';

// Start session
session_start();

// Create models
$hocPhanModel = new HocPhan($conn);
$sinhVienModel = new SinhVien($conn);

// Ensure SoLuong column exists
$hocPhanModel->addSoLuongColumn();

// Get all courses with available slots
$courses = $hocPhanModel->getCoursesWithSlots();

// Get student info
$student = $sinhVienModel->getStudentById($_SESSION['student_id']);

// Handle course registration (add to cart)
if (isset($_GET['register']) && !empty($_GET['register'])) {
    $maHP = $_GET['register'];

    // Add course to cart in session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if course is already in cart
    if (!in_array($maHP, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $maHP;
    }

    // Redirect to avoid duplicate submission
    header('Location: hocP
    han.php');
    exit;
}

// Start output buffer
ob_start();
?>

<style>
    body {
        background-color: #f0f8ff; /* Light blue background */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .row {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        margin-top: 20px;
    }

    .col-md-12 {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 90%; /* Adjust width as needed */
    }

    h2 {
        color: #007bff; /* Blue heading */
        text-align: center;
        margin-bottom: 30px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .table th, .table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #ffc107; /* Yellow header */
        color: #000;
        font-weight: bold;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .btn {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9em;
    }

    .btn-success {
        background-color: #4caf50; /* Green button */
        color: white;
    }

    .btn-success:hover {
        background-color: #45a049;
    }

    .btn-secondary {
        background-color: #007bff; /* Blue button */
        color: white;
    }

    .btn-secondary:hover {
        background-color: #0056b3;
    }

    .btn-primary {
        background-color: #ffc107; /* Yellow button */
        color: #000;
        padding: 12px 20px;
        font-weight: bold;
    }

    .btn-primary:hover {
        background-color: #ffca28;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 0.8em;
    }

    .text-center {
        text-align: center;
    }

    .mt-4 {
        margin-top: 20px;
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">DANH SÁCH HỌC PHẦN</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                    <th>Số lượng dự kiến</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($courses->num_rows > 0): ?>
                    <?php while ($course = $courses->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $course['MaHP']; ?></td>
                            <td><?php echo $course['TenHP']; ?></td>
                            <td><?php echo $course['SoTinChi']; ?></td>
                            <td><?php echo $course['SoLuong']; ?></td>
                            <td>
                                <?php if (isset($_SESSION['student_id'])): ?>
                                    <a href="hocP
                                    han.php?register=<?php echo $course['MaHP']; ?>"
                                        class="btn btn-sm btn-success">Đăng ký</a>
                                <?php else: ?>
                                    <a href="dangNhap.php" class="btn btn-sm btn-secondary">Đăng nhập để đăng ký</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No courses available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <div class="mt-4">
                <a href="dangky.php" class="btn btn-primary">View Registration Cart (<?php echo count($_SESSION['cart']); ?>)</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Get content from buffer
$content = ob_get_clean();

// Include layout
include 'views/layout.php';
?>