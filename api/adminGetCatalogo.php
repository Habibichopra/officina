<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/OfficineManager.php";
session_start();

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    echo rispostaErrore("Accesso negato");
    exit;
}

$m = new OfficineManager();
echo json_encode([
    "status"    => true,
    "servizi"   => $m->getTuttiServizi(),
    "pezzi"     => $m->getTuttiPezzi(),
    "accessori" => $m->getTuttiAccessori(),
    "officine"  => $m->getTutteLeOfficine()
]);
