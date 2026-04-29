<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Config {
    static public $hostname;
    static public $username;
    static public $password;
    static public $dbname;
}

Config::$hostname = $_ENV['DB_HOST'];
Config::$username = $_ENV['DB_USER'];
Config::$password = $_ENV['DB_PASS'];
Config::$dbname   = $_ENV['DB_NAME'];
