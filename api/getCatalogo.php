<?php
header("Content-Type: application/json");
require_once "../misc/functions.php";
require_once "../classes/OfficineManager.php";

$idOfficina = (int)($_GET['id_officina'] ?? 0);
if ($idOfficina <= 0) {
    echo rispostaErrore("Id non valido");
    exit;
}

$m = new OfficineManager();
echo json_encode([
    "status"    => true,
    "officina"  => $m->getOfficina($idOfficina),
    "servizi"   => $m->getServizi($idOfficina),
    "pezzi"     => $m->getPezzi($idOfficina),
    "accessori" => $m->getAccessori($idOfficina)
]);
