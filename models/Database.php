<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "quanlyvattu";
    private $connection;

    public function __construct() {

        $this->connect();
    }

    private function connect() {

        // Sử dụng mysqli để kết nối

        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->connection->connect_error) {
            die("Kết nối thất bại: " . $this->connection->connect_error);
        }
    }
<<<<<<< HEAD

=======
    // Trả về kết nối mysqli
>>>>>>> origin/branch-cua-mot-thanh-vien-ten-la-thang
    public function getConnection() {
        return $this->connection;
    }
    
}

?>