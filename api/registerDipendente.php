<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/AuthManager.php";
session_start();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password']      ?? '';

if (!$username || !$password) { echo rispostaErrore("Compila tutti i campi"); exit; }

if (AuthManager::registraDipendente($username, $password)) {
    $_SESSION['tipo']     = 'dipendente';
    $_SESSION['username'] = $username;
    echo rispostaOk("Registrazione completata");
} else {
    echo rispostaErrore("Username già in uso");
}
