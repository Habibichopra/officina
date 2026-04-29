<?php


class DatabaseManager
{

    private $conn;

    public function __construct()
    {

        require_once __DIR__ . "/../config/config.php";

        $this->conn = new mysqli(
            Config::$hostname,  
            Config::$username, 
            Config::$password, 
            Config::$dbname,   
        );

        if ($this->conn->connect_error) {
            die("Errore di connessione: " . $this->conn->connect_error);
        }
    }


    public function query($q)
    {
        return $this->conn->query($q);
    }


    public function getConnection() {
        return $this->conn;
    }
}
