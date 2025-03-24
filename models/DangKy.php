<?php
class DangKy
{
    private $conn;
    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    // Create new registration
    public function createRegistration($maSV)
    {
        $ngayDK = date("Y-m-d");

        $sql = "INSERT INTO dangky (NgayDK, MaSV) VALUES (?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $ngayDK, $maSV);

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        } else {
            return false;
        }
    }

    public function addCourseToRegistration($maDK, $maHP)
    {
        $sql = "SELECT COUNT(*) as count FROM chitietdangky WHERE MaDK = ? AND MaHP = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $maDK, $maHP);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            return false; // Already registered for this course
        }

        // Add the course to registration
        $sql = "INSERT INTO chitietdangky (MaDK, MaHP) VALUES (?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $maDK, $maHP);

        return $stmt->execute();
    }

    public function removeCourseFromRegistration($maDK, $maHP)
    {
        $sql = "DELETE FROM chitietdangky WHERE MaDK = ? AND MaHP = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $maDK, $maHP);

        return $stmt->execute();
    }

    public function deleteRegistration($maDK)
    {
        $sql = "DELETE FROM chitietdangky WHERE MaDK = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maDK);
        $stmt->execute();

        $sql = "DELETE FROM dangky WHERE MaDK = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maDK);

        return $stmt->execute();
    }

    public function getRegistrationCourses($maDK)
    {
        $sql = "SELECT c.*, h.TenHP, h.SoTinChi 
                FROM chitietdangky c
                JOIN hocphan h ON c.MaHP = h.MaHP
                WHERE c.MaDK = ?
                ORDER BY h.MaHP";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maDK);
        $stmt->execute();

        return $stmt->get_result();
    }

    public function getRegistrationById($maDK)
    {
        $sql = "SELECT d.*, s.HoTen, s.NgaySinh, n.TenNganh
                FROM dangky d
                JOIN sinhvien s ON d.MaSV = s.MaSV
                JOIN nganhhoc n ON s.MaNganh = n.MaNganh
                WHERE d.MaDK = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maDK);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }
    public function getStudentLatestRegistration($maSV)
    {
        $sql = "SELECT * FROM dangky WHERE MaSV = ? ORDER BY MaDK DESC LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maSV);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
}
