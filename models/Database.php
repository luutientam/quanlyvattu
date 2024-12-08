<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "quanlyvattu";
    private $connection;

    // Tạo kết nối trong constructor
    public function __construct() {
        $this->connect();
    }

    // Phương thức tạo kết nối
    private function connect() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        // Kiểm tra kết nối
        if ($this->connection->connect_error) {
            die("Kết nối thất bại: " . $this->connection->connect_error);
        } else {
            echo "Kết nối database thành công.<br>";
        }
    }

    // Trả về kết nối mysqli
    public function getConnection() {
        return $this->connection;
    }

    // Đóng kết nối khi đối tượng bị hủy
    public function __destruct() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>