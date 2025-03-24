<?php 
// Include database connection and models
require_once 'config/db.php';
require_once 'models/SinhVien.php';

// Start session
session_start();

// Create student model
$sinhVienModel = new SinhVien($conn);

// Process login form
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maSV = $_POST['MaSV'];

    // Check if student exists
    $student = $sinhVienModel->getStudentById($maSV);

    if ($student) {
        // Set session variables
        $_SESSION['student_id'] = $student['MaSV'];
        $_SESSION['student_name'] = $student['HoTen'];

        // Redirect to course listing page
        header('Location: hoc_phan.php');
        exit;
    } else {
        $message = 'Invalid student ID';
    }
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
        align-items: center;
        min-height: 100vh;
    }

    .col-md-6 {
        background-color: #fff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #007bff; /* Blue heading */
        text-align: center;
        margin-bottom: 30px;
    }

    .alert-danger {
        background-color: #ffe6e6; /* Light red background for error */
        color: #d32f2f; /* Dark red text for error */
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        border: 1px solid #ffcdd2;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .btn-primary {
        background-color: #ffc107; /* Yellow button */
        color: #000; /* Black text on yellow */
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        font-weight: bold;
    }

    .btn-primary:hover {
        background-color: #ffca28; /* Slightly darker yellow on hover */
    }

    .mt-3 {
        text-align: center;
        margin-top: 20px;
    }

    .mt-3 a {
        color: #007bff; /* Blue link */
        text-decoration: none;
    }

    .mt-3 a:hover {
        text-decoration: underline;
    }
</style>

<div class="row">
    <div class="col-md-6">
        <h2 class="mb-4">ĐĂNG NHẬP</h2>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="dangNhap.php" method="post">
            <div class="form-group">
                <label for="MaSV">MaSV</label>
                <input type="text" class="form-control" id="MaSV" name="MaSV" required>
            </div>

            <button type="submit" class="btn btn-primary">Đăng Nhập</button>
        </form>

        <div class="mt-3">
            <a href="index.php">Back to List</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

include 'views/layout.php';
?>