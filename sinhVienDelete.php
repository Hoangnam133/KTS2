<?php
// Include database connection
require_once 'config/db.php';
require_once 'models/SinhVien.php';

// Create student model instance
$sinhVienModel = new SinhVien($conn);

// Check if ID is provided
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

// Process form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // Delete student from database
    if ($sinhVienModel->deleteStudent($maSV)) {
        header('Location: index.php');
        exit;
    } else {
        $message = 'Error deleting student. The student may have active registrations.';
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

    h4 {
        color: #d32f2f; /* Dark red for confirmation */
        text-align: center;
        margin-bottom: 20px;
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
    }

    .col-md-9 {
        word-wrap: break-word;
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

    .btn-danger {
        background-color: #d32f2f; /* Red button */
        color: white;
    }

    .btn-danger:hover {
        background-color: #b32828;
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
        <h2 class="mb-4">XÓA THÔNG TIN SINH VIÊN</h2>

        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

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
                        <?php echo $student['MaNganh']; ?>
                    </div>
                </div>
            </div>
        </div>

        <form action="sinhVienDelete.php?id=<?php echo $maSV; ?>" method="post">
            <input type="hidden" name="confirm_delete" value="1">
            <button type="submit" class="btn btn-danger">Delete</button>
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