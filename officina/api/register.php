<?php

//abilita visualizzazione errori per debug
require_once __DIR__ . "/../misc/functions.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type:application/json");

if (!isset($_POST["cognome"]) || !isset($_POST["nome"]) || !isset($_POST["password"]) || !isset($_POST["telefono"])) {
	echo error("all fields are required");
	exit;
}


$cognome = $_POST['cognome'];
$nome = $_POST['nome'];
$telefono = $_POST['telefono'];
$password = $_POST['password'];


require_once __DIR__ . "/../classes/AuthManager.php";


if (!isset($_SESSION)) {
	session_start();
}

// registrazione nuovo utente
if (AuthManager::register($cognome, $nome, $telefono, $password)) {

	$_SESSION["cognome"] = $cognome;
	$_SESSION["nome"] = $nome;
	echo ok("registration successful");
	exit;
	
} else {

	echo error("error while registering, try another username");
	exit;
}
