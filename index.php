<?php
// Include database connection
require_once 'config/db.php';
require_once 'models/SinhVien.php';

// Create student model instance
$sinhVienModel = new SinhVien($conn);

// Get all students
$students = $sinhVienModel->getAllStudents();

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

    .btn-primary {
        background-color: #ffc107; /* Yellow button */
        color: #000;
        font-weight: bold;
    }

    .btn-primary:hover {
        background-color: #ffca28;
    }

    .btn-info {
        background-color: #007bff; /* Blue button */
        color: white;
    }

    .btn-info:hover {
        background-color: #0056b3;
    }

    .btn-danger {
        background-color: #d32f2f; /* Red button */
        color: white;
    }

    .btn-danger:hover {
        background-color: #b32828;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 0.8em;
    }

    .text-center {
        text-align: center;
    }

    img {
        max-width: 100px;
        height: auto;
        border-radius: 5px;
        display: block;
        margin: 10px auto;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4">TRANG DANH SÁCH SINH VIÊN</h2>
        <a href="sinhVienAdd.php" class="btn btn-success mb-3">Add Student</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>MaSV</th>
                    <th>HoTen</th>
                    <th>GioiTinh</th>
                    <th>NgaySinh</th>
                    <th>Hình</th>
                    <th>MaNganh</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($students->num_rows > 0): ?>
                    <?php while ($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['MaSV']; ?></td>
                            <td><?php echo $row['HoTen']; ?></td>
                            <td><?php echo $row['GioiTinh']; ?></td>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($row['NgaySinh'])); ?></td>
                            <td>
                                <?php if ($row['Hinh']): ?>
                                    <img src="<?php echo $row['Hinh']; ?>" alt="Student Image">
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['MaNganh']; ?></td>
                            <td>
                                <a href="sinhVienEdit.php?id=<?php echo $row['MaSV']; ?>" class="btn btn-sm btn-primary">Edit</a> |
                                <a href="sinhVienDetails.php?id=<?php echo $row['MaSV']; ?>" class="btn btn-sm btn-info">Details</a> |
                                <a href="sinhVienDelete.php?id=<?php echo $row['MaSV']; ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No students found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Get content from buffer
$content = ob_get_clean();

// Include layout
include 'views/layout.php';
?>