<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/OfficineManager.php";
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'dipendente') {
    echo rispostaErrore("Accesso non autorizzato");
    exit;
}

$idOfficina = $_SESSION['id_officina'] ?? null;
if (!$idOfficina) {
    echo rispostaErrore("Nessuna officina associata a questo account");
    exit;
}

$manager = new OfficineManager();
$pezzi = $manager->getMagazzinoOfficina($idOfficina);

echo json_encode(["status" => true, "dati" => $pezzi]);
