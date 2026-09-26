<?php
class DATABASE {
    private static $dns = "mysql:host=localhost;dbname=mutickets;port=3306;charset=utf8mb4";
    private static $username = "root";
    private static $password = ""; // Mật khẩu XAMPP mặc định để rỗng
    private static $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    );    
    private static $db;
    
    private function __construct() {} 
    
    public static function connect() {
        if (!isset(self::$db)) {
            try {
                self::$db = new PDO(
                    self::$dns, 
                    self::$username, 
                    self::$password, 
                    self::$options
                );
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                echo "<p>Lỗi kết nối: $error_message</p>";
                exit();
            }
        }
        return self::$db;
    }

    public static function disconnect() {
        self::$db = null;
    }

    public static function execute_nonquery($sql, $option = array()) {
        self::connect();
        if (self::$db != null) {
            try {
                $cmd = self::$db->prepare($sql);
                if (count($option) > 0) {
                    for ($i = 0; $i < count($option); $i++) {
                        $cmd->bindValue($i + 1, $option[$i]);
                    }
                }
                $cmd->execute();
                $ketqua = $cmd->fetchAll();
                return $ketqua;
            } catch (PDOException $ex) {
                echo "<p>Lỗi truy vấn: " . $ex->getMessage() . "</p>";
            }
        } else {
            echo "<p>Lỗi kết nối cơ sở dữ liệu</p>";
        }
    }
}
?>