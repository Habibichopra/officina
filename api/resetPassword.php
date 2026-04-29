<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/AuthManager.php";

$otp = $_POST['otp'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';

if (!$otp || !$newPassword) { echo rispostaErrore("Compila tutti i campi"); exit; }

if (AuthManager::resetPassword($otp, $newPassword)) {
    echo rispostaOk("Password resettata");
} else {
    echo rispostaErrore("OTP non valido o scaduto");
}