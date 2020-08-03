<?php
class Database {

    // укажите свои учетные данные базы данных 
    private $host = "std-mysql";
    private $db_name = "std_237";
    private $username = "std_237";
    private $password = "Qaa123321@";
    public $conn;

    // получаем соединение с БД 
    public function getConnection(){

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>