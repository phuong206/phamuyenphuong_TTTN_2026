<?php
class Database {
    private $host = "localhost";
    private $dbname = "qr_e_menu"; // sửa theo DB của bạn
    private $username = "root";
    private $password = "";

    public function connect() {
        try {
            $conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8",
                $this->username,
                $this->password
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            

            return $conn;

        } catch (PDOException $e) {
            echo "❌ Kết nối thất bại: " . $e->getMessage();
            return null;
        }
    }
}
?>