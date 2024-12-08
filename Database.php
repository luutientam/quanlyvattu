<?php
class Database
{
    private $host = "localhost";
    private $db_name = "quanlyvattu";
    private $username = "root";
    private $password = "";
    public $conn;

    /*************  ✨ Codeium Command ⭐  *************/
    /******  232147d4-34ee-4b6d-a1ee-84b641147639  *******/
    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
