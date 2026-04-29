<?php
header("Content-Type:application/json");
require_once __DIR__ . "/../misc/functions.php";

if (!isset($_POST["cognome"]) || !isset($_POST["nome"]) || !isset($_POST["password"])) {
    echo error("all fields are required");
    exit;
}

$cognome = $_POST['cognome'];
$nome = $_POST['nome'];
$password = $_POST['password'];

require_once __DIR__ . "/../classes/AuthManager.php";

if (!isset($_SESSION)) {
    session_start();
}

if (AuthManager::login($cognome, $nome, $password)) {
    $_SESSION["cognome"] = $cognome;
    $_SESSION["nome"] = $nome;
    echo ok("login successful");
    exit;
} else {
    echo error("wrong credentials");
    exit;
}