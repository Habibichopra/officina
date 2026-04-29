<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/OfficineManager.php";
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    echo rispostaErrore("Accesso negato");
    exit;
}

$idOfficina = (int)($_POST['id_officina'] ?? 0);
$idServizio = (int)($_POST['id_servizio'] ?? 0);

if (!$idOfficina || !$idServizio) {
    echo rispostaErrore("Dati mancanti");
    exit;
}

$m = new OfficineManager();
echo $m->associaServizio($idOfficina, $idServizio)
    ? rispostaOk("Servizio associato")
    : rispostaErrore("Errore associazione");
