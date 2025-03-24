<?php
class HocPhan
{
    private $conn;

    // Constructor - Initialize with database connection
    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    // Get all courses
    public function getAllCourses()
    {
        $sql = "SELECT * FROM hocphan ORDER BY MaHP";
        $result = $this->conn->query($sql);

        return $result;
    }

    // Get course by ID
    public function getCourseById($maHP)
    {
        $sql = "SELECT * FROM hocphan WHERE MaHP = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maHP);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Add available slots column to HocPhan table if it doesn't exist
    public function addSoLuongColumn()
    {
        $sql = "SHOW COLUMNS FROM hocphan LIKE 'SoLuong'";
        $result = $this->conn->query($sql);

        if ($result->num_rows == 0) {
            $sql = "ALTER TABLE hocphan ADD COLUMN SoLuong INT DEFAULT 100";
            $this->conn->query($sql);

            // Initialize all courses with 100 slots
            $sql = "UPDATE hocphan SET SoLuong = 100";
            $this->conn->query($sql);
        }
    }

    // Get all courses with available slots
    public function getCoursesWithSlots()
    {
        // Make sure the SoLuong column exists
        $this->addSoLuongColumn();

        $sql = "SELECT * FROM hocphan ORDER BY MaHP";
        $result = $this->conn->query($sql);

        return $result;
    }

    // Decrease available slots for a course
    public function decreaseSlots($maHP)
    {
        $sql = "UPDATE hocphan SET SoLuong = SoLuong - 1 WHERE MaHP = ? AND SoLuong > 0";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maHP);

        return $stmt->execute();
    }

    // Increase available slots for a course (when removing from cart)
    public function increaseSlots($maHP)
    {
        $sql = "UPDATE hocphan SET SoLuong = SoLuong + 1 WHERE MaHP = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maHP);

        return $stmt->execute();
    }
}
