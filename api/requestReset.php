<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/AuthManager.php";

$mail = trim($_POST['mail'] ?? '');

if (!$mail) { echo rispostaErrore("Inserisci l'email"); exit; }

if (AuthManager::requestPasswordReset($mail)) {
    echo rispostaOk("Email di reset inviata");
} else {
    echo rispostaErrore("Email non trovata o errore");
}