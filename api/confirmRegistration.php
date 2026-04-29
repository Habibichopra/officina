<?php
require_once "../misc/functions.php";
require_once "../classes/AuthManager.php";

$otp = $_GET['otp'] ?? '';

if (!$otp) {
    echo "<h1>Errore: OTP mancante</h1>";
    exit;
}

if (AuthManager::confermaRegistrazione($otp)) {
    echo "<h1>Registrazione confermata!</h1><p>Ora puoi effettuare il login.</p><a href='../pages/login_cliente.html'>Vai al login</a>";
} else {
    echo "<h1>Errore: OTP non valido o scaduto</h1>";
}