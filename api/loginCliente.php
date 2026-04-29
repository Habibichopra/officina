<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/AuthManager.php";
session_start();

$mail     = trim($_POST['mail']     ?? '');
$password = $_POST['password']      ?? '';

if (!$mail || !$password) { echo rispostaErrore("Compila tutti i campi"); exit; }

$utente = AuthManager::loginCliente($mail, $password);
if ($utente) {
    $_SESSION['tipo'] = 'cliente';
    $_SESSION['mail'] = $mail;
    echo rispostaOk("Login effettuato");
} else {
    echo rispostaErrore("Credenziali errate o account non confermato");
}
