<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/AuthManager.php";
session_start();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password']      ?? '';

if (!$username || !$password) {
    echo rispostaErrore("Compila tutti i campi");
    exit;
}

$utente = AuthManager::loginAdmin($username, $password);
if ($utente) {
    $_SESSION['tipo']     = 'admin';
    $_SESSION['username'] = $username;
    echo rispostaOk("Login effettuato");
} else {
    echo rispostaErrore("Credenziali errate");
}
