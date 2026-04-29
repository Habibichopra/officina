<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/AuthManager.php";
session_start();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password']      ?? '';

if (!$username || !$password) { echo rispostaErrore("Compila tutti i campi"); exit; }

$utente = AuthManager::loginDipendente($username, $password);
if ($utente) {
    $_SESSION['tipo']        = 'dipendente';
    $_SESSION['username']    = $username;
    $_SESSION['id_officina'] = $utente['id_officina'];
    echo rispostaOk("Login effettuato");
} else {
    echo rispostaErrore("Credenziali errate");
}
