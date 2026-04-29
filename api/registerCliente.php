<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/AuthManager.php";
session_start();

$mail     = trim($_POST['mail']     ?? '');
$password = $_POST['password']      ?? '';

if (!$mail || !$password) { echo rispostaErrore("Compila tutti i campi"); exit; }

try {
    AuthManager::registraCliente($mail, $password);
    echo rispostaOk("Registrazione completata. Controlla la tua email per confermare.");
} catch (Exception $e) {
    $msg = $e->getMessage();
    if ($msg === "EMAIL_ESISTENTE") {
        echo rispostaErrore("Email già registrata");
    } else {
        echo rispostaErrore($msg);
    }
}
