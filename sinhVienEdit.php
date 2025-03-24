<?php

require_once 'config/db.php';
require_once 'models/SinhVien.php';


$sinhVienModel = new SinhVien($conn);


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$maSV = $_GET['id'];
$student = $sinhVienModel->getStudentById($maSV);

if (!$student) {
    header('Location: index.php');
    exit;
}


$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoTen = $_POST['HoTen'];
    $gioiTinh = $_POST['GioiTinh'];
    $ngaySinh = $_POST['NgaySinh'];
    $maNganh = $_POST['MaNganh'];

    $hinh = $student['Hinh'];

    
    if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'Content/images/';

       
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['Hinh']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['Hinh']['tmp_name'], $uploadFile)) {
            $hinh = '/' . $uploadFile;
        }
    }

    
    if ($sinhVienModel->updateStudent($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh)) {
        header('Location: index.php');
        exit;
    } else {
        $message = 'Error updating student. Please try again.';
    }
}


$majors = $sinhVienModel->getAllMajors();


ob_start();
?>

<style>
    body {
        background-color: #ffeb3b; /* Màu nền vàng */
    }

    h2 {
        color: #007bff; /* Tiêu đề màu xanh dương */
    }

    .form-control {
        border: 2px solid #007bff; /* Viền input màu xanh dương */
    }

    .form-group label {
        font-weight: bold;
        color: #007bff; /* Nhãn (label) màu xanh dương */
    }

    .btn-success {
        background-color: #ffeb3b; /* Nút Save màu vàng */
        border: 1px solid #c7b200;
        color: #000;
    }

    .btn-success:hover {
        background-color: #fdd835;
    }

    .btn-secondary {
        background-color: #007bff; /* Nút Back màu xanh dương */
        border: 1px solid #0056b3;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #0056b3;
    }

    .alert {
        background-color: #ffeb3b; 
        color: #000;
        border: 1px solid #c7b200;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">chỉnh thông tin sinh viên</h2>

        <?php if ($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="sinhVienEdit.php?id=<?php echo $maSV; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="HoTen">HoTen</label>
                <input type="text" class="form-control" id="HoTen" name="HoTen" value="<?php echo $student['HoTen']; ?>" required>
            </div>

            <div class="form-group">
                <label for="GioiTinh">GioiTinh</label>
                <select class="form-control" id="GioiTinh" name="GioiTinh" required>
                    <option value="Nam" <?php echo ($student['GioiTinh'] === 'Nam') ? 'selected' : ''; ?>>Nam</option>
                    <option value="Nữ" <?php echo ($student['GioiTinh'] === 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                </select>
            </div>

            <div class="form-group">
                <label for="NgaySinh">NgaySinh</label>
                <input type="date" class="form-control" id="NgaySinh" name="NgaySinh"
                    value="<?php echo date('Y-m-d', strtotime($student['NgaySinh'])); ?>" required>
            </div>

            <div class="form-group">
                <label for="Hinh">Hinh</label>
                <input type="file" class="form-control-file" id="Hinh" name="Hinh">
                <?php if ($student['Hinh']): ?>
                    <div class="mt-2">
                        <img src="<?php echo $student['Hinh']; ?>" alt="Student Image" style="width: 100px;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="MaNganh">MaNganh</label>
                <select class="form-control" id="MaNganh" name="MaNganh" required>
                    <?php while ($major = $majors->fetch_assoc()): ?>
                        <option value="<?php echo $major['MaNganh']; ?>"
                            <?php echo ($major['MaNganh'] === $student['MaNganh']) ? 'selected' : ''; ?>>
                            <?php echo $major['MaNganh'] . ' - ' . $major['TenNganh']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
</div>

<?php
// Get content from buffer
$content = ob_get_clean();

// Include layout
include 'views/layout.php';
?>
