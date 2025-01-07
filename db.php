<?php
class Database
{
    public static $instance;
    private $conn;

    private function __construct()
    {
        $host = 'localhost';
        $port = '3306';
        $dbname = 'api_php';
        $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname;
        try {
            $this->conn = new \PDO($dsn, 'root', 'root', array(
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ));
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
