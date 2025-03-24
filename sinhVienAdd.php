<?php

require_once 'config/db.php';
require_once 'models/SinhVien.php';


$sinhVienModel = new SinhVien($conn);


$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $maSV = $_POST['MaSV'];
    $hoTen = $_POST['HoTen'];
    $gioiTinh = $_POST['GioiTinh'];
    $ngaySinh = $_POST['NgaySinh'];
    $maNganh = $_POST['MaNganh'];

 
    $hinh = '';
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


    if ($sinhVienModel->addStudent($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh)) {
        header('Location: index.php');
        exit;
    } else {
        $message = 'Error adding student. Please try again.';
    }
}


$majors = $sinhVienModel->getAllMajors();


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

    .form-control-file {
        margin-top: 10px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
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
        margin-left: 10px;
    }

    .btn-secondary:hover {
        background-color: #0056b3;
    }

    .alert-danger {
        background-color: #ffe6e6; /* Light red background for error */
        color: #d32f2f; /* Dark red text for error */
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        border: 1px solid #ffcdd2;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">THÊM SINH VIÊN</h2>
        <h5>SinhVien</h5>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        
        <form action="sinhVienAdd.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="MaSV">MaSV</label>
                <input type="text" class="form-control" id="MaSV" name="MaSV" required>
            </div>

            <div class="form-group">
                <label for="HoTen">HoTen</label>
                <input type="text" class="form-control" id="HoTen" name="HoTen" required>
            </div>

            <div class="form-group">
                <label for="GioiTinh">GioiTinh</label>
                <select class="form-control" id="GioiTinh" name="GioiTinh" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>

            <div class="form-group">
                <label for="NgaySinh">NgaySinh</label>
                <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" required>
            </div>

            <div class="form-group">
                <label for="Hinh">Hinh</label>
                <input type="file" class="form-control-file" id="Hinh" name="Hinh">
            </div>

            <div class="form-group">
                <label for="MaNganh">MaNganh</label>
                <select class="form-control" id="MaNganh" name="MaNganh" required>
                    <?php while ($major = $majors->fetch_assoc()): ?>
                        <option value="<?php echo $major['MaNganh']; ?>">
                            <?php echo $major['MaNganh'] . ' - ' . $major['TenNganh']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Create</button>
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