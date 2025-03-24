<?php
class SinhVien
{
    private $conn;

    // Constructor - Initialize with database connection
    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    // Get all students
    public function getAllStudents()
    {
        $sql = "SELECT s.*, n.TenNganh 
                FROM sinhvien s
                JOIN nganhhoc n ON s.MaNganh = n.MaNganh
                ORDER BY s.MaSV";
        $result = $this->conn->query($sql);

        return $result;
    }

    // Get student by ID
    public function getStudentById($maSV)
    {
        $sql = "SELECT s.*, n.TenNganh 
                FROM sinhvien s
                JOIN nganhhoc n ON s.MaNganh = n.MaNganh
                WHERE s.MaSV = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maSV);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Add new student
    public function addStudent($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh)
    {
        $sql = "INSERT INTO sinhvien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh);

        return $stmt->execute();
    }

    // Update student
    public function updateStudent($maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh)
    {
        $sql = "UPDATE sinhvien 
                SET HoTen = ?, GioiTinh = ?, NgaySinh = ?, Hinh = ?, MaNganh = ? 
                WHERE MaSV = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh, $maSV);

        return $stmt->execute();
    }

    // Delete student
    public function deleteStudent($maSV)
    {
        // First check if student has any registrations
        $sql = "SELECT COUNT(*) as count FROM dangky WHERE MaSV = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maSV);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            return false; // Cannot delete if student has registrations
        }

        // Delete the student
        $sql = "DELETE FROM sinhvien WHERE MaSV = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maSV);

        return $stmt->execute();
    }

    // Get all majors
    public function getAllMajors()
    {
        $sql = "SELECT * FROM nganhhoc ORDER BY MaNganh";
        $result = $this->conn->query($sql);

        return $result;
    }
}
