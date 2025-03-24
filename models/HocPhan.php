<?php
class HocPhan
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }


    public function getAllCourses()
    {
        $sql = "SELECT * FROM hocphan ORDER BY MaHP";
        $result = $this->conn->query($sql);

        return $result;
    }


    public function getCourseById($maHP)
    {
        $sql = "SELECT * FROM hocphan WHERE MaHP = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maHP);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }


    public function addSoLuongColumn()
    {
        $sql = "SHOW COLUMNS FROM hocphan LIKE 'SoLuong'";
        $result = $this->conn->query($sql);

        if ($result->num_rows == 0) {
            $sql = "ALTER TABLE hocphan ADD COLUMN SoLuong INT DEFAULT 100";
            $this->conn->query($sql);


            $sql = "UPDATE hocphan SET SoLuong = 100";
            $this->conn->query($sql);
        }
    }


    public function getCoursesWithSlots()
    {

        $this->addSoLuongColumn();

        $sql = "SELECT * FROM hocphan ORDER BY MaHP";
        $result = $this->conn->query($sql);

        return $result;
    }


    public function decreaseSlots($maHP)
    {
        $sql = "UPDATE hocphan SET SoLuong = SoLuong - 1 WHERE MaHP = ? AND SoLuong > 0";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maHP);

        return $stmt->execute();
    }


    public function increaseSlots($maHP)
    {
        $sql = "UPDATE hocphan SET SoLuong = SoLuong + 1 WHERE MaHP = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maHP);

        return $stmt->execute();
    }
}
