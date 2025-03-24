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


ob_start();
?>

<style>
    body {
        background-color: #f0f8ff; 
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
        width: 90%; 
    }

    h2 {
        color: #007bff; 
        text-align: center;
        margin-bottom: 30px;
    }

    h5 {
        color: #333;
        margin-bottom: 20px;
        text-align: center;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .card-body {
        padding: 20px;
    }

    .row {
        margin-bottom: 10px;
    }

    .col-md-3 {
        font-weight: bold;
        color: #333;
    }

    .col-md-9 {
        word-wrap: break-word;
        color: #555;
    }

    img {
        max-width: 100px;
        height: auto;
        border-radius: 5px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
    }

    .btn-primary {
        background-color: #ffc107; 
        color: #000;
        font-weight: bold;
    }

    .btn-primary:hover {
        background-color: #ffca28;
    }

    .btn-secondary {
        background-color: #007bff; 
        color: white;
        margin-left: 10px;
    }

    .btn-secondary:hover {
        background-color: #0056b3;
    }

    .mt-2 {
        margin-top: 10px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">Thông tin chi tiết</h2>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>HoTen:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo $student['HoTen']; ?>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <strong>GioiTinh:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo $student['GioiTinh']; ?>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <strong>NgaySinh:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo date('d/m/Y H:i:s', strtotime($student['NgaySinh'])); ?>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <strong>Hinh:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php if ($student['Hinh']): ?>
                            <img src="<?php echo $student['Hinh']; ?>" alt="Student Image">
                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <strong>MaNganh:</strong>
                    </div>
                    <div class="col-md-9">
                        <?php echo $student['MaNganh'] . ' - ' . $student['TenNganh']; ?>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <a href="sinh_vien_edit.php?id=<?php echo $maSV; ?>" class="btn btn-primary">Edit</a> |
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>

<?php

$content = ob_get_clean();


include 'views/layout.php';
?>