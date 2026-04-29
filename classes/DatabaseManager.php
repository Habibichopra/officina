<?php
class DatabaseManager {
    private $conn;

    public function __construct() {
        require_once __DIR__ . "/../config/config.php";
        $this->conn = new mysqli(Config::$hostname, Config::$username, Config::$password, Config::$dbname);
        if ($this->conn->connect_error) die("Errore connessione: " . $this->conn->connect_error);
        $this->conn->set_charset("utf8mb4");
    }

    public function getConn() {
        return $this->conn;
    }
}
