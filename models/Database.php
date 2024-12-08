<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "quanlyvattu";
    private $connection;

    // Tạo kết nối trong constructor
    public function __construct() {
        // Sử dụng mysqli để kết nối
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->connection->connect_error) {
            die("Kết nối thất bại: " . $this->connection->connect_error);
        }
    }
    // Trả về kết nối mysqli
    public function getConnection() {
        return $this->connection;
    }
}

?>